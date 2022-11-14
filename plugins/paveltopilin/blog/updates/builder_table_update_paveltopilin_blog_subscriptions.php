<?php namespace PavelTopilin\Blog\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableUpdatePaveltopilinBlogSubscriptions extends Migration
{
    public function up()
    {
        Schema::table('paveltopilin_blog_subscriptions', function($table)
        {
            $table->primary(['author_id','sub_id']);
        });
    }
    
    public function down()
    {
        Schema::table('paveltopilin_blog_subscriptions', function($table)
        {
            $table->dropPrimary(['author_id','sub_id']);
        });
    }
}
