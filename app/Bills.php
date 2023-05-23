<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bills';
public $timestamps = false;
    public static function getBillsByDate($sDate, $eDate, $uid)
    {
    	return Supply::with('customer')->where('delivery_date','>=', $sDate)->where('delivery_date','<=', $eDate)->where('customer_id','=', $uid)->get();
    }
    public static function genCode($issue_date)
    {
        $str_to_time = strtotime($issue_date);
     $prefix = date('Y', $str_to_time) .  date('m', $str_to_time);

       $valc =  DB::table('bills')
                   ->select(DB::raw('ifnull(MAX(bill_number),'. $prefix . '0000) as bill_number'))
                   ->where(DB::raw('YEAR(bill_issue_date)'), '=', date('Y', $str_to_time))
                   ->where(DB::raw('MONTH(bill_issue_date)'), '=', date('m', $str_to_time))->get();
$val = $valc[0]->bill_number ;
$val += 1;

        

        return $val;
    }
    public function genBillNumber($date)
    {
        $s_timestamp = strtotime($date);             
        $prefix = date("Y-m", $s_timestamp);
        $val = Bills::max('bill_number')+1;
        
        return $prefix.$val;
    }
    public static function getBillsByYear($uid)
    {

    	return DB::table('bills')->selectRaw('grand_total,qty,DATE_FORMAT(bill_issue_date, "%b-%Y") m')->whereBetween('bill_issue_date',array('2019-04-01', '2020-04-30'))->where('customer_id','=', $uid)
->orderBy("bill_issue_date",'asc')

    	->get();
    }
    public static function getBillsByUser($uid)
    {

        return DB::table('bills')->selectRaw('*')->where('customer_id','=', $uid)->orderBy('bill_issue_date')->get();
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id');
    }


    
}
