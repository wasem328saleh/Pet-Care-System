<?php

namespace App\Http\Controllers;

use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $User;

    public function __construct(UserRepositoryInterface $User)
    {
        $this->User=$User;
    }

    public function register(Request $request)
    {
        return $this->User->register($request);
    }
    public function Store_Product(Request $request)
    {
        return $this->User->Store_Product($request);
    }

    public function Store_Animal_Adoption(Request $request)
    {
        return $this->User->Store_Animal_Adoption($request);
    }

    public function Store_Training(Request $request)
    {
        return $this->User->Store_Training($request);
    }

    public function Get_All_Symptoms()
    {
        return $this->User->Get_All_Symptoms();
    }

    public function Get_Illness_By_Symptoms(Request $request)
    {
        return $this->User->Get_Illness_By_Symptoms($request);
    }

    public function Get_My_Product()
    {
        return $this->User->Get_My_Product();
    }
    public function Get_My_Animal_Adoption()
    {
        return $this->User->Get_My_Animal_Adoption();
    }
    public function Get_My_Training()
    {
        return $this->User->Get_My_Training();
    }

}
