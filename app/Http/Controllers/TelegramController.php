<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramController extends Controller
{
    /**
     * Generate a new link code for the user
     */
    public function generateCode()
    {
        $user = Auth::user();
        $code = $user->generateTelegramLinkCode();

        return response()->json([
            'success' => true,
            'code' => $code,
            'message' => 'Código generado. Envíalo al bot @' . config('services.telegram.bot_username'),
        ]);
    }

    /**
     * Unlink Telegram from the account
     */
    public function unlink()
    {
        $user = Auth::user();
        $user->update([
            'telegram_chat_id' => null,
            'telegram_link_code' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Telegram desvinculado correctamente',
        ]);
    }

    /**
     * Get Telegram status
     */
    public function status()
    {
        $user = Auth::user();

        return response()->json([
            'linked' => !empty($user->telegram_chat_id),
            'bot_username' => config('services.telegram.bot_username'),
        ]);
    }

    /**
     * Send a test notification
     */
    public function test(TelegramService $telegram)
    {
        $user = Auth::user();

        if (empty($user->telegram_chat_id)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes Telegram vinculado',
            ], 400);
        }

        $success = $telegram->sendMessage(
            $user->telegram_chat_id,
            "🔔 <b>Mensaje de prueba</b>\n\n" .
                "¡Hola {$user->name}! Esta es una notificación de prueba.\n\n" .
                "Tu sistema de recordatorios está funcionando correctamente. ✅"
        );

        return response()->json([
            'success' => $success,
            'message' => $success ? '¡Mensaje enviado! Revisa tu Telegram.' : 'Error al enviar el mensaje',
        ]);
    }
}
