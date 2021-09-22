<?php

namespace App\Http\Controllers;
use App\Models\NextofKin;
use Illuminate\Http\Request;
use App\Http\Requests\NextofKinRequest;
use Auth;
class NextofKinController extends Controller
{
    public function update(NextofKinRequest $request)
    { 
        $kin = NextofKin::create([
            'employee_id'=>auth()->user()->id,
            'surname'=>$request->surname,
            'firstname'=>$request->firstname,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
        ]);
        if($kin){
            return response()->json([
                'message'=>'success'
            ]);
        }else{
            return response()->json([
                'message'=>'not success'
            ], 400);
        }
    }
}
