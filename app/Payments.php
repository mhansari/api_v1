<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';
public $timestamps = false;
    public static function getBillsByDate($sDate, $eDate, $uid)
    {
    	return Supply::with('customer')->where('delivery_date','>=', $sDate)->where('delivery_date','<=', $eDate)->where('customer_id','=', $uid)->get();
    }

    public static function getPaymentsByBill($bill_id)
    {
    	return Payments::with('emp')->where('bill_id','=', $bill_id)->get();
    }

    public static function getPaymentsByYear($uid)
    {

    	return DB::table('payments')->selectRaw('amount,grand_total,DATE_FORMAT(received_at, "%b-%Y") m')->join('bills', 'bills.id', '=', 'payments.bill_id')->whereBetween('received_at',array('2019-04-01', '2020-04-30'))->where('customer_id','=', $uid)
->orderBy("received_at",'asc')

    	->get();
    }

    public function bills()
    {
        return $this->belongsTo('App\Bills','bill_id');
    }
    public function emp()
    {
        return $this->belongsTo('App\Employees','received_by_id');
    }
}
