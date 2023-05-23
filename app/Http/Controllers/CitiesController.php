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
use App\Cities;
use Concerns\InteractsWithInput;
class CitiesController extends ApiController
{
    

    public function list()
    {
    	$result = array();
    	try{
    	$res = Cities::all();
    	$result['cities'] = $res->toArray();
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
public function getActiveCities()
    {
        $result = array();
        try{
        $res = Cities::where('active', '=', '1')->get();



        $result['cities'] = $res->toArray();
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
    public function getByCityId(Request $request)
    {
        $result = array();
        try{
        $res = Cities::find(request('id'));
        $result['city'] = $res;
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
    public $successStatus = 200;
    public function add(Request $req)
    {
    	$result = array();
    	try{
    		if($req->name ==''){
    			throw new \Exception('Invalid data in Name field');
    		}
        	$cities = new Cities;
        	$cities->name = $req->name;
        	$cities->active =$req->active ;
        	$cities->date_create = Carbon::now()->toDateTimeString(); ;
        	$cities->date_update = Carbon::now()->toDateTimeString();;
    		$cities->save();
        	$result['data'] = $brand->toArray();
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


    public function update(Request $req)
    {
    	$result = array();
    	try{
    		if($req->name ==''){
    			throw new \Exception('Invalid data in Name field');
    		}
    		
    	$cities = Cities::find($req->id);
     	$cities->name = $req->name;
        $cities->active =$req->active ;
    	$cities->date_update = Carbon::now()->toDateTimeString();;
		$cities->save();
    	$result['data'] = $cities->toArray();
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
    
    public function getBillsByUser(Request $request)
    {
        $bills = Bills::getBillsByUser(Auth::user()->id);
        return response()->json(['bills'=>$bills], $this->successStatus); 
    }
}
