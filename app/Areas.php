<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'areas';
public $timestamps = false;
    

    /*public function customer()
    {
        return $this->hasMany('App\Customer','brand_id','id');
    }*/

    
}
