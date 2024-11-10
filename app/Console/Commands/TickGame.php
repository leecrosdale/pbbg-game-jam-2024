<?php

namespace App\Console\Commands;

use App\Services\GameTickService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TickGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tick-game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {


//        do {

            Log::info('Game tick started');
            app(GameTickService::class)->processTick();
            Log::info('Game tick completed');
//            sleep(15);
//
//        } while(true);

        return Command::SUCCESS;

    }
}
