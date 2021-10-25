<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer';
public $timestamps = false;
    public function genCode()
    {
    	$prefix = "AP-";
    	$val = Customer::max('id')+1;
        if($val<10)
            $prefix .= "000" . $val;
        else if($val>9 && $val < 99)
            $prefix .= "00" . $val;
        else if($val>99 && $val < 999)
            $prefix .= "0" . $val;
        else if($val>999 && $val < 9999)
            $prefix .= $val;

        return $prefix;
    }


    public function supply()
    {
        return $this->hasMany('App\Supply','customer_id','id');
    }

    public function bills()
    {
        return $this->hasMany('App\Bills','customer_id','id');
    }
}
