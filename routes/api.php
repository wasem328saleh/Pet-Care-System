<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Traits\GeneralTrait;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//(Start)------------------------- ALL -----------------

Route::post('login',[\App\Http\Controllers\Controller::class,'login']);
Route::post('logout',[\App\Http\Controllers\Controller::class,'logout'])->middleware('auth.guard:user-api');
Route::get('Get_All_Doctors',[\App\Http\Controllers\Controller::class,'Get_All_Doctors']);
Route::get('Get_All_Clinics',[\App\Http\Controllers\Controller::class,'Get_All_Clinics']);
Route::get('Get_All_Illnesses',[\App\Http\Controllers\Controller::class,'Get_All_Illnesses']);
Route::get('Get_All_Animals',[\App\Http\Controllers\Controller::class,'Get_All_Animals']);
Route::get('Get_All_Products',[\App\Http\Controllers\Controller::class,'Get_All_Products']);
Route::get('Get_All_Animals_Adoption',[\App\Http\Controllers\Controller::class,'Get_All_Animals_Adoption']);
Route::get('Get_All_Trainings',[\App\Http\Controllers\Controller::class,'Get_All_Trainings']);


Route::post('edit_Product',[\App\Http\Controllers\Controller::class,'edit_Product']);
Route::post('update_Product',[\App\Http\Controllers\Controller::class,'update_Product']);
Route::post('delete_Product',[\App\Http\Controllers\Controller::class,'delete_Product']);

Route::post('edit_Animal_Adoption',[\App\Http\Controllers\Controller::class,'edit_Animal_Adoption']);
Route::post('update_Animal_Adoption',[\App\Http\Controllers\Controller::class,'update_Animal_Adoption']);
Route::post('delete_Animal_Adoption',[\App\Http\Controllers\Controller::class,'delete_Animal_Adoption']);

Route::post('edit_Training',[\App\Http\Controllers\Controller::class,'edit_Training']);
Route::post('update_Training',[\App\Http\Controllers\Controller::class,'update_Training']);
Route::post('delete_Training',[\App\Http\Controllers\Controller::class,'delete_Training']);

//(End)-----------------------------------------------



//(Start)------------------------- ADMIN -----------------

Route::prefix('admin')->group(function (){
    Route::get('get_All_Users',[\App\Http\Controllers\AdminController::class,'getAllUsers']);
    Route::post('Add_Admin',[\App\Http\Controllers\AdminController::class,'Add_Admin']);

    Route::post('Store_Doctor',[\App\Http\Controllers\AdminController::class,'Store_Doctor']);
    Route::post('edit_Doctor',[\App\Http\Controllers\AdminController::class,'edit_Doctor']);
    Route::post('update_Doctor',[\App\Http\Controllers\AdminController::class,'update_Doctor']);
    Route::post('delete_Doctor',[\App\Http\Controllers\AdminController::class,'delete_Doctor']);

    Route::post('Store_Clinic',[\App\Http\Controllers\AdminController::class,'Store_Clinic']);
    Route::post('edit_Clinic',[\App\Http\Controllers\AdminController::class,'edit_Clinic']);
    Route::post('update_Clinic',[\App\Http\Controllers\AdminController::class,'update_Clinic']);
    Route::post('delete_Clinic',[\App\Http\Controllers\AdminController::class,'delete_Clinic']);

    Route::post('Store_Illness',[\App\Http\Controllers\AdminController::class,'Store_Illness']);
    Route::post('edit_Illness',[\App\Http\Controllers\AdminController::class,'edit_Illness']);
    Route::post('update_Illness',[\App\Http\Controllers\AdminController::class,'update_Illness']);
    Route::post('delete_Illness',[\App\Http\Controllers\AdminController::class,'delete_Illness']);

    Route::post('Store_Animal',[\App\Http\Controllers\AdminController::class,'Store_Animal']);
    Route::post('edit_Animal',[\App\Http\Controllers\AdminController::class,'edit_Animal']);
    Route::post('update_Animal',[\App\Http\Controllers\AdminController::class,'update_Animal']);
    Route::post('delete_Animal',[\App\Http\Controllers\AdminController::class,'delete_Animal']);

    Route::post('Store_Product',[\App\Http\Controllers\AdminController::class,'Store_Product'])->middleware('auth.guard:user-api');

    Route::post('Store_Animal_Adoption',[\App\Http\Controllers\AdminController::class,'Store_Animal_Adoption'])->middleware('auth.guard:user-api');


    Route::post('Store_Training',[\App\Http\Controllers\AdminController::class,'Store_Training'])->middleware('auth.guard:user-api');


    Route::get('Get_All_Orders_Product',[\App\Http\Controllers\AdminController::class,'Get_All_Orders_Product']);
    Route::get('Get_All_Orders_Animal_Adoption',[\App\Http\Controllers\AdminController::class,'Get_All_Orders_Animal_Adoption']);
    Route::get('Get_All_Orders_Training',[\App\Http\Controllers\AdminController::class,'Get_All_Orders_Training']);
    Route::post('approved_Order',[\App\Http\Controllers\AdminController::class,'approved_Order']);
    Route::post('rejected_Order',[\App\Http\Controllers\AdminController::class,'rejected_Order']);

});

//(End)-----------------------------------------------




//(Start)------------------------- USER -----------------

Route::prefix('user')->group(function (){
    Route::post('register',[\App\Http\Controllers\UserController::class,'register']);
    Route::post('Store_Product',[\App\Http\Controllers\UserController::class,'Store_Product'])->middleware('auth.guard:user-api');
    Route::post('Store_Animal_Adoption',[\App\Http\Controllers\UserController::class,'Store_Animal_Adoption'])->middleware('auth.guard:user-api');
    Route::post('Store_Training',[\App\Http\Controllers\UserController::class,'Store_Training'])->middleware('auth.guard:user-api');
    Route::get('Get_All_Symptoms',[\App\Http\Controllers\UserController::class,'Get_All_Symptoms']);
    Route::post('Get_Illness_By_Symptoms',[\App\Http\Controllers\UserController::class,'Get_Illness_By_Symptoms']);

    Route::get('Get_My_Product',[\App\Http\Controllers\UserController::class,'Get_My_Product'])->middleware('auth.guard:user-api');
    Route::get('Get_My_Animal_Adoption',[\App\Http\Controllers\UserController::class,'Get_My_Animal_Adoption'])->middleware('auth.guard:user-api');
    Route::get('Get_My_Training',[\App\Http\Controllers\UserController::class,'Get_My_Training'])->middleware('auth.guard:user-api');
});

//(End)-----------------------------------------------



Route::post('test',[\App\Http\Controllers\Controller::class,'testD']);
