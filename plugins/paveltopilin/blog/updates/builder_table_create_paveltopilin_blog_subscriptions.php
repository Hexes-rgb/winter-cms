<?php namespace PavelTopilin\Blog\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreatePaveltopilinBlogSubscriptions extends Migration
{
    public function up()
    {
        Schema::create('paveltopilin_blog_subscriptions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('author_id');
            $table->integer('sub_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('paveltopilin_blog_subscriptions');
    }
}
