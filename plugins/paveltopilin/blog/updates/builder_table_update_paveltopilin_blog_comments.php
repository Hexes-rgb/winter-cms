<?php namespace PavelTopilin\Blog\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdatePaveltopilinBlogComments extends Migration
{
    public function up()
    {
        Schema::table('paveltopilin_blog_comments', function($table)
        {
            $table->integer('user_id');
        });
    }
    
    public function down()
    {
        Schema::table('paveltopilin_blog_comments', function($table)
        {
            $table->dropColumn('user_id');
        });
    }
}
