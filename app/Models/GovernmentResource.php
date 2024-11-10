<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentResource extends Model
{
    /** @use HasFactory<\Database\Factories\GovernmentResourceFactory> */
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function government()
    {
        return $this->belongsTo(Government::class);
    }

    public function getPopulationUsageAttribute()
    {
        /** @var Government $government */
        $government = $this->government;
        return $government->calculateResourceConsumption($this->resource->name)['required'][$this->resource->name];
    }

}
