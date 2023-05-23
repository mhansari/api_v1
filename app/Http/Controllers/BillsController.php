<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Supply;
use App\Bills;
use App\BillDetail;
use App\Payments;
use App\BillingStatus;
use DB;
use Concerns\InteractsWithInput;
use Illuminate\Support\Facades\Hash;
class BillsController extends ApiController
{
	public $successStatus = 200;

    public function getBillingStatusList()
    {
        $result = array();
        try{
            $bs=BillingStatus::where('active', '=', '1')->get();
        
        $result['billing_statuses'] = $bs->toArray();
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();

        }
         return response()->json($result, $this->successStatus); 
    }

    public function getSupplyRecordsSummary(Request $req)
    {
        $result = array();
        try{
            $timestamp = strtotime($req->s_date,);             
            $s_date = date("Y-m-d", $timestamp);
            $timestamp = strtotime($req->e_date);             
            $e_date = date("Y-m-d", $timestamp); 

            $supply = Supply::getSupplyByDate($s_date, $e_date,$req->customer_id);
        
        $result['supply'] = $supply;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();

        }
         return response()->json($result, $this->successStatus); 
    }

    public function getSupplyRecordsSummaryByBillId(Request $req)
    {
       /* try{
        $_sql = "select c.code,concat(c.first_name , ' ' , c.last_name) name, c.address, c.mobile,c.rate, asr.* from bills b inner join bill_detail bd on bd.bill_id = b.id inner join archive_supply_records asr on asr.id = bd.archive_supply_records_id inner join customer c on c.id = asr.customer_id where b.id = ". $req->bill_id . " order by delivery_date asc ";
   
        $supply = DB::select($_sql);
        $result['supply'] = $supply;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();

        }
         return response()->json($result, $this->successStatus); 
         */

         $result = array();
        try{
            $timestamp = strtotime($req->s_date,);             
            $s_date = date("Y-m-d", $timestamp);
            $timestamp = strtotime($req->e_date);             
            $e_date = date("Y-m-d", $timestamp); 

            $supply = BillDetail::getSupplyByBillId($req->bill_id);
        
        $result['supply'] = $supply;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();

        }
         return response()->json($result, $this->successStatus); 
    }

