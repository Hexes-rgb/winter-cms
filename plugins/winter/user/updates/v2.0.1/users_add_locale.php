<?php

namespace Winter\User\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class UsersAddLocale extends Migration
{
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('locale')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('locale');
        });
    }
}
