<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';
    protected $table = 'employer_profiles';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer_profiles', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Define foreign keys
            $table->foreignId('id')->constrained('employer_accounts', 'id')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('company_accounts', 'id')->onDelete('cascade');

            $table->string('full_name', 500);
            $table->string('avatar', 2000)->default(env('DEFAULT_LOGO_URL', base_path('public/default_logo.png')));
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
        Schema::dropIfExists('employer_profiles');
    }
};
