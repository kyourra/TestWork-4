<?php


namespace App\Http\Controllers;


use App\Models\CheckCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


// Класс для обработки ошибок и действия на ними

//TODO  Обновлено: Как улучшить этот класс - перенести "действия" в отдельный класс по номеру ошибки

class ValidateCodeController
{


    protected $email;
    protected $code;

    protected $lastMCode;


    function __construct($email, $code=null)
    {
      $this->email=$email;
      $this->code=$code;
    }

    // для групповой проверки условий создания кода
    public function validateSendCode() {
        if (!$this->validateHourSend ())
            return ['status'=>'error','message'=>'Код не может отправляться на один и тот-же email более 5 раз в течение 1 часа;'];
        if ($this->validate5min())
            return  ['status'=>'error','message'=>'Код не может отправляться на один и тот-же email более 1 раза в 5 минут;'];
        return false;
    }

    // для групповой проверки условий кода
    public function validateCheckCode() {

        if (!$this->validateCode ())
            return ['status'=>'error','message'=>'По такому email Кода не существует;'];

        if (!$this->validateCodeNumberCheck ())
            return ['status'=>'error','message'=>'Количество попыток превысило 3 - код больше не действителен;'];

        if (!$this->validateCodeToken ())
            return ['status'=>'error','message'=>'Код не совпадает;'];

        if (!$this->validateCodeTokenTime ())
            return ['status'=>'error','message'=>'Врем жизни Кода истекло;'];

        return false;
    }



    // Код не может отправляться на один и тот-же email более 5 раз в течение 1 часа;
    private function validateHourSend() {
        $data= CheckCode::where('email',$this->email)
            ->where('created_at','>',Carbon::now()->subHour())
            ->get();

        if (count($data)<5) return true;
            else return false;
    }

    // Код отправлялся за последние 5 минут.
    private function validate5min() {

        $data= CheckCode::where('email',$this->email)
            ->where('created_at','>',Carbon::now()->subMinutes(5))
            ->get();

        if (count($data)>0) return true;
    }


    private function validateCode(){
        $this->lastMCode= CheckCode::where('email',$this->email)
                ->orderBy('updated_at', 'desc')->first();
        // если нет кода
        if (empty($this->lastMCode)) return false;

     return true;
    }

    private function validateCodeToken()
    {
        if (Hash::check($this->code, $this->lastMCode->token))  {
            (new RepositoryCodeController)->delCode($this->email);
            return true;
        }
        else {
            (new RepositoryCodeController)->inscrCode($this->email);
            false;
        };
    }

    private function validateCodeNumberCheck()
    {
        if ($this->lastMCode->numberCheck<3)  return true;
        else
        {
            (new RepositoryCodeController)->invalCode($this->email);
            false;
        }
    }

    private function validateCodeTokenTime()
    {
        if ($this->lastMCode->updated_at<=Carbon::now()->subMinutes(5)) {
            (new RepositoryCodeController)->invalCode($this->email);
            return false;
        }
        else return true;
    }



}
