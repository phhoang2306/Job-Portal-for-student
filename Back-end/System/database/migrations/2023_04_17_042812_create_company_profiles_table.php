<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';
    protected $table = 'company_profiles';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Define foreign keys
            $table->foreignId('id')->constrained('company_accounts', 'id')->onDelete('cascade');

            $table->string('name', 500);
            $table->string('logo', 2000)->default(env('DEFAULT_LOGO_URL', base_path('public/default_logo.png')));
            $table->string('description', 10000)->nullable();
            $table->string('site', 500)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('size', 100)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_profiles');
    }
};
