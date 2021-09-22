<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Employee extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];
    public function employee_details()
    {
        return $this->hasOne(EmployerDetails::class);
    }

    public function next_of_kin()
    {
        return $this->hasOne(NextofKin::class);
    }

    public function username()
    {
        return 'account_number';
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }
}
