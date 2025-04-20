<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Training;
use App\Repository\SystemRepositoryInterface;
use App\Traits\GeneralTrait;
use Google_Client;
use Google_Service_YouTube;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use GeneralTrait;

    protected $system;

    public function __construct(SystemRepositoryInterface $system)
    {
        $this->system=$system;
    }

    public function login(Request $request)
    {
        return $this->system->login($request);
    }

    public function logout(Request $request)
    {
        return $this->system->logout($request);
    }

    public function Get_All_Doctors()
    {
        return $this->system->Get_All_Doctors();
    }
    public function Get_All_Clinics()
    {
        return $this->system->Get_All_Clinics();
    }
    public function Get_All_Illnesses()
    {
        return $this->system->Get_All_Illnesses();
    }
    public function Get_All_Animals()
    {
        return $this->system->Get_All_Animals();
    }
    public function Get_All_Products()
    {
        return $this->system->Get_All_Products();
    }
    public function Get_All_Animals_Adoption()
    {
        return $this->system->Get_All_Animals_Adoption();
    }
    public function Get_All_Trainings()
    {
        return $this->system->Get_All_Trainings();
    }
    public function edit_Product(Request $request)
    {
        return $this->system->edit_Product($request);

    }
    public function update_Product(Request $request)
    {
        return $this->system->update_Product($request);

    }
    public function delete_Product(Request $request)
    {
        return $this->system->delete_Product($request);

    }

    public function edit_Animal_Adoption(Request $request)
    {
        return $this->system->edit_Animal_Adoption($request);

    }
    public function update_Animal_Adoption(Request $request)
    {
        return $this->system->update_Animal_Adoption($request);

    }
    public function delete_Animal_Adoption(Request $request)
    {
        return $this->system->delete_Animal_Adoption($request);

    }

    public function edit_Training(Request $request)
    {
        return $this->system->edit_Training($request);

    }
    public function update_Training(Request $request)
    {
        return $this->system->update_Training($request);

    }
    public function delete_Training(Request $request)
    {
        return $this->system->delete_Training($request);

    }



    public function testD(Request $request){


        $training=Training::find(request()->training_id);
        return $this->get_Type_Media($training);



    }
}
