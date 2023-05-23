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
use App\Employees;
use Concerns\InteractsWithInput;
class EmployeesController extends ApiController
{
    

    public function list()
    {
    	$result = array();
    	try{
    	$res = Employees::all();
    	$result['employees'] = $res->toArray();
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
public function getActiveRoutes()
    {
        $result = array();
        try{
        $res = Employees::where('active', '=', '1')->get();



        $result['employees'] = $res->toArray();
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
    public function getByRouteId(Request $request)
    {
        $result = array();
        try{
        $res = Employees::find(request('id'));
        $result['employee'] = $res;
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
        	$emp = new Employees;
        	$emp->name = $req->name;
        	$emp->active =$req->active ;
        	$emp->date_create = Carbon::now()->toDateTimeString(); ;
        	$emp->date_update = Carbon::now()->toDateTimeString();;
    		$emp->save();
        	$result['data'] = $emp->toArray();
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
    		
    	$emp = Employees::find($req->id);
     	$emp->name = $req->name;
        $emp->active =$req->active ;
    	$emp->date_update = Carbon::now()->toDateTimeString();;
		$emp->save();
    	$result['data'] = $emp->toArray();
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
    
    
}
