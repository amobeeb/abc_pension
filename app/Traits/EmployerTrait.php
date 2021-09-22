<?php
namespace App\Traits;

trait EmployerTrait
{
    public function generateOTP(){
        $otp = random_int(000000, 999999);
        return $otp;
    }

    public function generateAccountNumber()
    {
        $generate_account_no = 'EMP'.random_int(00000000000, 99999999999);
        return $generate_account_no;
    }
}