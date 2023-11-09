<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';
    protected $table = 'employer_accounts';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username', 255)->unique();
            $table->string('password', 255);
            $table->boolean('is_banned')->default(0);
            $table->dateTime('locked_until')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employer_accounts');
    }
};
