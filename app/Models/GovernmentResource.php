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

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function government()
    {
        $this->belongsTo(Government::class);
    }

}
