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
class DeliveryDaysController extends ApiController
{
    

    public function list()
    {
    	$result = array();
        $res = array('Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday');
    	try{

    	$result['delivery_days'] = $res;
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

    public function getDayByKey(Request $request)
    {
        $result = array();
        $res = array('Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday');
        try{

        $result['delivery_day'] = $res[$request->key];
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
    
}
