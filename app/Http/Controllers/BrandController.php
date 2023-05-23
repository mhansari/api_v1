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
use App\Brand;
use Concerns\InteractsWithInput;
class BrandController extends ApiController
{
    

    public function list()
    {
    	$result = array();
    	try{
    	$res = Brand::all();
    	$result['brands'] = $res->toArray();
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
public function getActiveBrands()
    {
        $result = array();
        try{
        $res = Brand::where('active', '=', '1')->get();



        $result['brands'] = $res->toArray();
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
    public function getByBrandId(Request $request)
    {
        $result = array();
        try{
        $res = Brand::find(request('id'));
        $result['brand'] = $res;
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
        	$brand = new Brand;
        	$brand->name = $req->name;
        	$brand->active =$req->active ;
        	$brand->date_create = Carbon::now()->toDateTimeString(); ;
        	$brand->date_update = Carbon::now()->toDateTimeString();;
    		$brand->save();
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
    		
    	$brand = Brand::find($req->id);
     	$brand->name = $req->name;
        $brand->active =$req->active ;
    	$brand->date_update = Carbon::now()->toDateTimeString();;
		$brand->save();
    	$result['data'] = $brand->toArray();
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
