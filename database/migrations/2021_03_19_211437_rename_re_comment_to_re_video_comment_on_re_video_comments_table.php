<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameReCommentToReVideoCommentOnReVideoCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('re_video_comments', function (Blueprint $table) {
            $table->renameColumn('re_comment', 're_video_comment');
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
            $table->renameColumn('re_video_comment', 're_comment');
        });
    }
}