    public function add(Request $req)
    {
        $result = array();
        //DB::transaction(function(){
        try{
           $collection = collect($req->customer_bill_data);
           $timestamp = strtotime($req->start_date);             
            $s_date = date("Y-m-d", $timestamp);
            $timestamp = strtotime($req->end_date);             
            $e_date = date("Y-m-d", $timestamp); 
           

          
           $i = 0;
           $amount = 0;
           for(; $i< count($collection); $i++) {
            $code = Bills::genCode($req->bill_generation_date);
            $supply = Supply::getSupplyByDate($s_date, $e_date, $collection[$i]['customer_id']);
            $b = new Bills();
            $b->bill_number = $code;
            $b->customer_id = $collection[$i]['customer_id'];
            $b->qty = $collection[$i]['qty'];
            $b->rate = $collection[$i]['rate'];
            $b->total = $collection[$i]['bill_amount'];
            $b->adv_amount = $collection[$i]['adv_amount'];
            $b->balance_amount = $collection[$i]['bal_amount'];
            $amount += $b->grand_total = $collection[$i]['at'];
            $b->comments = $collection[$i]['additional_comments'];
            $b->active = 1;
            $b->date_created = Carbon::now()->toDateTimeString();
            $b->date_updated = Carbon::now()->toDateTimeString();
            $b->bill_issue_date  = date("Y-m-d", strtotime($req->bill_generation_date));
            $b->is_payment_received = 0;
            $b->billing_period   = $req->start_date . ',' .   $req->end_date;
            $b->save();
            
            for($j = 0; $j < count($supply); $j++){
                $bd = new BillDetail();
                $bd->bill_id = $b->id;
                $bd->customer_id = $collection[$i]['customer_id'];
                $bd->archive_supply_records_id = $supply[$j]->id;
                $bd->save();
                $asr = Supply::find($supply[$j]->id);
                $asr->is_billed = true;
                $asr->billed_date = date("Y-m-d", strtotime($req->bill_generation_date));
                $asr->save();
            }
           }



            $result['data'] = 'Total Bills Generated/Amount : ' .  $i . '/' . $amount;
            $result['status'] = true;
 
        }
        catch(\Exception $e){

            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
         
        if($result['status']){
            return $this->success($result['data']);
        }else{
            return $this->fail($result['message']);
        }
    }
	public function getSupplyRecordsForBill(Request $req)
    {
        $_sql = "select c.delivery_days,c.id, concat(c.first_name , ' ', c.last_name) as name, c.address,
        c.mobile, sum(btl_delievered) qty, c.rate, (sum(btl_delievered) * c.rate) bill_amount,SUM(amount_balance) c_bill_amount,ifnull((select ifnull(p.bal_amount, b.grand_total) from bills b left join payments p on b.id = p.bill_id where b.customer_id = c.id order by b.id desc limit 1),0) as bal_amount, ifnull((select ifnull(p.adv_amount, 0) from bills b left join payments p on b.id = p.bill_id where b.customer_id = c.id order by b.id desc limit 1),0) * -1 adv_amount from customer c inner join archive_supply_records asr on c.id = asr.customer_id where btl_delievered > 0 and amount_balance > 0 and c.active=1 and c.billing=1 and asr.is_billed=0 ";
    try{

        $name ='';
        $mobile = '';
        $code = '';
       
        $address = '';
       
        $day = '';

        $brand = '';


        if($req->dd !='0'){
            $_sql .= " and c.delivery_days like ('%". $req->dd. "%')" ;
        }

        if($req->brand_id !='0'){
            $_sql .= " and c.brand_id = ". $req->brand_id ;
        }

        if($req->customer_id !='0'){
            $_sql .= " and asr.customer_id = ". $req->customer_id ;
        }
        $s_timestamp = strtotime($req->s_delivery_date);             
            $s_dd = date("Y-m-d", $s_timestamp);

        $e_timestamp = strtotime($req->e_delivery_date);             
            $e_dd = date("Y-m-d", $e_timestamp);

        $_sql .= " and  asr.delivery_date >= '" . $s_dd . "' and asr.delivery_date <= '" . $e_dd . "'";

 
        $_sql .= " group by c.id,name , c.address, c.mobile, c.rate,delivery_days order by name asc, delivery_days asc";



        $query = DB::select($_sql);
         $result['sql'] = $_sql;
        $result['users_billing'] = $query;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
            $result['sql'] = $_sql;
        }
         return response()->json($result, $this->successStatus); 
    }

public function SearchBills(Request $req)
    {
        
    try{

        $status ='';
        $mobile = '';
        $code = '';
       
        $cid = '';
       
        $day = '';

        $brand = '';


        if($req->dd !='0'){
            $day = " and c.delivery_days like ('%". $req->dd. "%')" ;
        }

        if($req->brand_id !='0'){
            $brand = " and c.brand_id = ". $req->brand_id ;
        }

        if($req->customer_id !='0'){
            $cid = " and b.customer_id = ". $req->customer_id ;
        }

if($req->status !='0'){
            $status = " and bs.id = ". $req->status ;
        }
        $_sql = "SELECT b.billing_period, b.customer_id,b.id, b.bill_number, b.qty, b.rate, b.total bill_amount, b.adv_amount, b.balance_amount as bal_amount, b.grand_total as c_bill_amount, b.comments, b.received_payment, b.received_date, concat(c.first_name, ' ' , c.last_name) name, c.address, c.mobile, bs.name as status FROM bills AS b inner join billing_status bs on bs.id = b.billing_status_id INNER JOIN customer AS c ON c.id = b.customer_id WHERE SUBSTRING(CAST(b.bill_issue_date AS nchar(10)), 1, 7) = '" . $req->biling_date . "' and (b.bill_number LIKE '%" . $req->bill_number . "%') AND ((c.first_name LIKE '%" . $req->name . "%') or (c.last_name LIKE '%" . $req->name . "%')) AND (c.mobile LIKE '%" . $req->mobile . "%')  AND (c.address LIKE '%" . $req->address . "%') " . $day . $brand . $cid . $status . " order by bill_number, first_name, last_name asc ";



        $query = DB::select($_sql);
         $result['sql'] = $_sql;
        $result['users_billing'] = $query;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
            $result['sql'] = $_sql;
        }
         return response()->json($result, $this->successStatus); 
    }
    public function view(Request $req)
    {
        
    try{

       // $_sql = "SELECT b.billing_period, b.customer_id,b.id, b.bill_number, b.qty, b.rate, b.total bill_amount, b.adv_amount, b.balance_amount as bal_amount, b.grand_total as c_bill_amount, b.comments, b.received_payment, b.received_date, concat(c.first_name, ' ' , c.last_name) name, c.address, c.mobile, bs.name as status FROM bills AS b inner join billing_status bs on bs.id = b.billing_status_id INNER JOIN customer AS c ON c.id = b.customer_id  order by bill_number, first_name, last_name asc ";
        $_sql = "SELECT b.billing_period, b.customer_id,b.id, b.bill_number, b.qty, b.rate, b.total bill_amount, b.adv_amount, b.balance_amount as bal_amount, b.grand_total as c_bill_amount, b.comments, b.received_payment, b.received_date, concat(c.first_name, ' ' , c.last_name) name, c.address, c.mobile, bs.name as status, asr.btl_delievered, asr.delivery_date , c.code FROM bills AS b inner join billing_status bs on bs.id = b.billing_status_id inner join bill_detail bd on bd.bill_id = b.id inner join archive_supply_records asr on asr.id = bd.archive_supply_records_id INNER JOIN customer AS c ON c.id = b.customer_id  order by bill_number, first_name, last_name asc";

        $query = DB::select($_sql);
        $result['sql'] = $_sql;
        $result['bills'] = $query;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
            $result['sql'] = $_sql;
        }
         return response()->json($result, $this->successStatus); 
    }
}