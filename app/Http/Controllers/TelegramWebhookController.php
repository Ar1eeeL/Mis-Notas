<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request, TelegramService $telegram)
    {
        $update = $request->all();

        if (isset($update['callback_query'])) {
            $this->handleCallback($update['callback_query'], $telegram);
            return response()->json(['ok' => true]);
        }

        if (isset($update['message']['text'])) {
            $this->handleMessage($update['message'], $telegram);
        }

        return response()->json(['ok' => true]);
    }

    private function handleCallback(array $callback, TelegramService $telegram)
    {
        $data = $callback['data'];
        $chatId = $callback['message']['chat']['id'];
        $messageId = $callback['message']['message_id'];
        $token = config('services.telegram.bot_token');

        if (str_starts_with($data, 'complete_')) {
            $note = Note::find(substr($data, 9));

            if ($note && !$note->is_completed) {
                $note->update(['is_completed' => true]);

                Http::post("https://api.telegram.org/bot{$token}/answerCallbackQuery", [
                    'callback_query_id' => $callback['id'],
                    'text' => "¡Nota completada! ✅"
                ]);

                Http::post("https://api.telegram.org/bot{$token}/editMessageText", [
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'text' => $callback['message']['text'] . "\n\n✅ <b>COMPLETADA</b>",
                    'parse_mode' => 'HTML'
                ]);
            } else {
                Http::post("https://api.telegram.org/bot{$token}/answerCallbackQuery", [
                    'callback_query_id' => $callback['id'],
                    'text' => "La nota ya fue procesada"
                ]);
            }
        }
    }

    private function handleMessage(array $message, TelegramService $telegram)
    {
        $text = trim($message['text']);
        $chatId = $message['chat']['id'];
        $firstName = $message['from']['first_name'] ?? 'Usuario';

        // Extraer comando y parámetro (para deep links como /start CODIGO)
        $parts = explode(' ', $text, 2);
        $command = $parts[0];
        $param = $parts[1] ?? null;

        match (true) {
            $command === '/start' && $param && preg_match('/^[A-Z0-9]{8}$/', $param) => $this->linkAccount($telegram, $chatId, $param),
            $command === '/start' => $this->sendWelcomeMessage($telegram, $chatId, $firstName),
            $command === '/estado' => $this->checkStatus($telegram, $chatId),
            $command === '/notas' => $this->listNotes($telegram, $chatId),
            (bool) preg_match('/^[A-Z0-9]{8}$/', $text) => $this->linkAccount($telegram, $chatId, $text),
            default => null,
        };
    }

    private function sendWelcomeMessage(TelegramService $telegram, string $chatId, string $name)
    {
        $message = "👋 ¡Hola {$name}!\n\n" .
            "Soy tu bot de recordatorios.\n\n" .
            "📋 <b>Vincular cuenta:</b>\n" .
            "1. Ve a tu perfil web\n" .
            "2. Genera un código\n" .
            "3. Envíamelo aquí\n\n" .
            "📝 <b>Comandos:</b>\n" .
            "/notas - Ver pendientes\n" .
            "/estado - Ver vinculación";

        $telegram->sendMessage($chatId, $message);
    }

    private function checkStatus(TelegramService $telegram, string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if ($user) {
            $telegram->sendMessage($chatId, "✅ <b>Vinculado con:</b> {$user->name}");
        } else {
            $telegram->sendMessage($chatId, "❌ <b>No vinculado</b>");
        }
    }

    private function listNotes(TelegramService $telegram, string $chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $telegram->sendMessage($chatId, "❌ Primero vincula tu cuenta.");
            return;
        }

        $notes = $user->notes()
            ->where('is_completed', false)
            ->orderBy('reminder_at')
            ->take(5)
            ->get();

        if ($notes->isEmpty()) {
            $telegram->sendMessage($chatId, "📭 No tienes notas pendientes.");
            return;
        }

        $telegram->sendMessage($chatId, "📋 <b>Tus notas pendientes:</b>");

        foreach ($notes as $note) {
            $this->sendNoteMessage($telegram, $chatId, $note);
        }
    }

    private function sendNoteMessage(TelegramService $telegram, string $chatId, Note $note)
    {
        $priorityMap = [
            'urgent' => '🔴 Urgente',
            'high' => '🟠 Alta',
            'normal' => '🔵 Normal',
            'low' => '🟢 Baja',
        ];

        $priority = $priorityMap[$note->priority] ?? '⚪ Normal';
        $reminder = $note->reminder_at ? $note->reminder_at->format('d/m H:i') : 'Sin fecha';

        $msg = "<b>{$priority}</b>\n" .
            "<b>{$note->title}</b>\n" .
            "{$note->content}\n" .
            "📅 {$reminder}";

        $options = [
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => '✅ Completar', 'callback_data' => "complete_{$note->id}"]
                ]]
            ])
        ];

        $telegram->sendMessage($chatId, $msg, $options);
    }

    private function linkAccount(TelegramService $telegram, string $chatId, string $code)
    {
        $user = User::where('telegram_link_code', $code)->first();

        if ($user) {
            $user->update([
                'telegram_chat_id' => $chatId,
                'telegram_link_code' => null,
            ]);
            $telegram->sendMessage($chatId, "✅ <b>¡Vinculado!</b> Hola {$user->name}.");
        } else {
            $telegram->sendMessage($chatId, "❌ <b>Código inválido</b>");
        }
    }
}
