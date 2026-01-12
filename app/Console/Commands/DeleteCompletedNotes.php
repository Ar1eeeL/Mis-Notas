<?php

namespace App\Console\Commands;

use App\Models\Note;
use Illuminate\Console\Command;

class DeleteCompletedNotes extends Command
{
    protected $signature = 'notes:delete-completed {--days=7 : Días después de completadas para eliminar}';

    protected $description = 'Eliminar notas completadas después de X días';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $deleted = Note::where('is_completed', true)
            ->where('updated_at', '<', $cutoffDate)
            ->delete();

        $this->info("Se eliminaron {$deleted} nota(s) completada(s) con más de {$days} días de antigüedad.");

        return 0;
    }
}
