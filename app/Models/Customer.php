<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

      use SoftDeletes;
    protected $fillable = [
    'name',
    'mobile',
    'email',
    'address',
    'gst_no',
    'open_balance',
];


    /**
     * Customer ke saare sales
     */
    public function sales()
    {
        return $this->hasMany(\App\Models\Sale::class);
    }


}
