<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardDifficultyColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('card_very_easy_days')->default(15);
            $table->integer('card_easy_days')->default(10);
            $table->integer('card_medium_days')->default(7);
            $table->integer('card_hard_days')->default(3);
            $table->integer('card_very_hard_days')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'card_very_easy_days',
                'card_easy_days',
                'card_medium_days',
                'card_hard_days',
                'card_very_hard_days',
            ]);
        });
    }
}
