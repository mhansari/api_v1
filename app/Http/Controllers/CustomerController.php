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
use App\Payments;
use DB;
use Concerns\InteractsWithInput;
use Illuminate\Support\Facades\Hash;
class CustomerController extends ApiController
{
    public function searchableCustomers(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = DB::table("customer")
                    ->select("id","first_name","last_name","address","mobile")
                    ->where('first_name','LIKE',"%$search%")
                    ->orWhere('last_name','LIKE',"%$search%")
                    ->orWhere('address','LIKE',"%$search%")
                    ->orWhere('mobile','LIKE',"%$search%")
                    ->orWhere('alt_mob','LIKE',"%$search%")
                    ->get();
        }


        return response()->json($data);
    }
    public function list()
    {
    	$result = array();
    	try{
    	//$customer = new Customer();
    	$res = Customer::all();
    	$result['users'] = $res->toArray();
        $result['status'] = true;
 
        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
        // print_r($supply);
        return response()->json($result, $this->successStatus); 
        /*if($result['status']){
            return $this->success($result['data']);
        }else{
            return $this->fail($result['message']);
        }*/
    }
    public function search(Request $req)
    {
        $_sql = "select c.* from customer c inner join brand b on b.id = c.brand_id where 1 = 1 ";
    try{
        $name ='';
        $mobile = '';
        $code = '';
        $symbol = '';
        $address = '';
        $landmark = '';
        $rate = '';
        $deposit = '';
        $mon = '';
        $tue = '';
        $wed ='';
        $thu = '';
        $fri ='';
        $sat = '';
        $sun = '';
        $bm = '';
        $brand = '';
        $status = '';

        if($req->name !=''){
            $name = ' and (first_name like ("%' . $req->name . '%") or last_name like ("%' . $req->name . '%"))';
        }
        if($req->mobile !=''){
            $mobile = ' and (mobile like ("%' . $req->mobile . '%") or alt_mob like ("%' . $req->mobile . '%"))';
        }
        if($req->code !=''){
            $code = ' and code like ("%' . $req->code  . '%")';
        }
        if($req->symbol !=''){
            $symbol = ' and symbol like ("%' . $req->symbol . '%")';
        }
        if($req->address !=''){
            $address = ' and address like ("%' . $req->address . '%")';
        }
        if($req->landmark !=''){
            $landmark = ' and landmark like ("%' . $req->landmark . '%")';
        }
        if($req->rate !=''){
            $rate = ' and rate =' . $req->rate;
        }
        if($req->deposit){
            $deposit = ' and deposit > 0';
        }
        if($req->mon){
            $mon = ' and delivery_days like ("%Mon%")';
        }
        if($req->tue){
            $tue = ' and delivery_days like ("%Tue%")';
        }
        if($req->wed){
            $wed = ' and delivery_days like ("%Wed%")';
        }
        if($req->thu){
            $thu = ' and delivery_days like ("%Thu%")';
        }
        if($req->fri){
            $fri =' and delivery_days like ("%Fri%")';
        }
        if($req->sat){
            $sat = ' and delivery_days like ("%Sat%")';
        }
        if($req->sun){
            $sun = ' and delivery_days like ("%Sun%")';
        }
        if($req->bm !='-1'){
            $bm = ' and billing=' . ($req->bm?'1':'0') ;
        }
        if($req->brand !='-1'){
            $brand = ' and brand_id =' . $req->brand;
        }
        if($req->status !='-1'){
            $status = ' and c.active=' . ($req->status?'1':'0') ;
        }
        $_sql .= $name . $mobile . $code . $symbol . $address . $status. $bm . $mon .  $tue . $wed . $thu . $fri . $sat . $sun . $landmark . $rate . $deposit  . $brand  ;
 
        $_sql .= ' order by first_name, last_name asc';



        $query = DB::select($_sql);
        
        $result['users'] = $query;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
         return response()->json($result, $this->successStatus); 
    }

    public function searchForCombo(Request $req)
    {
        $_sql = "select c.* from customer c inner join brand b on b.id = c.brand_id where 1 = 1 ";
    try{

        $name ='';
        $mobile = '';
        $code = '';
       
        $address = '';
       
        $day = '';

        $brand = '';


        if($req->dd !='0'){
            $_sql .= ' and delivery_days like ("%'. $req->dd.'%")' ;
        }

        if($req->brand_id ==''){
            $_sql .= ' and brand_id = brand_id';
        }
        else if($req->brand_id !='0'){
            $_sql .= ' and brand_id = '. $req->brand_id ;
        }
        else
        {
            $_sql .= ' and brand_id = brand_id' ;
        }

        $_sql .= ' and ((first_name like ("%' . $req->q . '%") or last_name like ("%' . $req->q . '%")) or (mobile like ("%' . $req->q . '%") or alt_mob like ("%' . $req->q . '%")) or code like ("%' . $req->q  . '%") or address like ("%' . $req->q . '%")) ';

 
        $_sql .= ' order by first_name, last_name asc';



        $query = DB::select($_sql);
        
        $result['users'] = $query;
        $result['status'] = true;

        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
         return response()->json($result, $this->successStatus); 
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
    	$customer->active = $req->active ;
        $customer->random_text = $req->random_text;
        $customer->password = Hash::make($req->random_text );
        $customer->brand_id = $req->brand_id ;
        $customer->lat = $req->lat ;
        $customer->lng = $req->lng ;


    	$customer->date_create = Carbon::now()->toDateTimeString(); ;
    	$customer->date_update = Carbon::now()->toDateTimeString();;
		$customer->save();
    	$result['data'] = $customer->toArray();
        $result['status'] = true;
 
        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $req->deposit. $e->getMessage();
            $result['stack'] = 'Operation failed due to '. $e;
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
    public function getBillsByUser(Request $request)
    {
        $bills = Bills::getBillsByUser(Auth::user()->id);
        return response()->json(['bills'=>$bills], $this->successStatus); 
    }

    public function getPaymentsByBill(Request $request)
    {
       $payments = Payments::getPaymentsByBill(request('bill_id'));
        return response()->json(['payments'=>$payments], $this->successStatus); 
    }

    public function getUserDetails(Request $request)
    {
  	
    	return response()->json(['user'=>Auth::user()], $this->successStatus); 
    }

    public function getUserDetailsById(Request $request)
    {
        $user = User::getUserDetailById(request('id'));
        return response()->json(['user'=>$user], $this->successStatus); 
    }
public function updateCustomer(Request $req)
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
            


        $customer = Customer::find($req->id);
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
    public function getSummary()
    {
  	         

             $result = array();
        try{
        //$customer = new Customer();
        $supply = Supply::getSupplyByDate('2020-04-01','2021-04-30', Auth::user()->id );
      //  $archive = Supply::getSupplyByYear(Auth::user()->id );
        $bills = Bills::getBillsByYear(Auth::user()->id );
        $payments = Payments::getPaymentsByYear(Auth::user()->id );

        $result['supply'] = $supply;
      //  $result['archive'] = $archive;
        $result['bills'] = $bills;
        $result['payments'] = $payments;
         $result['user'] = Auth::user();
       // $result['status'] = true;
 
        }catch(\Exception $e){
            $result['status'] = false;
            $result['message'] = 'Operation failed due to '. $e->getMessage();
        }
        
        
            // print_r($supply);
    	return response()->json($result, $this->successStatus); 
    }

}
