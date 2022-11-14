<?php

namespace PavelTopilin\Blog\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use PavelTopilin\Blog\Models\Tag;

class Posts extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
        \Backend\Behaviors\RelationController::class
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'paveltopilin.blog.manage_posts'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('PavelTopilin.Blog', 'blog', 'posts');
    }
}
