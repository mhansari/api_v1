<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bill_detail';
public $timestamps = false;
    public static function getSupplyByBillId($id)
    {
           return BillDetail::with('supply')->where('bill_id','=', $id)->get();
    }

    public function bills()
    {
        return $this->hasMany('App\Bills','bill_id','id');
    }
public function supply()
    {
        return $this->hasMany('App\Supply','archive_supply_records_id','id');
    }
    
}
