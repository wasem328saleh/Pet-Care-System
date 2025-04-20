<?php

namespace App\Repository;

use App\Models\Adoption;
use App\Models\Animal;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Illness;
use App\Models\Product;
use App\Models\Training;
use App\Traits\GeneralTrait;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
class SystemRepository implements SystemRepositoryInterface
{
    use GeneralTrait;
    public function login($request)
    {

        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('user-api')->attempt($credentials);

            if (!$token)
                return $this->returnError('E001', 'The login information is incorrect');

            $user = Auth::guard('user-api')->user();
            $user->api_token = $token;
            return $this->returnData('user', $user,'You are logged in successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function logout($request)
    {
        $token = $request->header('auth-token');
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return  $this->returnError('', 'some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        } else {
            return $this->returnError('', 'some thing went wrongs');
        }
    }

    public function Get_All_Doctors()
    {
        try {
            $Doctors=Doctor::all();
            return $this->returnData('Doctors',$Doctors,'This is All Doctors');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_All_Clinics()
    {
        try {
            $Clinics=Clinic::all();
            return $this->returnData('Clinics',$Clinics,'This is All Clinics');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_All_Illnesses()
    {
        try {
            $Illnesses=Illness::all();
            return $this->returnData('Illnesses',$Illnesses,'This is All Illnesses');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_All_Animals()
    {
        try {
            $Animals=Animal::all();
            return $this->returnData('Animals',$Animals,'This is All Animals');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_All_Products()
    {
        try {
            $Products=Product::where('is_active',true)->get();
            return $this->returnData('Products',$Products,'This is All Products');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_All_Animals_Adoption()
    {
        try {
            $Animals_of_Adoption=Adoption::where('is_active',true)->get();
            return $this->returnData('Animals_of_Adoption',$Animals_of_Adoption,'This is All Animals of Adoption');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Get_All_Trainings()
    {
        try {
            $All_Trainings=Training::where('is_active',true)->get();
            $Trainings=array();
            foreach ($All_Trainings as $training)
            {
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
                        array_push($Trainings,$objectTraining);
                        break;
                    }
                    case "video":
                    {
                        $training->setVisible(['id','text','video_url']);
                        $objectTraining=[
                            'type_media'=>$type_media,
                            'training'=>$training
                        ];
                        array_push($Trainings,$objectTraining);
                        break;
                    }
                    case "you_tube":
                    {
                        $training->setVisible(['id','text','youtube_url']);
                        $objectTraining=[
                            'type_media'=>$type_media,
                            'training'=>$training
                        ];
                        array_push($Trainings,$objectTraining);
                        break;
                    }
                }
            }
            return $this->returnData('Trainings',$Trainings,'This is All Trainings');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function edit_Product($request)
    {
        try {
            $rules = [
                "product_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Product=Product::select('id','full_Name','image_url','price','type','conects_info','description')->find(request()->product_id);

            return $this->returnData('Product',$Product,'This is Product selected');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_Product($request)
    {
        try {
            $rules = [
                "full_Name" => "required",
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                "price" => "required",
                "type" => "required",
                "conects_info" => "required",
                "description" => "required",
                "product_id" => "required",

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
            $product_id=request()->product_id;
            $product=Product::find($product_id);
            //Upload Animal Image
            $Folde_Name='products';
            $path = public_path('images/'.$Folde_Name.'/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $imageName = time() . '.' . $request->image->extension();
            $im=$request->image->move($path, $imageName);

            $image_url= URL::to('/')."/images/".$Folde_Name."/".$imageName;


            $product->update([
                'full_Name'=>$full_Name,
                'image_url'=>$image_url,
                'price'=>$price,
                'type'=>$type,
                'conects_info'=>$conects_info,
                'description'=>$description,
            ]);

            return $this->returnSuccessMessage('Update Product Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_Product($request)
    {

        try {
            $rules = [

                "product_id" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $product_id=request()->product_id;
            $product=Product::find($product_id);
            $order=$product->order;
            $product->delete();
            $order->delete();
            return $this->returnSuccessMessage('The Product Successfully Delete');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function edit_Animal_Adoption($request)
    {
        try {
            $rules = [
                "animal_adoption_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Animal_Adoption=Adoption::select('id','type_animal','image_url','conects_info','description','Reason_for_adoption')->find(request()->animal_adoption_id);

            return $this->returnData('Animal_Adoption',$Animal_Adoption,'This is Animal of Adoption selected');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_Animal_Adoption($request)
    {
        try {
            $rules = [
                "type_animal" => "required",
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                "conects_info" => "required",
                "description" => "required",
                "Reason_for_adoption" => "required",
                "animal_adoption_id" => "required",
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
            $animal_adoption_id=request()->animal_adoption_id;

            //Upload Animal Image
            $Folde_Name='animalsAdoption';
            $path = public_path('images/'.$Folde_Name.'/');
            !is_dir($path) &&
            mkdir($path, 0777, true);

            $imageName = time() . '.' . $request->image->extension();
            $im=$request->image->move($path, $imageName);

            $image_url= URL::to('/')."/images/".$Folde_Name."/".$imageName;

            $animal_adoption=Adoption::find($animal_adoption_id);

            $animal_adoption->update([
                'type_animal'=>$type_animal,
                'image_url'=>$image_url,
                'conects_info'=>$conects_info,
                'description'=>$description,
                'Reason_for_adoption'=>$Reason_for_adoption,
            ]);

            return $this->returnSuccessMessage('Update Animal of Adoption Successfully');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_Animal_Adoption($request)
    {
        try {
            $rules = [

                "animal_adoption_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $animal_adoption_id=request()->animal_adoption_id;
            $animal_adoption=Adoption::find($animal_adoption_id);
            $order=$animal_adoption->order;
            $animal_adoption->delete();
            $order->delete();
            return $this->returnSuccessMessage('The Animal of Adoption Successfully Delete');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function edit_Training($request)
    {
        try {
            $rules = [
                "training_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Training=Training::find(request()->training_id);
            $type_media=null;
            if($Training->image_url !=null)
            {
                $type_media='image';
                $Training->setVisible(['id','text', 'image_url']);
            }elseif ($Training->video_url !=null)
            {
                $type_media='video';

                $Training->setVisible(['id','text', 'video_url']);
            }else
            {
                $type_media='youtube_url';

                $Training->setVisible(['id','text', 'youtube_url']);
            }

            return $this->returnDataOfTraining($type_media,'Training',$Training,'This is Training selected');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_Training($request)
    {
        try {


            $rules = [
                "text" => "required",
                "media" => "required",
                "training_id" => "required",

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $text=request()->text;

            $training_id=request()->training_id;
            $training=Training::find($training_id);
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

                        $training->update([
                            'text'=>$text,
                            'image_url'=>$image_url,
                            'video_url'=>null,
                            'youtube_url'=>null
                        ]);

                        return $this->returnSuccessMessage('Update Training Successfully');
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

                        $training->update([
                            'text'=>$text,
                            'image_url'=>null,
                            'video_url'=>$video_url,
                            'youtube_url'=>null
                        ]);
                        return $this->returnSuccessMessage('Update Training Successfully');
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

                                $training->update([
                                    'text'=>$text,
                                    'image_url'=>null,
                                    'video_url'=>null,
                                    'youtube_url'=>$youtube_url
                                ]);

                                return $this->returnSuccessMessage('Update Training Successfully');
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

    public function delete_Training($request)
    {

        try {
            $rules = [
                "training_id" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $Training=Training::find(request()->training_id);

            $order=$Training->order;
            $Training->delete();
            $order->delete();
            return $this->returnSuccessMessage('The Training Successfully Delete');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


}
