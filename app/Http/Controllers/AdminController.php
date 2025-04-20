<?php

namespace App\Http\Controllers;

use App\Repository\AdminRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    protected $Admin;

    public function __construct(AdminRepositoryInterface $Admin)
    {
        $this->Admin=$Admin;
    }

    public function getAllUsers()
    {
        return $this->Admin->getAllUsers();
    }

    public function Add_Admin(Request $request)
    {
        return $this->Admin->Add_Admin($request);
    }

    public function Store_Doctor(Request $request)
    {
        return $this->Admin->Store_Doctor($request);
    }

    public function Store_Clinic(Request $request)
    {
        return $this->Admin->Store_Clinic($request);
    }

    public function Store_Illness(Request $request)
    {
        return $this->Admin->Store_Illness($request);
    }

    public function Store_Animal(Request $request)
    {
        return $this->Admin->Store_Animal($request);
    }

    public function Store_Product(Request $request)
    {
        return $this->Admin->Store_Product($request);
    }

    public function Store_Animal_Adoption(Request $request)
    {
        return $this->Admin->Store_Animal_Adoption($request);
    }

    public function Store_Training(Request $request)
    {
        return $this->Admin->Store_Training($request);
    }

    public function Get_All_Orders_Product()
    {
        return $this->Admin->Get_All_Orders_Product();
    }

    public function Get_All_Orders_Animal_Adoption()
    {
        return $this->Admin->Get_All_Orders_Animal_Adoption();
    }

    public function Get_All_Orders_Training()
    {
        return $this->Admin->Get_All_Orders_Training();
    }

    public function approved_Order(Request $request)
    {
        return $this->Admin->approved_Order($request);
    }

    public function rejected_Order(Request $request)
    {
        return $this->Admin->rejected_Order($request);
    }



    public function edit_Doctor(Request $request)
    {
        return $this->Admin->edit_Doctor($request);
    }
    public function update_Doctor(Request $request)
    {
        return $this->Admin->update_Doctor($request);

    }
    public function delete_Doctor(Request $request)
    {
        return $this->Admin->delete_Doctor($request);

    }

    public function edit_Clinic(Request $request)
    {
        return $this->Admin->edit_Clinic($request);

    }
    public function update_Clinic(Request $request)
    {
        return $this->Admin->update_Clinic($request);

    }
    public function delete_Clinic(Request $request)
    {
        return $this->Admin->delete_Clinic($request);

    }

    public function edit_Illness(Request $request)
    {
        return $this->Admin->edit_Illness($request);
    }

    public function update_Illness(Request $request)
    {
        return $this->Admin->update_Illness($request);
    }

    public function delete_Illness(Request $request)
    {
        return $this->Admin->delete_Illness($request);
    }

    public function edit_Animal(Request $request)
    {
        return $this->Admin->edit_Animal($request);

    }
    public function update_Animal(Request $request)
    {
        return $this->Admin->update_Animal($request);

    }
    public function delete_Animal(Request $request)
    {
        return $this->Admin->delete_Animal($request);

    }



}
