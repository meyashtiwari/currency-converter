<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'date',
        'rate',
        'currency_code',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
