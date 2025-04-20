<?php

namespace App\Repository;

interface UserRepositoryInterface
{

    public function register($request);
    public function Store_Product($request);
    public function Store_Animal_Adoption($request);
    public function Store_Training($request);

    public function Get_All_Symptoms();
    public function Get_Illness_By_Symptoms($request);

    public function Get_My_Product();
    public function Get_My_Animal_Adoption();
    public function Get_My_Training();






}
