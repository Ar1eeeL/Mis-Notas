<?php

namespace App\Console\Commands;

use App\Models\Note;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class SendNoteReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notes:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Telegram reminders for notes that are due';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegram)
    {
        $notes = Note::pendingReminders()->get();

        if ($notes->isEmpty()) {
            $this->info('No hay recordatorios pendientes.');
            return 0;
        }

        $this->info("Procesando {$notes->count()} recordatorio(s)...");

        $sent = 0;
        $failed = 0;

        foreach ($notes as $note) {
            if (empty($note->telegram_chat_id)) {
                $this->warn("Nota #{$note->id} sin Chat ID de Telegram, saltando...");
                continue;
            }

            $success = $telegram->sendReminder(
                $note->telegram_chat_id,
                $note->title,
                $note->content,
                $note->category,
                $note->priority,
                $note->id
            );

            if ($success) {
                $note->update(['reminder_sent' => true]);
                $sent++;
                $this->info("✓ Recordatorio enviado para nota #{$note->id}: {$note->title}");
            } else {
                $failed++;
                $this->error("✗ Error al enviar recordatorio para nota #{$note->id}");
            }
        }

        $this->newLine();
        $this->info("Resumen: {$sent} enviados, {$failed} fallidos");

        return 0;
    }
}
