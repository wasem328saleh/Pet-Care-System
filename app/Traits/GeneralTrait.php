<?php

namespace App\Traits;
use App\Mail\BabyLC;
use App\Models\Center;
use App\Models\Childe;
use App\Models\CivilRegistr;
use App\Models\FamilyBook;
use App\Models\MY_Parent;
use App\Models\Notification;
use App\Models\Training;
use App\Models\User;
use App\Models\Vaccine;
use Carbon\Carbon;

use DateTime;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

trait GeneralTrait
{
    public function returnDataOfTraining($type_media,$key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            'type_media' => $type_media,
            $key => $value
        ]);
    }

    public function get_Type_Media($Training)
    {
        if($Training->image_url !=null)
        {
            return "image";
        }elseif ($Training->video_url !=null)
        {
            return "video";
        }
        return "you_tube";
    }

    public function strrevpos($instr, $needle)
    {
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    }

    public function after ($t, $inthat)
    {
        if (!is_bool(strpos($inthat, $t)))
            return substr($inthat, strpos($inthat,$t)+strlen($t));
    }

    public function after_last ($t, $inthat)
    {
        if (!is_bool($this->strrevpos($inthat, $t)))
        { return substr($inthat, $this->strrevpos($inthat, $t)+strlen($t));}
    }

    public function before ($t, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $t));
    }

    public function before_last ($t, $inthat)
    {
        return substr($inthat, 0, $this->strrevpos($inthat, $t));
    }

    public function between ($t, $that, $inthat)
    {
        return $this->before ($that, $this->after($t, $inthat));
    }

    public function between_last ($t, $that, $inthat)
    {
        return $this->after_last($t, $this->before_last($that, $inthat));
    }

    public function get_FullName($childe_id)
    {
        $child=Childe::find($childe_id);
        $name=$child->name;
        $parent_id=$child->parent_id;
        $parent=MY_Parent::find($parent_id);
        $father_firstName=$parent->father_firstName;
        $father_lastName=$parent->father_lastName;
        $Full_Name=$name.' '.$father_firstName.' '.$father_lastName;
        return $Full_Name;
    }

    public function get_Age_Month($childe_id)
    {
        $now = Carbon::now();
        $child=Childe::find($childe_id);
        $birth_date=Carbon::parse($child->birth_date)->toDateString();
        $diff = $now->diff($birth_date);
        $ageInMonths = ($diff->y * 12) + $diff->m;
        $response=[$childe_id,$ageInMonths];
        return $response;
    }
    public function get_All_Vaccine_not_Execute($v_name,$child_id)
    {
        $Vaccines=Vaccine::select('id','name_ar','due_month_of_Age','doses_number','AD')->get();
        $v=[];
        foreach ($Vaccines as $vaccine)
        {
            if(!in_array($vaccine['name_ar'],$v_name))
            {
                $v[]=$vaccine;
            }
        }
        return $v;
    }
    public function getMinId($objects) {
        $minId = null;
        foreach ($objects as $object) {
            if ($minId == null || $object->id < $minId->id) {
                $minId = $object;
            }
        }
        return $minId;
    }
    public function getDaysToAdd($dob, $months) {
        $dobDateTime = new DateTime($dob);
        $targetDateTime = clone $dobDateTime;
        $targetDateTime->modify('+' . $months . ' months');
        $diff = $targetDateTime->diff($dobDateTime);
        return $diff->days;
    }
    public function diffDays($date1, $date2) {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        return $interval->days;
    }
    public function addDays($date, $days) {
        $datetime = new DateTime($date);
        $datetime->modify('+' . $days . ' days');
        return $datetime->format('Y-m-d');
    }
    public function get_next_visit($v_name,$child_id,$center_id)
    {
        $dates=[];
        $center=Center::find($center_id);
        $sessions=$center->VaccineSessions;
        foreach ($sessions as $session)
        {
            $dates[]=Carbon::parse($session->date)->format(' Y-m-d');
        }
        $child=Childe::find($child_id);
        $birth_date=$child->birth_date;
        $v_d=$this->getMinId($this->get_All_Vaccine_not_Execute($v_name,$child_id));
        $givenDate=$this->addDays(Carbon::now()->toDateString(),$this->getDaysToAdd($birth_date,$v_d->due_month_of_Age)-$this->diffDays($birth_date,Carbon::now()->toDateString()));

        $closesDate=null;
        $closesDiff=null;
         $d=Carbon::parse('2023-9-2')->format('Y-m-d');
        foreach ($dates as $date)
        {
           // $diff=abs(Carbon::parse($date)->diffInDays(Carbon::parse($givenDate)));

//            if($closesDiff==null || $diff<$closesDiff)
//            {
//                $closesDate=$date;
//                $closesDiff=$diff;
//            }
//            echo Carbon::parse($date)->format('Y-m-d')."  \n";
            if(Carbon::parse($date)->format('Y-m-d')>$givenDate)
            {
                $closesDate=$date;

                break;
            }
        }
        return $closesDate;
    }

