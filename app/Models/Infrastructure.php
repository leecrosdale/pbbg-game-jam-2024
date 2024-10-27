<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infrastructure extends Model
{

    use HasUuid;

    /** @use HasFactory<\Database\Factories\InfrastructureFactory> */
    use HasFactory;
}
