<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCommentToVideoCommentOnVideoCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_comments', function (Blueprint $table) {
            $table->renameColumn('comment', 'video_comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_comments', function (Blueprint $table) {
            $table->renameColumn('video_comment', 'comment');
        });
    }
}
