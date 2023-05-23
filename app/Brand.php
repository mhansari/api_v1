<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brand';
public $timestamps = false;
    

    public function customer()
    {
        return $this->hasMany('App\Customer','brand_id','id');
    }

    
}
