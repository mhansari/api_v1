<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Customer;
use App\Supply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\User;
use Concerns\InteractsWithInput;
class SupplyController extends ApiController
{
    

    public function list()
    {
    	$result = array();
    	try{
    	//$supply = new Customer();
    	$res = Customer::all();
    	$result['data'] = $res->toArray();
        $result['status'] = true;
 
        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
        
        if($result['status']){
            return $this->success($result['data']);
        }else{
            return $this->fail($result['message']);
        }
    }
    public $successStatus = 200;
    public function add(Request $req)
    {
    	$result = array();
    	try{
    		if(!is_numeric($req->btl_delievered) || $req->btl_delievered ==''){
    			throw new \Exception('Invalid data in Bottle Delivered field');
    		}
    		elseif(!is_numeric($req->empty_btl_returned) || $req->empty_btl_returned ==''){
    				throw new \Exception('Invalid data in Empty Returned field');
    		}
			elseif(!is_numeric($req->balance_btl)  || $req->balance_btl ==''){
    				throw new \Exception('Invalid data in Balance Bottles field');
    		}
    		elseif(!is_numeric($req->amount_paid ) || $req->amount_paid ==''){
    				throw new \Exception('Invalid data in Amount field');
    		}
            elseif(!is_numeric($req->customer_id ) || $req->customer_id ==''){
                    throw new \Exception('Invalid data in Customer ID field');
            }
            elseif($req->delivery_date ==''){
                    throw new \Exception('Invalid data in Delivery Date field');
            }

        $sdate = date("Y") . '-01-' . date("m");
        $edate = date("Y") . '-'.date("t").'-' . date("m");

    	$supply = new Supply;
    	$supply->customer_id = $req->customer_id;
    	$supply->btl_delievered = $req->btl_delievered;
    	$supply->emtry_btl_returned = $req->empty_btl_returned;
    	$supply->balance_btl = $req->balance_btl;
    	$supply->amount_advance = $req->amount_advance ;
    	$supply->amount_paid = $req->amount_paid ;
    	$supply->amount_balance = $req->amount_balance ;
        $timestamp = strtotime($req->delivery_date);             
            $dd = date("Y-m-d", $timestamp);
    	$supply->delivery_date = $dd ;    	
    	$supply->remarks_id = $req->remarks_id ;
    	$supply->is_balance = $req->is_balance ;
    	//$supply->is_billed = $req->is_billed ;
    	//$supply->billed_date = $req->billed_date ;
    	$supply->active = 1 ;
    	$supply->date_created = Carbon::now()->toDateTimeString(); ;
    	$supply->date_updated = Carbon::now()->toDateTimeString();;
		$supply->save();
    	$result['supply'] = Supply::getSupplyByDate($sdate,$edate, $req->customer_id);//$supply->toArray();
        $result['status'] = true;
 
        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
        
        if($result['status']){
            return response()->json($result, $this->successStatus); 
        }else{
            return $this->fail($result['message']);
        }
    }


    public function update(Request $req)
    {
    	$result = array();
    	try{
    		if(!is_numeric($req->mobile) || $req->mobile ==''){
    			throw new \Exception('Invalid data in Mobile Number field');
    		}
    		elseif($req->alt_mob !=''){
    			if(!is_numeric($req->alt_mob))
    				throw new \Exception('Invalid data in Alternate Mobile Number field');
    		}
			


    	$supply = Customer::find(Auth::user()->id);
     	$supply->first_name = $req->first_name;
    	$supply->last_name = $req->last_name;
    	$supply->nic = $req->nic;
    	$supply->email = $req->email;
    	$supply->mobile = $req->mobile;
    	$supply->alt_mob = $req->alt_mob ;
    	$supply->address = $req->address ;
    	$supply->landmark = $req->landmark ;
    	$supply->date_update = Carbon::now()->toDateTimeString();;
		$supply->save();
    	$result['supply'] = $supply->toArray();
        $result['status'] = true;
        $result['_token'] = $req->bearerToken();
 
        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
        
        if($result['status']){
            return $this->success($result);
        }else{
            return $this->fail($result['message']);
        }
    }
    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(),[
    		'name'=>'required',
    		'email' =>'required|email',
    		'password' => 'required',
    		'c_password' => 'required|same::password',

    	]);
		
		$input = $request->all();   
		$input['password'] = bcrypt($input['password']); 	
		$user = User::create($input);
		$success['name'] = $user->name;
		$success['token'] = $user->createToken('MyApp')->accessToken;
    	return response()->json(['success'=>$success], $this->successStatus);
    }
	protected function credentials()
    {

		if(is_numeric(request('email'))){
 			return ['mobile'=>request('email'),'password'=>request('password')];
		}
		elseif (is_numeric(request('email'))) {
				return ['alt_mob' => request('email'), 'password'=>request('password')];
		}
		elseif (filter_var(request('email'), FILTER_VALIDATE_EMAIL)) {
				return ['email' => request('email'), 'password'=>request('password')];
		}
		return ['code' => request('email'), 'password'=>request('password')];
    }
    public function login(Request $request)
    {
    	//print_r(request('email'));
    	if(Auth::attempt($this->credentials()))
    	{
    		$user = Auth::user();

    		$success['token'] = $user->createToken('MyApp')->accessToken;
    		return response()->json(['success'=>$success,'user'=>$user], $this->successStatus);
    	}
    	else
    	{
    		return response()->json(['error'=>'Unauthorized'],401);
    	}
    }

    public function getUserDetails(Request $request)
    {
  	
    	return response()->json(['user'=>Auth::user()], $this->successStatus); 
    }
    public function getSupplyRecords(Request $request)
    {


        $result = array();
        try{
            $timestamp = strtotime($request->start_date);             
            $sdate = date("Y-m-d", $timestamp);
            $timestamp = strtotime($request->end_date);             
            $edate = date("Y-m-d", $timestamp); 

        $supply = Supply::getSupplyByDate($sdate,$edate, $request->uid );


        $result['supply'] = $supply;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
        
        
            // print_r($supply);
        return response()->json($result, $this->successStatus); 
    }
    public function getSummary()
    {
  		$sDate = '2020-01-04';
  		$sDate = '2020-30-04';



    	return response()->json(['user'=>Auth::user()], $this->successStatus); 
    }

}
