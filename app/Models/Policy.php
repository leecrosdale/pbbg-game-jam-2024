<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Policy extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'effects' => 'array',
        'cost' => 'float',
        'duration' => 'integer',
    ];

    public function government(): BelongsTo
    {
        return $this->belongsTo(Government::class);
    }

    public function applyPolicyEffects(): void
    {
        $effects = $this->getPolicyEffects();
        
        foreach ($effects as $effect) {
            $this->applyEffect($effect['type'], $effect['value']);
        }
    }

    public function getPolicyEffects(): array
    {
        return match($this->type) {
            'economic_stimulus' => [
                ['type' => 'economy_boost', 'value' => 0.5], // +0.5 economy
                ['type' => 'money_cost', 'value' => -1000], // -$1000
            ],
            'healthcare_reform' => [
                ['type' => 'health_boost', 'value' => 0.5], // +0.5 health
                ['type' => 'medicine_production', 'value' => 0.2], // +20% medicine production
                ['type' => 'money_cost', 'value' => -800], // -$800
            ],
            'education_investment' => [
                ['type' => 'education_boost', 'value' => 0.5], // +0.5 education
                ['type' => 'research_bonus', 'value' => 0.1], // +10% research efficiency
                ['type' => 'money_cost', 'value' => -1200], // -$1200
            ],
            'security_enhancement' => [
                ['type' => 'safety_boost', 'value' => 0.5], // +0.5 safety
                ['type' => 'crime_reduction', 'value' => 0.15], // -15% crime rate
                ['type' => 'money_cost', 'value' => -900], // -$900
            ],
            'infrastructure_development' => [
                ['type' => 'construction_bonus', 'value' => 0.25], // +25% construction speed
                ['type' => 'maintenance_reduction', 'value' => 0.1], // -10% maintenance costs
                ['type' => 'money_cost', 'value' => -1500], // -$1500
            ],
            'environmental_protection' => [
                ['type' => 'happiness_boost', 'value' => 0.3], // +0.3 happiness
                ['type' => 'sustainability_bonus', 'value' => 0.2], // +20% resource efficiency
                ['type' => 'money_cost', 'value' => -600], // -$600
            ],
            default => [],
        };
    }

    private function applyEffect(string $type, float $value): void
    {
        $government = $this->government;
        
        match($type) {
            'economy_boost' => $government->increment('economy', $value),
            'health_boost' => $government->increment('health', $value),
            'education_boost' => $government->increment('education', $value),
            'safety_boost' => $government->increment('safety', $value),
            'happiness_boost' => $this->addHappiness($value),
            'money_cost' => $government->increment('money', $value),
            'medicine_production' => $this->boostResourceProduction('medicine', $value),
            'research_bonus' => $this->addResearchBonus($value),
            'crime_reduction' => $this->reduceCrime($value),
            'construction_bonus' => $this->addConstructionBonus($value),
            'maintenance_reduction' => $this->reduceMaintenance($value),
            'sustainability_bonus' => $this->addSustainabilityBonus($value),
            default => null,
        };
    }

    private function addHappiness(float $value): void
    {
        $happinessResource = $this->government->government_resources()
            ->whereHas('resource', function($query) {
                $query->where('name', 'happiness');
            })->first();
            
        if ($happinessResource) {
            $happinessResource->increment('amount', $value * 100);
        }
    }

    private function boostResourceProduction(string $resourceType, float $multiplier): void
    {
        // This would affect infrastructure production rates
        // For now, we'll just log the effect
        \Log::info("Policy {$this->type} boosted {$resourceType} production by {$multiplier}");
    }

    private function addResearchBonus(float $value): void
    {
        // This would affect research/technology advancement
        \Log::info("Policy {$this->type} added research bonus of {$value}");
    }

    private function reduceCrime(float $value): void
    {
        // This would reduce negative events or improve safety
        \Log::info("Policy {$this->type} reduced crime by {$value}");
    }

    private function addConstructionBonus(float $value): void
    {
        // This would reduce infrastructure upgrade costs
        \Log::info("Policy {$this->type} added construction bonus of {$value}");
    }

    private function reduceMaintenance(float $value): void
    {
        // This would reduce ongoing maintenance costs
        \Log::info("Policy {$this->type} reduced maintenance by {$value}");
    }

    private function addSustainabilityBonus(float $value): void
    {
        // This would improve resource efficiency
        \Log::info("Policy {$this->type} added sustainability bonus of {$value}");
    }

    public function getCost(): float
    {
        return $this->cost ?? 1000.0;
    }

    public function getDescription(): string
    {
        return match($this->type) {
            'economic_stimulus' => 'Boost economic growth and increase money generation',
            'healthcare_reform' => 'Improve public health and medicine production',
            'education_investment' => 'Enhance education system and research capabilities',
            'security_enhancement' => 'Strengthen security and reduce crime rates',
            'infrastructure_development' => 'Accelerate construction and reduce maintenance costs',
            'environmental_protection' => 'Improve happiness and resource efficiency',
            default => 'Unknown policy effect',
        };
    }

    public function getDuration(): int
    {
        return $this->duration ?? 10; // Default 10 turns
    }

    public function isActive(): bool
    {
        return $this->created_at->addTurns($this->getDuration())->isFuture();
    }
}
