<?php
namespace App\Repository;

interface AdminRepositoryInterface
{
    public function getAllUsers();
    public function Add_Admin($request);
    public function Store_Doctor($request);
    public function Store_Clinic($request);
    public function Store_Illness($request);
    public function Store_Animal($request);
    public function Store_Product($request);
    public function Store_Animal_Adoption($request);
    public function Store_Training($request);

    public function edit_Doctor($request);
    public function update_Doctor($request);
    public function delete_Doctor($request);

    public function edit_Clinic($request);
    public function update_Clinic($request);
    public function delete_Clinic($request);

    public function edit_Illness($request);
    public function update_Illness($request);
    public function delete_Illness($request);

    public function edit_Animal($request);
    public function update_Animal($request);
    public function delete_Animal($request);






    public function Get_All_Orders_Product();
    public function Get_All_Orders_Animal_Adoption();
    public function Get_All_Orders_Training();

    public function approved_Order($request);

    public function rejected_Order($request);
}
