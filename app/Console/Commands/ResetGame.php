<?php

namespace App\Console\Commands;

use App\Models\Government;
use App\Models\GovernmentInfrastructure;
use App\Models\GovernmentResource;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:reset {--user= : Reset specific user by email} {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the game for all players or a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userEmail = $this->option('user');
        $confirmed = $this->option('confirm');

        if ($userEmail) {
            $this->resetSpecificUser($userEmail, $confirmed);
        } else {
            $this->resetAllUsers($confirmed);
        }
    }

    private function resetSpecificUser(string $email, bool $confirmed = false)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        if (!$confirmed) {
            if (!$this->confirm("Are you sure you want to reset the game for user '{$email}'? This will delete all their government data, resources, infrastructure, and policies.")) {
                $this->info('Reset cancelled.');
                return 0;
            }
        }

        $this->info("Resetting game for user: {$user->name} ({$email})");
        
        try {
            DB::beginTransaction();

            // Delete user's government and all related data
            if ($user->government) {
                $governmentId = $user->government->id;
                
                // Delete policies
                Policy::where('government_id', $governmentId)->delete();
                $this->line('✓ Deleted policies');

                // Delete government infrastructure
                GovernmentInfrastructure::where('government_id', $governmentId)->delete();
                $this->line('✓ Deleted infrastructure');

                // Delete government resources
                GovernmentResource::where('government_id', $governmentId)->delete();
                $this->line('✓ Deleted resources');

                // Delete the government itself
                $user->government()->delete();
                $this->line('✓ Deleted government');

                // Create a new government with default values
                $this->createNewGovernment($user);
                $this->line('✓ Created new government with default values');
            }

            DB::commit();
            $this->info("✅ Game reset successfully for user '{$email}'");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error resetting game: " . $e->getMessage());
            return 1;
        }

        return 0;
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
            DB::beginTransaction();

            // Delete all policies
            $policyCount = Policy::count();
            Policy::truncate();
            $this->line("✓ Deleted {$policyCount} policies");

            // Delete all government infrastructure
            $infraCount = GovernmentInfrastructure::count();
            GovernmentInfrastructure::truncate();
            $this->line("✓ Deleted {$infraCount} infrastructure items");

            // Delete all government resources
            $resourceCount = GovernmentResource::count();
            GovernmentResource::truncate();
            $this->line("✓ Deleted {$resourceCount} resource records");

            // Delete all governments
            $governmentCount = Government::count();
            Government::truncate();
            $this->line("✓ Deleted {$governmentCount} governments");

            // Create new governments for all users
            $users = User::all();
            $bar = $this->output->createProgressBar($users->count());
            $bar->start();

            foreach ($users as $user) {
                $this->createNewGovernment($user);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->line("✓ Created new governments for {$users->count()} users");

            DB::commit();
            $this->info("✅ Game reset successfully for all {$userCount} users");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error resetting game: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function createNewGovernment(User $user)
    {
        // Create new government with default values from config
        $government = Government::create([
            'user_id' => $user->id,
            'name' => $user->name . "'s Government",
            'population' => config('game.settings.starting_population', 100),
            'available_population' => config('game.settings.starting_population', 100),
            'money' => config('game.economy.starting_money', 1000),
            'economy' => 1,
            'health' => 1,
            'safety' => 1,
            'education' => 1,
            'overall' => 4,
            'economy_population' => 0,
            'health_population' => 0,
            'safety_population' => 0,
            'education_population' => 0,
        ]);

        // Create default resources
        $resources = \App\Models\Resource::all();
        foreach ($resources as $resource) {
            $defaultAmount = rand(50, 200); // Random starting amount
            GovernmentResource::create([
                'government_id' => $government->id,
                'resource_id' => $resource->id,
                'amount' => $defaultAmount,
                'population_usage' => 0,
            ]);
        }
    }
}
