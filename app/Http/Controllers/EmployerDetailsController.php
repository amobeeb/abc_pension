<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployerDetails;
use App\Http\Requests\EmployeeDetailsRequest;
use Auth;
class EmployerDetailsController extends Controller
{
    public function update(EmployeeDetailsRequest $request)
    {    
       
        $details = EmployerDetails::create([
            'employee_id'=> auth()->user()->id,
            'employer_name'=>$request->employer_name,
            'employee_code'=>$request->employee_code,
        ]);
        if($details){
            return response()->json([
                'message'=>'Employer Detail saved'
            ]);
        }else{
            return response()->json([
                'message'=>'not successful'
            ], 400);
        }
    }
}
