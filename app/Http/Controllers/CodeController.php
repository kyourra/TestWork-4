<?php


namespace App\Http\Controllers;

use App\Http\Requests\SendCodeRequest;
use App\Http\Requests\CheckCodeRequest;
use App\Mail\sendCodeMail;
use App\Models\CheckCode;
use App\Repository\CheckCodeRepository;
use Illuminate\Support\Facades\Mail;


// Управляющий контроллер
class CodeController
{

    //ОТ себя: по хорошему все это делается через редис с переменным по времени жизни, но так как редис у меня временно лежит( на локали. Делаю через БД

    public function sendCode(SendCodeRequest $request ) {

        $email= $request->input('email');
        $report= (new ValidateCodeController($email))->validateSendCode();

        if (!$report) {
            $code=$this->genCode();
            (new RepositoryCodeController)->createCode($email,$code);

            Mail::to($email)->send(new sendCodeMail($code,$email));
            return response()->json(['status'=>'success']);
        } else return response()->json($report);

    }

    public function checkCode(CheckCodeRequest $request){
        $email= $request->input('email');
        $code= $request->input('code');

        $report= (new ValidateCodeController($email,$code))->validateCheckCode();

        if (!$report) {
            return response()->json(['status'=>'success']);
        } else return response()->json($report);
    }

    // Простая генерация кода
    private function genCode () {
        return rand(1000, 9999);
    }





}

