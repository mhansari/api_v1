<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';
public $timestamps = false;
    

    /*public function customer()
    {
        return $this->hasMany('App\Customer','brand_id','id');
    }*/

    
}
