<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
protected $fillable = [
    'asset_name', 
    'category', 
    'serial_number', 
    'purchase_date', 
    'value', 
    'quantity',
    'next_service_date',
    'image'
];
}
