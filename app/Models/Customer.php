<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'address',
        'gst_no'
    ];

    /**
     * Customer ke saare sales
     */
    public function sales()
    {
        return $this->hasMany(\App\Models\Sale::class);
    }
}
