<?php

namespace PavelTopilin\Blog\Classes;

use Winter\User\Models\User;
use PavelTopilin\Blog\Models\Tag;
use Khill\Lavacharts\DataTables\DataTable;

class AuthorsDataTable
{

    function __construct($filters)
    {
        $this->authors = User::when(
            (empty($filters['authors'])) ? false : $filters['authors'],
            function ($query, $authors) {
                return $query->select('id', 'name')->whereIn('id', $authors);
            }
        )->get()->loadCount('posts')->toArray();
        // $this->tags = Tag::when(
        //     (empty($filters['tags'])) ? false : $filters['tags'],
        //     function ($query, $tags) {
        //         return $query->select('id', 'name')->whereIn('id', $tags);
        //     }
        // )->get()->loadCount('posts');
    }

    public function build()
    {
        // $chartTable  = new DataTable();
        // $chartTable->addStringColumn('Authors');
        // $chartTable->addNumberColumn('Posts count');

        // /* добавляешь данные из $this->data */
        // foreach ($this->authors as $author) {
        //     $chartTable->addRow([$author->name, $author->posts_count]);
        // }

        return $this->authors;
    }
}
