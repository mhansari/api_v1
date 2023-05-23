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
use App\Areas;
use Concerns\InteractsWithInput;
class AreasController extends ApiController
{
    

    public function list()
    {
    	$result = array();
    	try{
    	$res = Areas::all();
    	$result['areas'] = $res->toArray();
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
public function getActiveAreas()
    {
        $result = array();
        try{
        $res = Areas::where('active', '=', '1')->get();



        $result['areas'] = $res->toArray();
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
        $res = Areas::find(request('id'));
        $result['area'] = $res;
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
        	$area = new Areas;
        	$area->name = $req->name;
        	$area->active =$req->active ;
        	$area->date_create = Carbon::now()->toDateTimeString(); ;
        	$area->date_update = Carbon::now()->toDateTimeString();;
    		$area->save();
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
    		
    	$area = Areas::find($req->id);
     	$area->name = $req->name;
        $area->active =$req->active ;
    	$area->date_update = Carbon::now()->toDateTimeString();;
		$area->save();
    	$result['data'] = $area->toArray();
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
