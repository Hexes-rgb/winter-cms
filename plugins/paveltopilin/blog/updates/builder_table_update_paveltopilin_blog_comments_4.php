<?php namespace PavelTopilin\Blog\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdatePaveltopilinBlogComments4 extends Migration
{
    public function up()
    {
        Schema::table('paveltopilin_blog_comments', function($table)
        {
            $table->integer('comment_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('paveltopilin_blog_comments', function($table)
        {
            $table->dropColumn('comment_id');
        });
    }
}
