<?php namespace PavelTopilin\Blog\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreatePaveltopilinBlogViews extends Migration
{
    public function up()
    {
        Schema::create('paveltopilin_blog_views', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id');
            $table->integer('user_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('paveltopilin_blog_views');
    }
}
