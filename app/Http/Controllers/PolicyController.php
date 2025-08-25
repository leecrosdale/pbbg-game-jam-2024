<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\Government;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PolicyController extends Controller
{
    public function index(): View
    {
        $government = auth()->user()->government;
        $availablePolicies = $this->getAvailablePolicies();
        $activePolicies = $government->policies()->where('active', true)->get();
        
        return view('policies.index', compact('government', 'availablePolicies', 'activePolicies'));
    }

    public function enact(Request $request): RedirectResponse
    {
        $request->validate([
            'policy_type' => 'required|string|in:economic_stimulus,healthcare_reform,education_investment,security_enhancement,infrastructure_development,environmental_protection',
        ]);

        $government = auth()->user()->government;
        $policyType = $request->policy_type;
        
        // Get policy cost
        $cost = $this->getPolicyCost($policyType);
        
        // Check if government has enough money
        if ($government->money < $cost) {
            return back()->withErrors(['money' => 'Insufficient funds to enact this policy.']);
        }
        
        // Create and apply the policy
        $policy = Policy::create([
            'government_id' => $government->id,
            'type' => $policyType,
            'cost' => $cost,
            'duration' => 10, // 10 turns
            'active' => true,
        ]);
        
        // Apply policy effects
        $policy->applyPolicyEffects();
        
        // Deduct cost from government
        $government->decrement('money', $cost);
        
        return redirect()->route('policies.index')->with('success', 'Policy enacted successfully!');
    }

    public function cancel(Policy $policy): RedirectResponse
    {
        $policy->update(['active' => false]);
        
        return redirect()->route('policies.index')->with('success', 'Policy cancelled successfully!');
    }

    private function getAvailablePolicies(): array
    {
        return [
            'economic_stimulus' => [
                'name' => 'Economic Stimulus',
                'description' => 'Boost economic growth and increase money generation',
                'cost' => 1000,
                'effects' => ['+0.5 Economy', '-$1000'],
                'icon' => 'fas fa-chart-line',
                'color' => 'from-green-500 to-green-600',
            ],
            'healthcare_reform' => [
                'name' => 'Healthcare Reform',
                'description' => 'Improve public health and medicine production',
                'cost' => 800,
                'effects' => ['+0.5 Health', '+20% Medicine Production', '-$800'],
                'icon' => 'fas fa-heartbeat',
                'color' => 'from-red-500 to-red-600',
            ],
            'education_investment' => [
                'name' => 'Education Investment',
                'description' => 'Enhance education system and research capabilities',
                'cost' => 1200,
                'effects' => ['+0.5 Education', '+10% Research Efficiency', '-$1200'],
                'icon' => 'fas fa-graduation-cap',
                'color' => 'from-blue-500 to-blue-600',
            ],
            'security_enhancement' => [
                'name' => 'Security Enhancement',
                'description' => 'Strengthen security and reduce crime rates',
                'cost' => 900,
                'effects' => ['+0.5 Safety', '-15% Crime Rate', '-$900'],
                'icon' => 'fas fa-shield-alt',
                'color' => 'from-purple-500 to-purple-600',
            ],
            'infrastructure_development' => [
                'name' => 'Infrastructure Development',
                'description' => 'Accelerate construction and reduce maintenance costs',
                'cost' => 1500,
                'effects' => ['+25% Construction Speed', '-10% Maintenance', '-$1500'],
                'icon' => 'fas fa-building',
                'color' => 'from-yellow-500 to-yellow-600',
            ],
            'environmental_protection' => [
                'name' => 'Environmental Protection',
                'description' => 'Improve happiness and resource efficiency',
                'cost' => 600,
                'effects' => ['+0.3 Happiness', '+20% Resource Efficiency', '-$600'],
                'icon' => 'fas fa-leaf',
                'color' => 'from-emerald-500 to-emerald-600',
            ],
        ];
    }

    private function getPolicyCost(string $policyType): float
    {
        $policies = $this->getAvailablePolicies();
        return $policies[$policyType]['cost'] ?? 1000;
    }
}
