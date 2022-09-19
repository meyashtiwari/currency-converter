<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
    ];

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class);
    }
}
