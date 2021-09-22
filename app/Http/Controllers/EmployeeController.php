<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use App\Http\Requests\EmployeeRequest;
class EmployeeController extends Controller
{
    public function store(EmployeeRequest $request)
    {
        // generate otp
        $otp = random_int(000000, 999999);
        // $validate = $request->validate();
        $employee = Employee::create([ 
            'surname'=>$request->surname,
            'firstname'=>$request->firstname,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'address'=>$request->address,
            'password'=>Hash::make($request->password),
            'OTP'=>$otp
        ]);
        
        if($employee){
            // $this->sendMessage('ABC Pension OTP:'.$otp, '+'.$request->mobile);
            return response()->json([
                'message'=>'success',
                'token' => $employee->createToken('tokens')->plainTextToken
            ]);
        }
    }

    public function check_otp(Request $request)
    {
        $generate_account_no = 'EMP'.random_int(00000000000, 99999999999);
        //email, user otp
        $employee = Employee::whereEmail($request->email)->first();
        
        if($employee->otp == $request->otp ){
            $employee->isActivate = 1;
            $employee->account_number = $generate_account_no;
            $employee->save();
            return response()->json([
                'message'=>'Success, Account Activated. Account Number = '.$generate_account_no
            ]);
        }else{
            return response()->json([
                'message'=>'Invalid OTP'
            ], 400);
        }
    }


    public function authenticate(Request $request)
    {
        if(Auth::guard('employees')->attempt(['account_number'=>$request->pin, 'password'=>$request->password])){
           
            return response()->json([
                'message'=>'Login Successful',
                'token' => Auth::guard('employees')->user()->createToken('tokens')->plainTextToken, 
            ]);
        }else{
            return response()->json([
                'message'=>'Login Not Successful', 
            ]);
        }
        
    }


    public function signout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }

    private function sendMessage($message, $recipients)
    {
    $account_sid = getenv("TWILIO_SID");
    $auth_token = getenv("TWILIO_AUTH_TOKEN");
    $twilio_number = getenv("TWILIO_NUMBER");
    $client = new Client($account_sid, $auth_token);
    $client->messages->create($recipients, 
            ['from' => $twilio_number, 'body' => $message] );
    }

    public function allEmployee()
    {
        $employee = Employee::all();
        return response()->json([
            'message'=>$employee, 
        ]);
    }

    public function getEmployee($account_number)
{
    $employee = Employee::whereAccountNumber($account_number)->first();
    return response()->json([
        'message'=>$employee, 
    ]);
}
}
