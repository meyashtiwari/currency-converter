<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyTables extends Migration
{
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->char('symbol', 3);
            $table->timestamps();

            $table->unique('symbol');
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('currency_id')->unsigned()->index();
            $table->date('date');
            $table->decimal('rate', 16, 4, true);
            $table->timestamps();

            $table->unique(['currency_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency_rates');
        Schema::dropIfExists('currencies');
    }
}
