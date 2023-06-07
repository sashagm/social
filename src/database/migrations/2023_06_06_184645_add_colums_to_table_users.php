<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        if (Schema::hasTable(config('socials.user_table'))) {
            
           
                Schema::table(config('socials.user_table'), function (Blueprint $table) {
                    $table->string('provider')->nullable();
                    $table->string('provider_id')->nullable();
                });
                      


        }
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('socials.user_table'), function (Blueprint $table) {
            $table->dropColumn('provider');
            $table->dropColumn('provider_id');

        });
    }
};
