<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Resource extends Model
{

    use HasUuid;

    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    protected $guarded = [];


    public function adjustMarketPrices()
    {

        $minFluctuation = -0.1;
        $maxFluctuation = 0.1;

        $price = $this->price;

        // Generate a random fluctuation percentage
        $fluctuation = mt_rand($minFluctuation * 100, $maxFluctuation * 100) / 100;

        // Calculate the new price, ensuring it stays above a minimum threshold
        $newPrice = max(1, $price * (1 + $fluctuation));

        // Log the price change for debugging
        Log::debug([
            'Resource Type' => $this->name,
            'Old Price' => $price,
            'Fluctuation' => $fluctuation * 100 . '%',
            'New Price' => $newPrice,
        ]);

        $this->price = $newPrice;

        // Save the updated prices to the database
        $this->save();
    }


}
