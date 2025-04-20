<?php
namespace App\Repository;

use App\Models\Adoption;
use App\Models\Animal;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Illness;
use App\Models\OrderAdoption;
use App\Models\OrderProduct;
use App\Models\OrderTraining;
use App\Models\Product;
use App\Models\Training;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AdminRepository implements AdminRepositoryInterface
{
use GeneralTrait;


    public function getAllUsers()
    {
        $users=User::where('is_admin','like',0)->get();
        return $this->returnData('Users',$users,'this is All users is not Admin');
    }

    public function Add_Admin($request)
    {
        try {
            $rules = [
                "user_id" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user_id=request()->user_id;
            $user=User::find($user_id);
            $user->update([
               'is_admin'=>true
            ]);

            return $this->returnSuccessMessage('Add Admin Successfully');


        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }




    public function Store_Doctor($request)
    {

        try {
            $rules = [
                "full_Name_doctor" => "required",
                "phone_number" => "required",
                "address" => "required",
                "description" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $full_Name_doctor=request()->full_Name_doctor;
            $phone_number=request()->phone_number;
            $address=request()->address;
            $description=request()->description;


            $doctor=Doctor::create([
                'full_Name_doctor'=>$full_Name_doctor,
                'phone_number'=>$phone_number,
                'address'=>$address,
                'description'=>$description,
            ]);
            return $this->returnSuccessMessage('Store Doctor Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Store_Clinic($request)
    {
        try {
            $rules = [
                "full_Name_clinic" => "required",
                "phone_number" => "required",
                "address" => "required",
                "available_doctors" => "required",
                "working_days" => "required",
                "description" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $full_Name_clinic=request()->full_Name_clinic;
            $phone_number=request()->phone_number;
            $address=request()->address;
            $available_doctors=request()->available_doctors;
            $working_days=request()->working_days;
            $description=request()->description;


            $clinic=Clinic::create([
                'full_Name_clinic'=>$full_Name_clinic,
                'phone_number'=>$phone_number,
                'address'=>$address,
                'available_doctors'=>$available_doctors,
                'working_days'=>$working_days,
                'description'=>$description,
            ]);
            return $this->returnSuccessMessage('Store Clinic Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Store_Illness($request)
    {
        try {
            $rules = [
                "name_illness" => "required",
                "symptoms" => "required",
                "treatment" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $name_illness=request()->name_illness;
            $symptoms=request()->symptoms;
            $treatment=request()->treatment;

            $illness=Illness::create([
                'name_illness'=>$name_illness,
                'symptoms'=>$symptoms,
                'treatment'=>$treatment
            ]);
            return $this->returnSuccessMessage('Store Illness Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Store_Animal($request)
    {
        try {
            $rules = [
                "name" => "required",
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                "description" => "required",
                "type" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $name=request()->name;
            $description=request()->description;
            $type=request()->type;
            //Upload Animal Image
            $Folde_Name='animals';
            $path = public_path('images/'.$Folde_Name.'/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $imageName = time() . '.' . $request->image->extension();
            $im=$request->image->move($path, $imageName);

            $image_url= URL::to('/')."/images/".$Folde_Name."/".$imageName;


           $animal=Animal::create([
               'name'=>$name,
               'image_url'=>$image_url,
               'description'=>$description,
               'type'=>$type
           ]);
            return $this->returnSuccessMessage('Store Animal Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Store_Product($request)
    {
        try {
            $rules = [
                "full_Name" => "required",
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                "price" => "required",
                "type" => "required",
                "conects_info" => "required",
                "description" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $full_Name=request()->full_Name;
            $price=request()->price;
            $type=request()->type;
            $conects_info=request()->conects_info;
            $description=request()->full_Name;

            //Upload Animal Image
            $Folde_Name='products';
            $path = public_path('images/'.$Folde_Name.'/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $imageName = time() . '.' . $request->image->extension();
            $im=$request->image->move($path, $imageName);

            $image_url= URL::to('/')."/images/".$Folde_Name."/".$imageName;

            $user_id=Auth::id();

            $order_product=OrderProduct::create([
                'user_id'=>$user_id,
                'is_active'=>true
            ]);
            $product=Product::create([
                'full_Name'=>$full_Name,
                'image_url'=>$image_url,
                'price'=>$price,
                'type'=>$type,
                'conects_info'=>$conects_info,
                'description'=>$description,
                'is_active'=>true,
                'order_id'=>$order_product->id
            ]);

            return $this->returnSuccessMessage('Store Product Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Store_Animal_Adoption($request)
    {
        try {
            $rules = [
                "type_animal" => "required",
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                "conects_info" => "required",
                "description" => "required",
                "Reason_for_adoption" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $type_animal=request()->type_animal;
            $conects_info=request()->conects_info;
            $description=request()->description;
            $Reason_for_adoption=request()->Reason_for_adoption;

            //Upload Animal Image
            $Folde_Name='animalsAdoption';
            $path = public_path('images/'.$Folde_Name.'/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $imageName = time() . '.' . $request->image->extension();
            $im=$request->image->move($path, $imageName);

            $image_url= URL::to('/')."/images/".$Folde_Name."/".$imageName;

            $user_id=Auth::id();

            $order_adoption=OrderAdoption::create([
                'user_id'=>$user_id,
                'is_active'=>true
            ]);

            $animal_adoption=Adoption::create([
                'type_animal'=>$type_animal,
                'image_url'=>$image_url,
                'conects_info'=>$conects_info,
                'description'=>$description,
                'Reason_for_adoption'=>$Reason_for_adoption,
                'is_active'=>true,
                'order_id'=>$order_adoption->id
            ]);

            return $this->returnSuccessMessage('Store Animal of Adoption Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function Store_Training($request)
    {

        try {


            $rules = [
                "text" => "required",
                "media" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $text=request()->text;
            $media=request()->media;

            switch ($media)
            {
                case "image":
                {
                    try {
                        $rules = [
                            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',

                        ];

                        $validator = Validator::make($request->all(), $rules);

                        if ($validator->fails()) {
                            $code = $this->returnCodeAccordingToInput($validator);
                            return $this->returnValidationError($code, $validator);
                        }

                        //Upload Image
                        $Folde_Name='training_multy_media';
                        $path = public_path('images/'.$Folde_Name.'/');
                        !is_dir($path) &&
                        mkdir($path, 0777, true);

                        $imageName = time() . '.' . $request->image->extension();
                        $im=$request->image->move($path, $imageName);

                        $image_url= URL::to('/')."/images/".$Folde_Name."/".$imageName;

                        $user_id=Auth::id();

                        $order_training=OrderTraining::create([
                            'user_id'=>$user_id,
                            'is_active'=>true
                        ]);
                        $training=Training::create([
                            'text'=>$text,
                            'image_url'=>$image_url,
                            'is_active'=>true,
                            'order_id'=>$order_training->id
                        ]);
                        return $this->returnSuccessMessage('Store Training Successfully');
                    }catch (\Exception $ex) {
                        return $this->returnError($ex->getCode(), $ex->getMessage());
                    }
                }
                case "video":
                {
                    try {
                        $rules = [
                            'video' => 'required|mimetypes:video/mp4,video/quicktime|max:2048',

                        ];

                        $validator = Validator::make($request->all(), $rules);

                        if ($validator->fails()) {
                            $code = $this->returnCodeAccordingToInput($validator);
                            return $this->returnValidationError($code, $validator);
                        }
                        //Upload Video
                        $Folde_Name='training_multy_media';
                        $path = public_path('video/'.$Folde_Name.'/');
                        !is_dir($path) &&
                        mkdir($path, 0777, true);

                        $videoName = time() . '.' . $request->video->extension();
                        $vi=$request->video->move($path, $videoName);

                        $video_url= URL::to('/')."/video/".$Folde_Name."/".$videoName;

                        $user_id=Auth::id();

                        $order_training=OrderTraining::create([
                            'user_id'=>$user_id,
                            'is_active'=>true
                        ]);
                        $training=Training::create([
                            'text'=>$text,
                            'video_url'=>$video_url,
                            'is_active'=>true,
                            'order_id'=>$order_training->id
                        ]);
                        return $this->returnSuccessMessage('Store Training Successfully');
                    }catch (\Exception $ex) {
                        return $this->returnError($ex->getCode(), $ex->getMessage());
                    }
                }
                case "you_tube":
                {
                    try {
                        $rules = [
                            'youtube_url' => 'required',

                        ];

                        $validator = Validator::make($request->all(), $rules);

                        if ($validator->fails()) {
                            $code = $this->returnCodeAccordingToInput($validator);
                            return $this->returnValidationError($code, $validator);
                        }
                        $youtube_url=request()->youtube_url;
                        //Check video Url is a YouTube video link or Not.

                        $rx = '/^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$/';
                        if (preg_match($rx, $youtube_url)) {
                            // The URL is a valid YouTube video link

                            //Check video Url exists or Not.


                            $client = new Client();

                            // Make a GET request to the YouTube video URL
                            $response = $client->get($youtube_url);

                            // Get the status code of the response
                            $statusCode = $response->getStatusCode();

                            // Check if the status code is 200 (OK)
                            if ($statusCode === 200) {
                                // Video link exists
                                $user_id=Auth::id();

                                $order_training=OrderTraining::create([
                                    'user_id'=>$user_id,
                                    'is_active'=>true
                                ]);
                                $training=Training::create([
                                    'text'=>$text,
                                    'youtube_url'=>$youtube_url,
                                    'is_active'=>true,
                                    'order_id'=>$order_training->id
                                ]);
                                return $this->returnSuccessMessage('Store Training Successfully');
                            } else {
                                // Video link does not exist
                                return $this->returnError('','YouTube video link does not exist');
                            }

                        } else {
                            // The URL is not a valid YouTube video link
                            return $this->returnError('',' video link does not  a YouTube video link');
                        }


                    }




                catch (\Exception $ex) {
                    return $this->returnError($ex->getCode(), $ex->getMessage());
                }
                }
            }



            return $this->returnError('','There is an error in the data sent');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function Get_All_Orders_Product()
    {
        try {
            $AllOrders=OrderProduct::whereNull('is_active')->get();

            $Orders=[];
            foreach ($AllOrders as $order)
            {
                $id=$order['id'];
                $user=User::find($order['user_id'])->full_name;
                $Order_Date=Carbon::parse($order['created_at'])->format('Y/m/d - H:i A');
                $details = [
                    'id' => $order->product->id,
                    'full_Name' => $order->product->full_Name,
                    'image_url' => $order->product->image_url,
                    'price' => $order->product->price,
                    'type' => $order->product->type,
                    'conects_info' => $order->product->conects_info,
                    'description' => $order->product->description
                ];
                $ObjectOrder=[
                    'id'=>$id,
                    'user'=>$user,
                    'Order_Date'=>$Order_Date,
                    'details'=>$details
                ];
                array_push($Orders,$ObjectOrder);

            }
            return $this->returnData('Orders',$Orders,'These are all the requests that need to be approved or rejected .');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function Get_All_Orders_Animal_Adoption()
    {
        try {
            $AllOrders=OrderAdoption::whereNull('is_active')->get();

            $Orders=[];
            foreach ($AllOrders as $order)
            {
                $id=$order['id'];
                $user=User::find($order['user_id'])->full_name;
                $Order_Date=Carbon::parse($order['created_at'])->format('Y/m/d - H:i A');
                $details = [
                    'id' => $order->adoption->id,
                    'type_animal' => $order->adoption->type_animal,
                    'image_url' => $order->adoption->image_url,
                    'conects_info' => $order->adoption->conects_info,
                    'description' => $order->adoption->description,
                    'Reason_for_adoption' => $order->adoption->Reason_for_adoption
                ];
                $ObjectOrder=[
                    'id'=>$id,
                    'user'=>$user,
                    'Order_Date'=>$Order_Date,
                    'details'=>$details
                ];
                array_push($Orders,$ObjectOrder);

            }
            return $this->returnData('Orders',$Orders,'These are all the requests that need to be approved or rejected .');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function Get_All_Orders_Training()
    {
        try {
            $AllOrders=OrderTraining::whereNull('is_active')->get();

            $Orders=[];
            foreach ($AllOrders as $order)
            {
                $id=$order['id'];
                $user=User::find($order['user_id'])->full_name;
                $Order_Date=Carbon::parse($order['created_at'])->format('Y/m/d - H:i A');
                $details=[];
                $type_media=null;
                if($order->training->image_url !=null)
                {
                    $details = [
                        'id' => $order->training->id,
                        'text' => $order->training->text,
                        'image_url' => $order->training->image_url,
                    ];
                    $type_media='image';
                }elseif ($order->training->video_url !=null)
                {
                    $details = [
                        'id' => $order->training->id,
                        'text' => $order->training->text,
                        'video_url' => $order->training->video_url,
                    ];
                    $type_media='video';
                }else
                {
                    $details = [
                        'id' => $order->training->id,
                        'text' => $order->training->text,
                        'youtube_url' => $order->training->youtube_url,
                    ];
                    $type_media='youtube_url';
                }

                $ObjectOrder=[
                    'id'=>$id,
                    'user'=>$user,
                    'Order_Date'=>$Order_Date,
                    'type_media'=>$type_media,
                    'details'=>$details
                ];
                array_push($Orders,$ObjectOrder);

            }

            return $this->returnData('Orders',$Orders,'These are all the requests that need to be approved or rejected .');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }



    public function approved_Order($request)
    {
        try {
            $rules = [
                "order_id" => "required",
                "type"=>"required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $order_id=request()->order_id;
            $type=request()->type;

            switch ($type)
            {
                case "product":
                {
                    $order=OrderProduct::find($order_id);
                    $product=$order->product;
                    $order->update([
                        'is_active'=>true
                    ]);
                    $product->update([
                        'is_active'=>true
                    ]);
                    return $this->returnSuccessMessage('The Order was Approved Successfully');
                }
                case "adoption":
                {
                    $order=OrderAdoption::find($order_id);
                    $adoption=$order->adoption;
                    $order->update([
                        'is_active'=>true
                    ]);
                    $adoption->update([
                        'is_active'=>true
                    ]);
                    return $this->returnSuccessMessage('The Order was Approved Successfully');
                }
                case "training":
                {
                    $order=OrderTraining::find($order_id);
                    $training=$order->training;
                    $order->update([
                        'is_active'=>true
                    ]);
                    $training->update([
                        'is_active'=>true
                    ]);
                    return $this->returnSuccessMessage('The Order was Approved Successfully');
                }
            }
            return $this->returnError('','There is a problem with Request');

        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function rejected_Order($request)
    {
        try {
            $rules = [
                "order_id" => "required",
                "type"=>"required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $order_id=request()->order_id;
            $type=request()->type;

            switch ($type)
            {
                case "product":
                {
                    $order=OrderProduct::find($order_id);
                    $product=$order->product;
                    $order->update([
                        'is_active'=>false
                    ]);
                    $product->update([
                        'is_active'=>false
                    ]);
                    $order->delete();
                    $product->delete();
                    return $this->returnSuccessMessage('The Order was Rejected Successfully');
                }
                case "adoption":
                {
                    $order=OrderAdoption::find($order_id);
                    $adoption=$order->adoption;
                    $order->update([
                        'is_active'=>false
                    ]);
                    $adoption->update([
                        'is_active'=>false
                    ]);
                    $order->delete();
                    $adoption->delete();
                    return $this->returnSuccessMessage('The Order was Rejected Successfully');
                }
                case "training":
                {
                    $order=OrderTraining::find($order_id);
                    $training=$order->training;
                    $order->update([
                        'is_active'=>false
                    ]);
                    $training->update([
                        'is_active'=>false
                    ]);
                    $order->delete();
                    $training->delete();
                    return $this->returnSuccessMessage('The Order was Rejected Successfully');
                }
            }
            return $this->returnError('','There is a problem with Request');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function edit_Illness($request)
    {
        try {
            $rules = [
                "illness_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Illness=Illness::select('id','name_illness','symptoms','treatment')->find(request()->illness_id);

            return $this->returnData('Illness',$Illness,'This is Illness selected');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_Illness($request)
    {
        try {
            $rules = [
                "name_illness" => "required",
                "symptoms" => "required",
                "treatment" => "required",
                "illness_id"=>"required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $illness_id=request()->illness_id;
            $name_illness=request()->name_illness;
            $symptoms=request()->symptoms;
            $treatment=request()->treatment;
            $illness=Illness::find($illness_id);
            $illness->update([
                'name_illness'=>$name_illness,
                'symptoms'=>$symptoms,
                'treatment'=>$treatment
            ]);
            return $this->returnSuccessMessage('Update Illness Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_Illness($request)
    {
        try {
            $rules = [
                "illness_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Illness=Illness::find(request()->illness_id);
            $Illness->delete();

            return $this->returnSuccessMessage('The Illness Successfully Delete');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function edit_Doctor($request)
    {
        try {
            $rules = [
                "doctor_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Doctor=Doctor::select('id','full_Name_doctor','phone_number','address','description')->find(request()->doctor_id);

            return $this->returnData('Doctor',$Doctor,'This is Doctor selected');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_Doctor($request)
    {
        try {
            $rules = [
                "full_Name_doctor" => "required",
                "phone_number" => "required",
                "address" => "required",
                "description" => "required",
                "doctor_id" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }


            $full_Name_doctor=request()->full_Name_doctor;
            $phone_number=request()->phone_number;
            $address=request()->address;
            $description=request()->description;
            $doctor_id=request()->doctor_id;

            $doctor=Doctor::find($doctor_id);

            $doctor->update([
                'full_Name_doctor'=>$full_Name_doctor,
                'phone_number'=>$phone_number,
                'address'=>$address,
                'description'=>$description,
            ]);
            return $this->returnSuccessMessage('Update Doctor Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_Doctor($request)
    {
        try {
            $rules = [

                "doctor_id" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $doctor_id=request()->doctor_id;

            $doctor=Doctor::find($doctor_id);
            $doctor->delete();

            return $this->returnSuccessMessage('The Doctor Successfully Delete');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function edit_Clinic($request)
    {
        try {
            $rules = [
                "clinic_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Clinic=Clinic::select('id','full_Name_clinic','phone_number','address','available_doctors','working_days','description')->find(request()->clinic_id);

            return $this->returnData('Clinic',$Clinic,'This is Clinic selected');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_Clinic($request)
    {
        try {
            $rules = [
                "full_Name_clinic" => "required",
                "phone_number" => "required",
                "address" => "required",
                "available_doctors" => "required",
                "working_days" => "required",
                "description" => "required",
                "clinic_id" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $full_Name_clinic=request()->full_Name_clinic;
            $phone_number=request()->phone_number;
            $address=request()->address;
            $available_doctors=request()->available_doctors;
            $working_days=request()->working_days;
            $description=request()->description;
            $clinic_id=request()->clinic_id;

            $clinic=Clinic::find($clinic_id);

            $clinic->update([
                'full_Name_clinic'=>$full_Name_clinic,
                'phone_number'=>$phone_number,
                'address'=>$address,
                'available_doctors'=>$available_doctors,
                'working_days'=>$working_days,
                'description'=>$description,
            ]);
            return $this->returnSuccessMessage('Update Clinic Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_Clinic($request)
    {
        try {
            $rules = [

                "clinic_id" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $clinic_id=request()->clinic_id;

            $clinic=Clinic::find($clinic_id);

            $clinic->delete();

            return $this->returnSuccessMessage('The Clinic Successfully Delete');


        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function edit_Animal($request)
    {
        try {
            $rules = [
                "animal_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Animal=Animal::select('id','name','image_url','description','type')->find(request()->animal_id);

            return $this->returnData('Animal',$Animal,'This is Animal selected');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_Animal($request)
    {
        try {
            $rules = [
                "name" => "required",
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                "description" => "required",
                "type" => "required",
                "animal_id" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $name=request()->name;
            $description=request()->description;
            $type=request()->type;
            $animal_id=request()->animal_id;
            $animal=Animal::find($animal_id);

            //Upload Animal Image
            $Folde_Name='animals';
            $path = public_path('images/'.$Folde_Name.'/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $imageName = time() . '.' . $request->image->extension();
            $im=$request->image->move($path, $imageName);

            $image_url= URL::to('/')."/images/".$Folde_Name."/".$imageName;


            $animal->update([
                'name'=>$name,
                'image_url'=>$image_url,
                'description'=>$description,
                'type'=>$type
            ]);
            return $this->returnSuccessMessage('Update Animal Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_Animal($request)
    {
        try {
            $rules = [

                "animal_id" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $animal_id=request()->animal_id;
            $animal=Animal::find($animal_id);
            $animal->delete();

            return $this->returnSuccessMessage('The Animal Successfully Delete');


        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }




}
