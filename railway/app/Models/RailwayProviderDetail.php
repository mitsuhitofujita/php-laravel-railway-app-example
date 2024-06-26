<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RailwayProviderDetail extends Model
{
    use HasFactory;

    protected $table = 'railway_provider_details';

    const UPDATED_AT = null;

    protected $fillable = [
        'valid_from',
        'name',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'name' => 'string',
        'created_at' => 'datetime',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';
}
