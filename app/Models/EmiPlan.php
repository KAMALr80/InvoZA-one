<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmiPlan extends Model
{
    use HasFactory, SoftDeletes;

  protected $fillable = [
    'sale_id',
    'total_amount',
    'down_payment',
    'months',
    'emi_amount',
    'status',
];



    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
