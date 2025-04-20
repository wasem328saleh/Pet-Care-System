<?php

namespace App\Repository;

interface SystemRepositoryInterface
{
    public function login($request);
    public function logout($request);

    public function Get_All_Doctors();
    public function Get_All_Clinics();
    public function Get_All_Illnesses();
    public function Get_All_Animals();
    public function Get_All_Products();
    public function Get_All_Animals_Adoption();
    public function Get_All_Trainings();

    public function edit_Product($request);
    public function update_Product($request);
    public function delete_Product($request);

    public function edit_Animal_Adoption($request);
    public function update_Animal_Adoption($request);
    public function delete_Animal_Adoption($request);

    public function edit_Training($request);
    public function update_Training($request);
    public function delete_Training($request);
}
