<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remarks extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'remarks';
public $timestamps = false;
    

    public function supply()
    {
        return $this->hasMany('App\Supply','remarks_id','id');
    }

    
}
