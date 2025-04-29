<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Nette\Utils\Random;


trait GeneralTrait
{



    public function relationship_exists($firstId,$secondId,$relationshipName,$model)
    {
        $exists = $model::whereHas($relationshipName, function ($query) use ($secondId) {
            $query->where('id', $secondId);
        })->where('id', $firstId)->exists();
        return $exists;
    }
    public function generate_serialnumber($model)
    {
        $model_name=$this->after_last('\\',$model);
        $fist_char=Str::upper(Str::substr($model_name,0,1));
        do {
            $randomNumber = mt_rand(1000000, 9999999);
            $serial_number =$fist_char . '_' . $randomNumber;
        } while ($model::where('serial_number',$serial_number)->exists());
        return $serial_number;
    }
    public function is_contains_array($array,$value)
    {
        foreach ($array as $item)
        {
            if (Str::contains($item,$value))
            {
                return true;
            }
        }
        return false;
    }
    function decrypt_json($encrypted_data) {
        // Extract encrypted data, IV, and key from the combined string
        $encrypted_data_with_iv_key = base64_decode($encrypted_data);
        $encrypted_data_length = strlen($encrypted_data_with_iv_key) - 32 - 16;
        $encrypted_data = substr($encrypted_data_with_iv_key, 0, $encrypted_data_length);
        $iv = substr($encrypted_data_with_iv_key, $encrypted_data_length, 16);
        $key = substr($encrypted_data_with_iv_key, $encrypted_data_length + 16);

        // Decrypt the JSON data
        $decrypted_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);

        return $decrypted_data;
    }
    function encrypt_json($json_data, $password) {
        // Generate a random encryption key
        $key = openssl_random_pseudo_bytes(32);

        // Create an encryption context
        $ivlen = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($ivlen);
        $options = 0;

        // Encrypt the JSON data
        $encrypted_data = openssl_encrypt($json_data, 'aes-256-cbc', $key, $options, $iv);

        // Combine the encrypted data, IV, and key for storage
        $encrypted_data_with_iv_key = base64_encode($encrypted_data . $iv . $key);

        return $encrypted_data_with_iv_key;
    }
    public function replace_key($array, $old_key, $new_key) {
        $keys = array_keys($array);
        if (false === $index = array_search($old_key, $keys, true)) {
            throw new Exception(sprintf('Key "%s" does not exist', $old_key));
        }
        $keys[$index] = $new_key;
        return array_combine($keys, array_values($array));
    }
    public function checkKeyExists($array, $key, $value) {
        foreach ($array as $item) {
            if ($item[$key]==$value) {
                return true;
            }
        }
        return false;
    }

//******************************* Image Functions ***************************************************

    public function UploadeImage($Folder, $Image)
    {
        if (!is_null($Image)) {
            $Folde_Name = "";
            $Folde_Name = $Folder;
            $path = public_path('Upload/' . $Folde_Name . '/');
            !is_dir($path) && mkdir($path, 0777, true);
            $number_r = Random::generate(6);
            $imageName = $number_r . '.' . $Image->extension();
            $im = $Image->move($path, $imageName);
            $image_url = "/Upload/" . $Folde_Name . "/" . $imageName;
            return $image_url;
        }
        return null;
    }

    public function updateImage($Folder, $model, $image)
    {
        $ob = $model::where('id', $image['id'])->first();
        Storage::delete(url($ob->url));
        if (isset($image['file']) && $image['file']->isValid()) {
            $ob->update(['url' => $this->UploadeImage($Folder, $image['file'])]);
            return;
        }
        $ob->delete();
    }

    public function deleteImage($image)
    {
//        return $image;

        if (!$image)
        {
            return $this->returnError(00,'هناك شيء خاطئ');
        }
        Storage::delete(url($image->url));
        $image->delete();
        return $this->returnSuccessMessage(trans('messages.Delete'));
    }

//**********************************************************************************************

//*********************** Function String *******************************************
    public function strrevpos($instr, $needle)
    {
        $rev_pos = strpos(strrev($instr), strrev($needle));
        if ($rev_pos === false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    }

    public function after($t, $inthat)
    {
        if (!is_bool(strpos($inthat, $t)))
            return substr($inthat, strpos($inthat, $t) + strlen($t));
    }

    public function after_last($t, $inthat)
    {
        if (!is_bool($this->strrevpos($inthat, $t))) {
            return substr($inthat, $this->strrevpos($inthat, $t) + strlen($t));
        }
    }

    public function before($t, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $t));
    }

    public function before_last($t, $inthat)
    {
        return substr($inthat, 0, $this->strrevpos($inthat, $t));
    }

    public function between($t, $that, $inthat)
    {
        return $this->before($that, $this->after($t, $inthat));
    }

    public function between_last($t, $that, $inthat)
    {
        return $this->after_last($t, $this->before_last($that, $inthat));
    }
//**********************************************************************************************


//************************** Function Language ***********************************************
    public function language_App()
    {
        $path = base_path('lang/language.json');
        $data = json_decode(File::get($path), true);
        $lang = $data['language'];
        App::setLocale($lang);
    }

    public function ChangeLanguage($lang)
    {
        $path = base_path('lang/language.json');
        $data = json_decode(File::get($path), true);
        $data['language'] = $lang;
        File::put($path, json_encode($data));
    }

    public function Language()
    {
        $path = public_path('language.json');
        $data = json_decode(File::get($path), true);
        $lang = $data['language'];
        return $this->returnData('language', $lang, '');

    }

    public function getLanguageApplication()
    {
        $path = public_path('language.json');
        $data = json_decode(File::get($path), true);
        $lang = $data['language'];
        return $lang;

    }

//**********************************************************************************************



}
