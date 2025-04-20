<?php

namespace App\Repository;

use App\Models\Adoption;
use App\Models\Illness;
use App\Models\OrderAdoption;
use App\Models\OrderProduct;
use App\Models\OrderTraining;
use App\Models\Product;
use App\Models\Training;
use App\Models\User;
use App\Traits\GeneralTrait;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UserRepository implements UserRepositoryInterface
{
use GeneralTrait;
    public function register($request)
    {
        try {
            $rules = [
                "email" => "required|email|unique:users",
                "password" => "required|min:8",
                "full_name" => "required",
                "phone_number" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $full_name=request()->full_name;
            $email=request()->email;
            $password=request()->password;
            $phone_number=request()->phone_number;
            $is_admin=false;
            //Register

            $user=User::create([
                'full_name'=>$full_name,
                'email'=>$email,
                'password'=>Hash::make($password),
                'phone_number'=>$phone_number,
                'is_admin'=>$is_admin
            ]);

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('user-api')->attempt($credentials);

            if (!$token)
                return $this->returnError('E001', 'The login information is incorrect');

            $user = Auth::guard('user-api')->user();
            $user->api_token = $token;
            return $this->returnData('user', $user,'You are Register in successfully');

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
                'user_id'=>$user_id
            ]);
            $product=Product::create([
                'full_Name'=>$full_Name,
                'image_url'=>$image_url,
                'price'=>$price,
                'type'=>$type,
                'conects_info'=>$conects_info,
                'description'=>$description,
                'order_id'=>$order_product->id
            ]);

            return $this->returnSuccessMessage('Please wait until your request is approved, dear.');

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
                'user_id'=>$user_id
            ]);

            $animal_adoption=Adoption::create([
                'type_animal'=>$type_animal,
                'image_url'=>$image_url,
                'conects_info'=>$conects_info,
                'description'=>$description,
                'Reason_for_adoption'=>$Reason_for_adoption,
                'order_id'=>$order_adoption->id
            ]);

            return $this->returnSuccessMessage('Please wait until your request is approved, dear.');

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
                            'user_id'=>$user_id
                        ]);
                        $training=Training::create([
                            'text'=>$text,
                            'image_url'=>$image_url,
                            'order_id'=>$order_training->id
                        ]);
                        return $this->returnSuccessMessage('Please wait until your request is approved, dear.');
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
                            'user_id'=>$user_id
                        ]);
                        $training=Training::create([
                            'text'=>$text,
                            'video_url'=>$video_url,
                            'order_id'=>$order_training->id
                        ]);
                        return $this->returnSuccessMessage('Please wait until your request is approved, dear.');
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
                                    'user_id'=>$user_id
                                ]);
                                $training=Training::create([
                                    'text'=>$text,
                                    'youtube_url'=>$youtube_url,
                                    'order_id'=>$order_training->id
                                ]);
                                return $this->returnSuccessMessage('Please wait until your request is approved, dear.');
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

    public function Get_All_Symptoms()
    {
        try {

            $All_Symptoms=[];
            $Illnesses=Illness::all();
            foreach ($Illnesses as $illness)
            {
                $symptoms=$illness->symptoms;
                array_push($All_Symptoms,$symptoms);
                $symptoms=null;
            }
            return $this->returnData('All_Symptoms',$All_Symptoms,'This is All Symptoms');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_Illness_By_Symptoms($request)
    {
        try {
            $rules = [
                'symptoms' => 'required',

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $symptoms=request()->symptoms;
            $Illness=Illness::select('id','name_illness','symptoms','treatment')->where('symptoms','like',$symptoms)->get();
            return $this->returnData('Illness',$Illness,'This is Illness resrched');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_My_Product()
    {
        try {
            $user=Auth::user();
            $orders_product=$user->orders_product;
            $products=array();
            foreach ($orders_product as $order)
            {
                if ($order['is_active'])
                {
                    $product=$order->product;
                    $product->setVisible(['id','full_Name','image_url','price','type','conects_info','description']);
                    array_push($products,$product);
                }
            }
            return $this->returnData('My_Products',$products,'This is Your Products');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function Get_My_Animal_Adoption()
    {
        try {
            $user=Auth::user();
            $orders_animal_of_adoption=$user->orders_adoption;
            $animals_of_adoption=array();
            foreach ($orders_animal_of_adoption as $order)
            {
                if ($order['is_active'])
                {
                    $animal_of_adoption=$order->adoption;
                    $animal_of_adoption->setVisible(['id','type_animal','image_url','conects_info','description','Reason_for_adoption']);
                    array_push($animals_of_adoption,$animal_of_adoption);
                }
            }
            return $this->returnData('My_Animals_of_Adoption',$animals_of_adoption,'This is Your Animals of Adoption');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_My_Training()
    {
        try {
            $user=Auth::user();
            $orders_training=$user->orders_training;
            $trainings=array();
            foreach ($orders_training as $order)
            {
                if ($order['is_active'])
                {
                    $training=$order->training;
                    $type_media=$this->get_Type_Media($training);
                    switch ($type_media)
                    {

                        case "image":
                        {
                            $training->setVisible(['id','text','image_url']);
                            $objectTraining=[
                                'type_media'=>$type_media,
                                'training'=>$training
                            ];
                            array_push($trainings,$objectTraining);
                            break;
                        }
                        case "video":
                        {
                            $training->setVisible(['id','text','video_url']);
                            $objectTraining=[
                                'type_media'=>$type_media,
                                'training'=>$training
                            ];
                            array_push($trainings,$objectTraining);
                            break;
                        }
                        case "you_tube":
                        {
                            $training->setVisible(['id','text','youtube_url']);
                            $objectTraining=[
                                'type_media'=>$type_media,
                                'training'=>$training
                            ];
                            array_push($trainings,$objectTraining);
                            break;
                        }
                    }

                }
            }
            return $this->returnData('My_Trainings',$trainings,'This is Your Trainings');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



}
