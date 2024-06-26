<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RailwayLineDetail extends Model
{
    use HasFactory;

    protected $table = 'railway_line_details';

    const UPDATED_AT = null;

    protected $fillable = [
        'railway_provider_id',
        'valid_from',
        'name',
    ];

    protected $casts = [
        'railway_provider_id' => 'integer',
        'valid_from' => 'datetime',
        'name' => 'string',
        'created_at' => 'datetime',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';
}
