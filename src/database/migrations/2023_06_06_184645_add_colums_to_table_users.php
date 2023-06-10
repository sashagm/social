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

            $userAvatarField = config('socials.user_avatar');

            if (!Schema::hasColumn(config('socials.user_table'), $userAvatarField)) {
                Schema::table(config('socials.user_table'), function (Blueprint $table) use ($userAvatarField) {
                    $table->text($userAvatarField)->nullable();
                });
            }
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

        $userAvatarField = config('socials.user_avatar');

        if (Schema::hasColumn(config('socials.user_table'), $userAvatarField)) {
            Schema::table(config('socials.user_table'), function (Blueprint $table) use ($userAvatarField) {
                $table->dropColumn($userAvatarField);
            });
        }
    }
};
