<?php

namespace App\Console\Commands;

use App\Services\GameTickService;
use Illuminate\Console\Command;
use App\Models\Government;
use App\Models\GameSetting;

class TickGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:tick {--count=1 : Number of ticks to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process game tick and update all governments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $gameTickService = new GameTickService();

        $this->info("Processing {$count} game tick(s)...");

        for ($i = 0; $i < $count; $i++) {
            $this->info("Processing tick " . ($i + 1) . "...");
            
            // Get current turn before processing
            $currentTurn = GameSetting::where('name', 'turn')->value('value');
            
            $gameTickService->processTick();
            
            // Get updated turn
            $newTurn = GameSetting::where('name', 'turn')->value('value');
            
            // Display balance information
            $this->displayBalanceInfo();
            
            $this->info("Tick {$newTurn} completed successfully.");
        }

        $this->info("Game tick processing completed!");
    }

    private function displayBalanceInfo()
    {
        $governments = Government::all();
        
        foreach ($governments as $government) {
            $this->line("Government: {$government->name}");
            $this->line("  Population: {$government->population}");
            $this->line("  Money: {$government->money}");
            $this->line("  Overall Score: {$government->overall}");
            $this->line("  Sectors - Economy: {$government->economy}, Health: {$government->health}, Safety: {$government->safety}, Education: {$government->education}");
            
            // Display resource levels
            $resources = $government->government_resources;
            $resourceInfo = [];
            foreach ($resources as $resource) {
                $resourceInfo[] = "{$resource->resource->name}: {$resource->amount}";
            }
            $this->line("  Resources: " . implode(', ', $resourceInfo));
            $this->line("");
        }
    }
}
