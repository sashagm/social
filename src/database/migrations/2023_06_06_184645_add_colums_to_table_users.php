<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable(config('socials.user.table'))) {
            Schema::table(config('socials.user.table'), function (Blueprint $table) {
                $table->string('provider')->nullable()->after(config('socials.user.table_after'));
                $table->string('provider_id')->nullable()->after('provider');
                $table->tinyInteger(config('socials.user.update_colum'))->default(0);
            });

            $userAvatarField = config('socials.user.avatar');

            if (!Schema::hasColumn(config('socials.user.table'), $userAvatarField)) {
                Schema::table(config('socials.user.table'), function (Blueprint $table) use ($userAvatarField) {
                    $table->text($userAvatarField)->nullable()->after('provider_id');
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
        Schema::table(config('socials.user.table'), function (Blueprint $table) {
            $table->dropColumn('provider');
            $table->dropColumn('provider_id');
            $table->dropColumn(config('socials.user.update_colum'));
        });

        $userAvatarField = config('socials.user.avatar');

        if (Schema::hasColumn(config('socials.user.table'), $userAvatarField)) {
            Schema::table(config('socials.user.table'), function (Blueprint $table) use ($userAvatarField) {
                $table->dropColumn($userAvatarField);
            });
        }
    }
};
