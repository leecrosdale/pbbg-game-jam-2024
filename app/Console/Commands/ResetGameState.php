<?php

namespace App\Console\Commands;

use App\Models\GameSetting;
use Illuminate\Console\Command;

class ResetGameState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:reset-state {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the game state (turn, season) without affecting player data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $confirmed = $this->option('confirm');

        if (!$confirmed) {
            if (!$this->confirm('Are you sure you want to reset the game state? This will reset the turn counter and season, but keep all player data intact.')) {
                $this->info('Reset cancelled.');
                return 0;
            }
        }

        $this->info('Resetting game state...');

        try {
            // Reset turn to 1
            GameSetting::updateOrCreate(
                ['name' => 'turn'],
                ['value' => '1']
            );
            $this->line('✓ Reset turn to 1');

            // Reset season to spring
            GameSetting::updateOrCreate(
                ['name' => 'season'],
                ['value' => 'spring']
            );
            $this->line('✓ Reset season to spring');

            // Reset turn state to planning
            GameSetting::updateOrCreate(
                ['name' => 'turn_state'],
                ['value' => 'planning']
            );
            $this->line('✓ Reset turn state to planning');

            $this->info('✅ Game state reset successfully!');
            $this->info('📊 Current game state:');
            $this->line('   Turn: ' . GameSetting::where('name', 'turn')->first()->value);
            $this->line('   Season: ' . GameSetting::where('name', 'season')->first()->value);
            $this->line('   State: ' . GameSetting::where('name', 'turn_state')->first()->value);

        } catch (\Exception $e) {
            $this->error('❌ Error resetting game state: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
