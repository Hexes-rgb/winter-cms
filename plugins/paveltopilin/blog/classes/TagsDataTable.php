<?php

namespace PavelTopilin\Blog\Classes;

use Winter\User\Models\User;
use PavelTopilin\Blog\Models\Tag;
use Khill\Lavacharts\DataTables\DataTable;

class TagsDataTable
{

    function __construct($filters)
    {
        // $this->authors = User::when(
        //     (empty($filters['authors'])) ? false : $filters['authors'],
        //     function ($query, $authors) {
        //         return $query->select('id', 'name')->whereIn('id', $authors);
        //     }
        // )->get()->loadCount('posts');
        $this->tags = Tag::when(
            (empty($filters['tags'])) ? false : $filters['tags'],
            function ($query, $tags) {
                return $query->select('id', 'name')->whereIn('id', $tags);
            }
        )->get()->loadCount('posts')->toArray();
    }

    public function build()
    {
        // $chartTable  = new DataTable();
        // $chartTable->addStringColumn('Tags');
        // $chartTable->addNumberColumn('Posts count');

        // foreach ($this->tags as $tags) {
        //     $chartTable->addRow([$tags->name, $tags->posts_count]);
        // }
        /* добавляешь данные из $this->data */
        return $this->tags;
    }
}
