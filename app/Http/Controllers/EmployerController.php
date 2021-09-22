<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\EmployerRequest;
use Mail;
use App\Mail\SendOTPMail;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use App\Models\Employee;
use App\Models\Account;
use App\Traits\EmployerTrait;
use Auth;

class EmployerController extends Controller
{
     use EmployerTrait;

    public function store(EmployerRequest $request)
    { 
        // $validate = $request->validate();
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'office_address'=>$request->office_address,
            'password'=>Hash::make($request->password),
            'OTP'=>$this->generateOTP()
        ]);
        
        if($user){ 
            // send OTP
            $this->sendMessage('ABC Pension OTP:'.$this->generateOTP(), '+'.$request->mobile);
            return response()->json([
                'message'=>'success',
                'token' => $user->createToken('tokens')->plainTextToken
            ]);
        }
    }

    public function check_otp(Request $request)
    { 
        //email, user otp
        $user = User::whereEmail($request->email)->first();
        //  verify OTP
        if($user->OTP == $request->otp ){
            $user->isActive = 1;
            $user->account_number = $this->generateAccountNumber();
            $user->save();
            return response()->json([
                'message'=>'Success, Account Activated. Account Number = '.$this->generateAccountNumber()
            ]);
        }else{
            return response()->json([
                'message'=>'Invalid OTP'
            ], 400);
        }
    }

    public function authenticate(Request $request)
    {
        if(Auth::guard('employers')->attempt(['account_number'=>$request->pin, 'password'=>$request->password, 'isActive'=>1])){
            
            return response()->json([
                'message'=>'Login Successful',
                'token' => Auth::guard('employers')->user()->createToken('tokens')->plainTextToken, 
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
            'message' => 'Signed out'
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

public function allEmployer()
{
    $employer = User::all();
    return response()->json([
        'message'=>$employer, 
    ]);
}

public function makeTransaction(Request $request)
{
    $employee = Employee::whereAccountNumber($request->account_number)->first();
    $account = Account::whereEmployeeId($employee->id)->first();
    if(!$account){
        Account::create([
            'employee_id' => $employee->id,
            'amount'=>$request->amount
        ]);
    }else{
        $account->increment('amount', $request->amount );
    }
    return [
        'message' => 'Money Transfered',
        'receiver'=>$employee->account
    ];
}

public function getEmployer($account_number)
{
    $employer = User::whereAccountNumber($account_number)->first();
    return response()->json([
        'message'=>$employer, 
    ]);
}

 
}
