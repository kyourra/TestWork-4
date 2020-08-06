<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_code', function (Blueprint $table) {
            $table->id();
            $table->string('email')->comment('Почта');
            $table->string('token')->comment('Код из 4 символов');
            $table->smallInteger('numberCheck')->default(0)->comment('Количество проверок');
            $table->timestamps(); // время отправки равное время изменения
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_code');
    }
}
