<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentInfrastructure extends Model
{

    /** @use HasFactory<\Database\Factories\GovernmentInfrastructureFactory> */
    use HasFactory;
    use HasUuid;


    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function government()
    {
        return $this->belongsTo(Government::class);
    }


    public function getNextTickAttribute()
    {

        $base = $this->infrastructure->base;
        $increase = $base * $this->level * pow($this->population, 0.001);
        $maxIncrease = 500;
        return floor(min($increase, $maxIncrease));

    }

    public function getUpgradeCostAttribute()
    {

        $base_cost = 100;
        $cost_multiplier = 1.5;

        // Calculate the cost for the next efficiency upgrade
        return (int)($base_cost * pow($cost_multiplier, $this->efficiency));
    }


    public function setPopulation(int $population)
    {

        $currentPopulation = $this->population;

        if ($population > $this->government->available_population ) {
            return false;
        }


        $remaining = $currentPopulation - $population;

//        if ($population < $this->population) {
//            $remaining = $this->government->available_population + $population;
//        } else if ($population > $this->population) {
//            $remaining = $this->government->available_population - $population;
//        }


        $this->population = $population;
        $this->save();


        if ($remaining > 0) {
            $this->government->available_population += $remaining;
        } else if ($remaining < 0) {
            $this->government->available_population -= abs($remaining);
        }

        $this->government->save();


        return true;

    }

}
