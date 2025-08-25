<?php

namespace App\Console\Commands;

use App\Services\GameTickService;
use Illuminate\Console\Command;
use App\Models\Government;
use App\Models\GameSetting;

class BalanceTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:balance-test {--ticks=100 : Number of ticks to simulate} {--interval=10 : Display interval}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test game balance over multiple ticks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ticks = (int) $this->option('ticks');
        $interval = (int) $this->option('interval');
        $gameTickService = new GameTickService();

        $this->info("Starting balance test for {$ticks} ticks...");
        $this->info("Displaying results every {$interval} ticks");
        $this->line("");

        // Store initial state
        $initialState = $this->captureGameState();
        $this->displayGameState($initialState, 0, "Initial State");

        for ($i = 0; $i < $ticks; $i++) {
            $gameTickService->processTick();
            
            if (($i + 1) % $interval === 0) {
                $currentState = $this->captureGameState();
                $this->displayGameState($currentState, $i + 1, "After " . ($i + 1) . " ticks");
                
                // Check for balance issues
                $this->checkBalanceIssues($currentState, $initialState, $i + 1);
            }
        }

        $finalState = $this->captureGameState();
        $this->displayGameState($finalState, $ticks, "Final State");
        
        $this->info("Balance test completed!");
    }

    private function captureGameState()
    {
        $governments = Government::all();
        $state = [];
        
        foreach ($governments as $government) {
            $state[$government->id] = [
                'name' => $government->name,
                'population' => $government->population,
                'money' => $government->money,
                'overall' => $government->overall,
                'economy' => $government->economy,
                'health' => $government->health,
                'safety' => $government->safety,
                'education' => $government->education,
                'resources' => []
            ];
            
            foreach ($government->government_resources as $resource) {
                $state[$government->id]['resources'][$resource->resource->name] = $resource->amount;
            }
        }
        
        return $state;
    }

    private function displayGameState($state, $tick, $label)
    {
        $this->line("=== {$label} (Tick {$tick}) ===");
        
        foreach ($state as $govId => $govData) {
            $this->line("Government: {$govData['name']}");
            $this->line("  Population: {$govData['population']}");
            $this->line("  Money: {$govData['money']}");
            $this->line("  Overall Score: {$govData['overall']}");
            $this->line("  Sectors - E:{$govData['economy']} H:{$govData['health']} S:{$govData['safety']} Ed:{$govData['education']}");
            
            $resourceInfo = [];
            foreach ($govData['resources'] as $resource => $amount) {
                $resourceInfo[] = "{$resource}:{$amount}";
            }
            $this->line("  Resources: " . implode(' ', $resourceInfo));
        }
        $this->line("");
    }

    private function checkBalanceIssues($currentState, $initialState, $tick)
    {
        foreach ($currentState as $govId => $currentGov) {
            $initialGov = $initialState[$govId] ?? null;
            
            if ($initialGov) {
                // Check for exponential growth
                $populationGrowth = $currentGov['population'] / max(1, $initialGov['population']);
                $moneyGrowth = $currentGov['money'] / max(1, $initialGov['money']);
                $overallGrowth = $currentGov['overall'] / max(1, $initialGov['overall']);
                
                if ($populationGrowth > 10) {
                    $this->warn("⚠️  Population growth too high: {$populationGrowth}x after {$tick} ticks");
                }
                
                if ($moneyGrowth > 20) {
                    $this->warn("⚠️  Money growth too high: {$moneyGrowth}x after {$tick} ticks");
                }
                
                if ($overallGrowth > 5) {
                    $this->warn("⚠️  Overall score growth too high: {$overallGrowth}x after {$tick} ticks");
                }
                
                // Check for resource overflow
                foreach ($currentGov['resources'] as $resource => $amount) {
                    if ($amount > 10000) {
                        $this->warn("⚠️  Resource overflow: {$resource} at {$amount}");
                    }
                }
                
                // Check for negative values
                if ($currentGov['money'] < 0) {
                    $this->error("❌ Negative money: {$currentGov['money']}");
                }
                
                if ($currentGov['overall'] < 0) {
                    $this->error("❌ Negative overall score: {$currentGov['overall']}");
                }
            }
        }
    }
}
