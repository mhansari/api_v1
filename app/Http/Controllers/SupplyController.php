<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Customer;
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
    	//$customer = new Customer();
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
    		if(!is_numeric($req->mobile) || $req->mobile ==''){
    			throw new \Exception('Invalid data in Mobile Number field');
    		}
    		elseif($req->alt_mob !=''){
    			if(!is_numeric($req->alt_mob))
    				throw new \Exception('Invalid data in Alternate Mobile Number field');
    		}
			elseif(!is_numeric($req->rate)  || $req->rate ==''){
    				throw new \Exception('Invalid data in Rate field');
    		}
    		elseif(!is_numeric($req->deposit ) || $req->deposit ==''){
    				throw new \Exception('Invalid data in Deposit field');
    		}


    	$customer = new Customer;
    	$customer->code = $customer->genCode(); 
    	$customer->first_name = $req->first_name;
    	$customer->last_name = $req->last_name;
    	$customer->nic = $req->nic;
    	$customer->mobile = $req->mobile;
    	$customer->alt_mob = $req->alt_mob ;
    	$customer->address = $req->address ;
    	$customer->landmark = $req->landmark ;
    	$customer->symbol = $req->symbol ;    	
    	$customer->rate = $req->rate ;
    	$customer->deposit = $req->deposit ;
    	$customer->required_bottles = $req->required_bottles ;
    	$customer->billing = $req->billing ;
    	$customer->delivery_days = $req->delivery_days ;
    	$customer->active = 1 ;
    	$customer->date_create = Carbon::now()->toDateTimeString(); ;
    	$customer->date_update = Carbon::now()->toDateTimeString();;
		$customer->save();
    	$result['data'] = $customer->toArray();
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
			


    	$customer = Customer::find(Auth::user()->id);
     	$customer->first_name = $req->first_name;
    	$customer->last_name = $req->last_name;
    	$customer->nic = $req->nic;
    	$customer->email = $req->email;
    	$customer->mobile = $req->mobile;
    	$customer->alt_mob = $req->alt_mob ;
    	$customer->address = $req->address ;
    	$customer->landmark = $req->landmark ;
    	$customer->date_update = Carbon::now()->toDateTimeString();;
		$customer->save();
    	$result['data'] = $customer->toArray();
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

    public function getSummary()
    {
  		$sDate = '2020-01-04';
  		$sDate = '2020-30-04';



    	return response()->json(['user'=>Auth::user()], $this->successStatus); 
    }

}