public function get_All_Vaccine_Name_due($response)
{
    $child_id=$response[0];
    $ageInMonths=$response[1];
    $Vaccines=[];


//    if($this->getLanguageApplication()=="en")
//    {
//        $All_Vaccines=Vaccine::select('name_en','due_month_of_Age','doses_number','AD')->get();
//
//    }
//    elseif ($this->getLanguageApplication()=="ar")
//    {
//
//        $All_Vaccines=Vaccine::select('name_ar','due_month_of_Age','doses_number','AD')->get();
//
//    }
    $All_Vaccines=Vaccine::select('id','name_ar','due_month_of_Age','doses_number','AD')->get();
    foreach ($All_Vaccines as $vaccine)
    {
        if($vaccine->due_month_of_Age <=$ageInMonths)
        {
            $Vaccines[]=$vaccine;
        }
    }

    $Child=Childe::find($child_id);
    $VE=$Child->vaccineCard->VaccineCardLines;
    if($VE->count()==0)
    {
        return $Vaccines;
    }
    $Vaccines_Execute=[];
    foreach ($VE as $item)
    {
        $Vaccines_Execute[]=$item->v_name;
    }
    $Vaccines_Due=[];
    foreach ($Vaccines as $vaccine)
    {
        if(!in_array($vaccine['name_ar'],$Vaccines_Execute))
        {
            $Vaccines_Due[]=$vaccine;
        }
    }

    return $Vaccines_Due;


}
public function getWithProperoty($p,$v,$arr)
{
    $answer='';
    foreach ($arr as $item)
    {
        if($item[$p]==$v)
        {
            $answer=$item;
            break;
        }
    }
    return $answer['id'];
}
public function language_App()
{
    $path=resource_path('lang/language.json');
    $data=json_decode(File::get($path),true);
    $lang=$data['language'];
    App::setLocale($lang);
}

    public function ChangeLanguage($lang)
    {

        $path = resource_path('lang/language.json');
        $data = json_decode(File::get($path), true);
        $data['language'] = $lang;
        File::put($path, json_encode($data));
    }
    public function Language()
    {
        $path = resource_path('lang/language.json');
        $data = json_decode(File::get($path), true);
        $lang=$data['language'];
        return $this->returnData('language',$lang,'');

    }

    public function getLanguageApplication()
    {
        $path = resource_path('lang/language.json');
        $data = json_decode(File::get($path), true);
        $lang=$data['language'];
        return $lang;

    }
    public function RoleAssignToUser($user_id,$Role)
    {
        $user=User::find($user_id);

        $user->assignRole($Role);
    }
    public function get_Father_and_Mother($familyBook_id)
    {
        $FamilyBook=FamilyBook::find($familyBook_id);

        $Father=$FamilyBook->Father;
        $Mother=$FamilyBook->Mother;
        return $FamilyBook;
    }
    public function get_Family_book_of_persone($persone_id)
    {
        $p=CivilRegistr::find($persone_id);
        if($p->gender=='male')
        {
            return $p->family_book_Hasfather;
        }
        return $p->family_book_Hasmother;
    }


    public function Check_Family_Book_Number($national_ID_Father,$national_ID_Mother)
    {
        $father_id=CivilRegistr::where('national_ID','like',$national_ID_Father)->get();
        $mother=CivilRegistr::where('national_ID','like',$national_ID_Mother)->get();


        $f=$father_id[0]->family_book_Hasfather;
        $m=$mother[0]->family_book_Hasmother;
        if($f->Family_book_number==$m->Family_book_number)
        {
            $result=1;
            return $result;
        }
        $result=0;
        return $result;
    }
    public function Get_Family_Book_Number($national_ID_Father,$national_ID_Mother)
    {
        $father_id=CivilRegistr::where('national_ID','like',$national_ID_Father)->get();
        $mother=CivilRegistr::where('national_ID','like',$national_ID_Mother)->get();


        $f=$father_id[0]->family_book_Hasfather;
        $m=$mother[0]->family_book_Hasmother;
        if($f->Family_book_number==$m->Family_book_number)
        {
            $result=$f->Family_book_number;
            return $result;
        }
        $result=00000;
        return $result;
    }

    public function CheckActiveUser()
    {
        $user=Auth::user();

        $result=0;
        if($user->status_active==1)
        {
            $result=1;
            return $result;
        }
        return $result;
    }

    public function CheckInformation($first_name,$last_name,$national_ID)
    {
        $person=CivilRegistr::where('first_name','like',$first_name)->where('last_name','like',$last_name)->where('national_ID','like',$national_ID)->get();
        if($person->count()>0)
        {
            $result=1;
            return $result;
        }
        $result=0;
        return $result;
    }
    public function sendVerificationCode($user_id)
    {
        $user=User::find($user_id);
        $email=$user->email;
        $code=rand(pow(10, 4), pow(10, 5)-1);
                $data=[
            'title'=>trans('messages.Email_Verification_Code'),
            'body'=>trans('messages.Thank_you_for_signing_up'),
            'code'=>$code
        ];
        Mail::to($email)->send(new BabyLC($data));
        $user->update([
            'verification_code'=>$code
        ]);

        return $this->returnSuccessMessage(trans('messages.please_see_gmail'));
    }

    public function ResendVerificationCode($email)
    {
        $user=User::where('email','like',$email)->get();

        $code=rand(pow(10, 4), pow(10, 5)-1);
        $data=[
            'title'=>trans('messages.Email_Verification_Code'),
            'body'=>trans('messages.Thank_you_for_signing_up'),
            'code'=>$code
        ];
        Mail::to($email)->send(new BabyLC($data));
        $user[0]->update([
            'verification_code'=>$code
        ]);

        return $this->returnData('email',$email,trans('messages.please_see_gmail'));
    }

    public function senNotification($title,$body)
    {
        //$SERVER_API_KEY ='AAAAsLxtfnw:APA91bGZD0LEoYj8jIqD-vbjFjvVW1Fn3FFmAfflvh-I7TGOt4QxIxz6FY2FRhbnHF0o6tzeA52vsfqQOY4RTgCE1ZAfu8NpzN8oKslurYrMBh71Gci7RbO4qelpqoo7NfcrqSbGtlkp';
       // $SERVER_API_KEY ='AAAAFvveZVc:APA91bE2WDIZ251GszccUxV1kuJ-PQ3reVbnS6_YG_rYM9RdSynZBAe9_3Y5uMl496MiRR_GmporTRfopUvDKFhAsjJ-PybIE44CA19SXuOcqx7-95mQ6y_G5eG1rB-w2izkD2TvWb7f';
        $SERVER_API_KEY =env('FCM_KEY');
        //$token_1 = 'dEJItcZ8sANYR8DYRx3Kn5:APA91bFm-Q1MMeAabG2etTIqByAkzy1PnrtQqgkcGuvqF3uRrBcXm6VjajbCkv0ci5348wfv-GiTBBmTNlg44I0NGPe1SPj9QRSQOzruKVe2YiMEVe5dvwVAX47Q_NYFvtDUnRRZDskq';
        $user=Auth::user();
        $tokenDevice=$user->device_token;
        $data = [
            "registration_ids" => [ $tokenDevice ],
        "notification" => [
            "title" => $title
            ,
            "body" => $body
            ,
            "sound"=> "default" //required for sound on ios
         ], ];
        $dataString = json_encode($data);
        $headers = [ 'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json'
        , ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

       // curl_close($ch);
//return $user;
        if($status==200)
        {
            $ww=Carbon::now()->format('h:i A');

            $notification=Notification::create([
                'title'=>$title,
                'body_text'=>$body,
                'date'=>Carbon::now()->format('Y-m-d'),
                'time'=>$ww,
                'user_id'=>$user->id
            ]);
            return $notification;
        }


    }

    public function senNotificationWeb($title,$body)
    {
        $path = resource_path('lang/token.json');
        $datat = json_decode(File::get($path), true);
        $token=$datat['token'];
        //$SERVER_API_KEY ='AAAAsLxtfnw:APA91bGZD0LEoYj8jIqD-vbjFjvVW1Fn3FFmAfflvh-I7TGOt4QxIxz6FY2FRhbnHF0o6tzeA52vsfqQOY4RTgCE1ZAfu8NpzN8oKslurYrMBh71Gci7RbO4qelpqoo7NfcrqSbGtlkp';
        $SERVER_API_KEY ='AAAAFvveZVc:APA91bE2WDIZ251GszccUxV1kuJ-PQ3reVbnS6_YG_rYM9RdSynZBAe9_3Y5uMl496MiRR_GmporTRfopUvDKFhAsjJ-PybIE44CA19SXuOcqx7-95mQ6y_G5eG1rB-w2izkD2TvWb7f';
        $token_1 =$token;
        $data = [
            "registration_ids" => [ $token_1 ],
            "notification" => [
                "title" => $title
                ,
                "body" => $body
                ,
                "sound"=> "default" //required for sound on ios
            ], ];
        $dataString = json_encode($data);
        $headers = [ 'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json'
            , ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
//        dd($response);

    }

    public function send_notification_FCM($notification_id, $title, $message, $id,$type) {

        $accesstoken = env('FCM_KEY');
        echo $accesstoken;

        $URL = 'https://fcm.googleapis.com/fcm/send';


        $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },

          }';
        // print_r($post_data);die;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: ' . $accesstoken;
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($crl, CURLOPT_URL, $URL);
        curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

        $rest = curl_exec($crl);

        if ($rest === false) {
            // throw new Exception('Curl error: ' . curl_error($crl));
            //print_r('Curl error: ' . curl_error($crl));
            $result_noti = 0;
        } else {

            $result_noti = 1;
        }

        //curl_close($crl);
        //print_r($result_noti);die;
        return $result_noti;
    }


    public function checkDates()
    {

        $products=Product::all();
        $now=Carbon::now()->toDateString();
        for ($i=0;$i<count($products);$i++)
        {

            if (($products[$i]->exp_date) <= $now)
            {
                $id=$products[$i]->id;
                $product=Product::find($id);
                $product->delete();
            }
            elseif ( ($products[$i]->price->d1) <= $now && $now < ($products[$i]->price->d2))
            {

                $g=$products[$i]->o_price;
                $c=$products[$i]->price->s2;
                $s=($c*$g)/100;
                $s_price=$g-$s;
                $id=$products[$i]->id;
                $product=Product::find($id);
                $product->update([
                    's_price'=>$s_price
                ]);
            }
            elseif ( ($products[$i]->price->d2) <= $now && $now < ($products[$i]->exp_date))
            {

                $g=$products[$i]->o_price;
                $c=$products[$i]->price->s3;
                $s=($c*$g)/100;
                $s_price=$g-$s;
                $id=$products[$i]->id;
                $product=Product::find($id);
                $product->update([
                    's_price'=>$s_price
                ]);
            }
        }


        return 1;
    }

    public function getGender($national_ID)
    {
        $person=CivilRegistr::where('national_ID','like',$national_ID)->get();
        return $person[0]['gender'];

    }




    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }



    public function returnSuccessMessage($msg = "", $errNum = "S000")
    {
        return [
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }


}
