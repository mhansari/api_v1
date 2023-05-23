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
use App\Remarks;
use Concerns\InteractsWithInput;
class RemarksController extends ApiController
{
    

    public function list()
    {
    	$result = array();
    	try{
    	$res = Remarks::all();
    	$result['remarks'] = $res->toArray();
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
public function getActiveRemarks()
    {
        $result = array();
        try{
        $res = Remarks::where('active', '=', '1')->get();



        $result['remarks'] = $res->toArray();
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
    public function getByRemarksId(Request $request)
    {
        $result = array();
        try{
        $res = Remarks::find(request('id'));
        $result['remarks'] = $res;
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
        	$remarks = new Remarks;
        	$remarks->name = $req->name;
        	$remarks->active =$req->active ;
        	$remarks->date_create = Carbon::now()->toDateTimeString(); ;
        	$remarks->date_update = Carbon::now()->toDateTimeString();;
    		$remarks->save();
        	$result['data'] = $remarks->toArray();
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
    		
    	$remarks = Remarks::find($req->id);
     	$remarks->name = $req->name;
        $remarks->active =$req->active ;
    	$remarks->date_update = Carbon::now()->toDateTimeString();;
		$remarks->save();
    	$result['data'] = $remarks->toArray();
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
