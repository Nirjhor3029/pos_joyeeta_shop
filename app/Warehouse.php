<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable =[

        "name", "phone", "email", "address", "is_active"
    ];

    public function product()
    {
    	return $this->hasMany('App\Product');
    	
    }
        public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
