<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCommentToReCommentOnReVideoCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_video_comments', function (Blueprint $table) {
            $table->renameColumn('comment', 're_comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('re_video_comments', function (Blueprint $table) {
            $table->renameColumn('re_comment', 'comment');
        });
    }
}
