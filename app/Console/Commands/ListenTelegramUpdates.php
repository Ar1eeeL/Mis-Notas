<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListenTelegramUpdates extends Command
{
    protected $signature = 'telegram:listen';
    protected $description = 'Continuously listen for Telegram updates';

    public function handle()
    {
        $this->info('🎧 Listening for Telegram updates... (Press Ctrl+C to stop)');

        while (true) {
            $this->callSilent('telegram:process');
            sleep(2); // Wait 2 seconds between checks to avoid hitting rate limits
        }
    }
}
