<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $apiUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', '');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    public function sendMessage(string $chatId, string $message, array $options = []): bool
    {
        if (empty($this->botToken)) {
            Log::warning('Telegram bot token not configured');
            return false;
        }

        try {
            $payload = array_merge([
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ], $options);

            $response = Http::post("{$this->apiUrl}/sendMessage", $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error("Telegram API error: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("Telegram service error: " . $e->getMessage());
            return false;
        }
    }

    public function sendReminder(string $chatId, string $title, string $content, ?string $category = null, ?string $priority = null, $noteId = null): bool
    {
        $priorityEmojis = [
            'low' => '🟢',
            'normal' => '🔵',
            'high' => '🟠',
            'urgent' => '🔴',
        ];

        $emoji = $priorityEmojis[$priority] ?? '📝';

        $message = "{$emoji} <b>¡Recordatorio!</b>\n\n" .
            "<b>📌 {$title}</b>\n\n" .
            "{$content}\n\n";

        if ($category) {
            $message .= "📁 Categoría: <i>{$category}</i>\n";
        }

        if ($priority) {
            $priorityMap = [
                'low' => 'Baja',
                'normal' => 'Normal',
                'high' => 'Alta',
                'urgent' => 'Urgente',
            ];
            $priorityText = $priorityMap[$priority] ?? ucfirst($priority ?? 'normal');
            $message .= "⚡ Prioridad: <i>{$priorityText}</i>";
        }

        $options = [];
        if ($noteId) {
            $options['reply_markup'] = json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '✅ Marcar como completada', 'callback_data' => "complete_{$noteId}"]
                    ]
                ]
            ]);
        }

        return $this->sendMessage($chatId, $message, $options);
    }

    public function getMe(): ?array
    {
        if (empty($this->botToken)) {
            return null;
        }

        try {
            $response = Http::get("{$this->apiUrl}/getMe");
            return $response->successful() ? $response->json('result') : null;
        } catch (\Exception $e) {
            Log::error("Telegram getMe error: " . $e->getMessage());
            return null;
        }
    }

    public function setWebhook(string $url): bool
    {
        if (empty($this->botToken)) {
            return false;
        }

        try {
            $response = Http::post("{$this->apiUrl}/setWebhook", ['url' => $url]);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Telegram setWebhook error: " . $e->getMessage());
            return false;
        }
    }
}
