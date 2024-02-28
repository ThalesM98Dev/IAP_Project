<?php

use App\Enum\Rules;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('role')->default(Rules::USER);
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('files_limit')->default(100);
            $table->integer('files_counter')->default(0);
            $table->ipAddress('user_ip')->default(\Illuminate\Support\Facades\Request::ip());
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
