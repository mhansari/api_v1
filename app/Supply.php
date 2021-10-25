<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'archive_supply_records';
public $timestamps = false;
    public static function getSupplyByDate($sDate, $eDate, $uid)
    {
    	return Supply::with('customer')->where('delivery_date','>=', $sDate)->where('delivery_date','<=', $eDate)->where('customer_id','=', $uid)->get();
    }

    public static function getSupplyByYear($uid)
    {

    	return DB::table('archive_supply_records')->selectRaw('sum(btl_delievered) total_btls,DATE_FORMAT(delivery_date, "%b-%Y") m')->whereBetween('delivery_date',array('2019-04-01', '2020-04-30'))->where('customer_id','=', $uid)
->groupBy('m')->orderBy("delivery_date",'asc')

    	->get();
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id');
    }
}
