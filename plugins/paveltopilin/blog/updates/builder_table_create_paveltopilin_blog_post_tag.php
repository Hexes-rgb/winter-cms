<?php namespace PavelTopilin\Blog\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreatePaveltopilinBlogPostTag extends Migration
{
    public function up()
    {
        Schema::create('paveltopilin_blog_post_tag', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('post_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->primary(['post_id','tag_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('paveltopilin_blog_post_tag');
    }
}
