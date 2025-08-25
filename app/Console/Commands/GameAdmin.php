<?php

namespace App\Console\Commands;

use App\Models\Government;
use App\Models\GameSetting;
use App\Models\User;
use Illuminate\Console\Command;

class GameAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:admin 
                            {action : Action to perform (reset-all, reset-user, reset-state, status, list-users)}
                            {--user= : User email for reset-user action}
                            {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Game administration commands for managing players and game state';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $confirmed = $this->option('confirm');

        switch ($action) {
            case 'reset-all':
                return $this->resetAllUsers($confirmed);
            case 'reset-user':
                return $this->resetSpecificUser($confirmed);
            case 'reset-state':
                return $this->resetGameState($confirmed);
            case 'status':
                return $this->showGameStatus();
            case 'list-users':
                return $this->listUsers();
            default:
                $this->error("Unknown action: {$action}");
                $this->info('Available actions: reset-all, reset-user, reset-state, status, list-users');
                return 1;
        }
    }

    private function resetAllUsers(bool $confirmed = false)
    {
        $userCount = User::count();

        if ($userCount === 0) {
            $this->info('No users found to reset.');
            return 0;
        }

        if (!$confirmed) {
            if (!$this->confirm("Are you sure you want to reset the game for ALL {$userCount} users? This will delete all government data, resources, infrastructure, and policies for every player.")) {
                $this->info('Reset cancelled.');
                return 0;
            }
        }

        $this->info("Resetting game for {$userCount} users...");
        
        try {
            // Call the ResetGame command
            $this->call('game:reset', ['--confirm' => true]);
            $this->info("✅ Game reset successfully for all {$userCount} users");
        } catch (\Exception $e) {
            $this->error("❌ Error resetting game: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function resetSpecificUser(bool $confirmed = false)
    {
        $userEmail = $this->option('user');

        if (!$userEmail) {
            $this->error('Please specify a user email with --user option');
            return 1;
        }

        $user = User::where('email', $userEmail)->first();

        if (!$user) {
            $this->error("User with email '{$userEmail}' not found.");
            return 1;
        }

        if (!$confirmed) {
            if (!$this->confirm("Are you sure you want to reset the game for user '{$userEmail}'? This will delete all their government data, resources, infrastructure, and policies.")) {
                $this->info('Reset cancelled.');
                return 0;
            }
        }

        $this->info("Resetting game for user: {$user->name} ({$userEmail})");
        
        try {
            // Call the ResetGame command with specific user
            $this->call('game:reset', ['--user' => $userEmail, '--confirm' => true]);
            $this->info("✅ Game reset successfully for user '{$userEmail}'");
        } catch (\Exception $e) {
            $this->error("❌ Error resetting game: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function resetGameState(bool $confirmed = false)
    {
        if (!$confirmed) {
            if (!$this->confirm('Are you sure you want to reset the game state? This will reset the turn counter and season, but keep all player data intact.')) {
                $this->info('Reset cancelled.');
                return 0;
            }
        }

        $this->info('Resetting game state...');

        try {
            // Call the ResetGameState command
            $this->call('game:reset-state', ['--confirm' => true]);
            $this->info('✅ Game state reset successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Error resetting game state: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function showGameStatus()
    {
        $this->info('📊 Game Status:');
        $this->newLine();

        // Game state
        $turn = GameSetting::where('name', 'turn')->first()->value ?? 'Unknown';
        $season = GameSetting::where('name', 'season')->first()->value ?? 'Unknown';
        $turnState = GameSetting::where('name', 'turn_state')->first()->value ?? 'Unknown';

        $this->line("🕐 Turn: {$turn}");
        $this->line("🍃 Season: {$season}");
        $this->line("⚡ State: {$turnState}");
        $this->newLine();

        // Player statistics
        $userCount = User::count();
        $governmentCount = Government::count();
        $activeGovernments = Government::where('available_population', '>', 0)->count();

        $this->line("👥 Total Users: {$userCount}");
        $this->line("🏛️ Total Governments: {$governmentCount}");
        $this->line("✅ Active Governments: {$activeGovernments}");
        $this->newLine();

        // Top players
        $topPlayers = Government::orderByDesc('overall')->take(5)->get();
        if ($topPlayers->count() > 0) {
            $this->line('🏆 Top 5 Players:');
            foreach ($topPlayers as $index => $government) {
                $rank = $index + 1;
                $this->line("   {$rank}. {$government->name} - Score: " . number_format($government->overall, 1));
            }
        }

        return 0;
    }

    private function listUsers()
    {
        $users = User::with('government')->get();

        if ($users->count() === 0) {
            $this->info('No users found.');
            return 0;
        }

        $this->info('👥 Registered Users:');
        $this->newLine();

        $headers = ['ID', 'Name', 'Email', 'Government', 'Population', 'Score', 'Money'];
        $rows = [];

        foreach ($users as $user) {
            $government = $user->government;
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $government ? $government->name : 'None',
                $government ? number_format($government->population) : '0',
                $government ? number_format($government->overall, 1) : '0.0',
                $government ? '$' . number_format($government->money) : '$0',
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }
}
