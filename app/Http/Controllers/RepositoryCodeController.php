<?php


namespace App\Http\Controllers;

use App\Models\CheckCode;
use Illuminate\Support\Facades\Hash;


// Класс для работы с таблицой в БД
class RepositoryCodeController
{

    public function createCode($email,$token) {
        CheckCode::create(['email' => $email,'token'=>Hash::make($token)]);
        return true;
    }

    // $ Инвалидируем все
    public function delCode ($email) {
        CheckCode::where('email', $email)->delete();
        return true;
    }

    // Количесвто проверок кода
    public function inscrCode($email) {
        $data = CheckCode::where('email',$email)
            ->orderBy('updated_at', 'desc')->first();

        if (!empty($data)) {
            $data->numberCheck++;
            $data->save();
        }
        return true;
    }

    // Инвалидируем
    public function invalCode($email) {
        $data = CheckCode::where('email',$email)
            ->orderBy('updated_at', 'desc')->first();

        if (!empty($data)) {
            $data->token='';
            $data->save();
        }
        return true;
    }

}
