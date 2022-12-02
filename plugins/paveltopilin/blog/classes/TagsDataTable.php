<?php

namespace PavelTopilin\Blog\Classes;

use PavelTopilin\Blog\Models\Tag;

class TagsDataTable
{

    function __construct($filters)
    {
        $this->tags = Tag::when(
            (empty($filters['tags'])) ? false : $filters['tags'],
            function ($query, $tags) {
                return $query->select('id', 'name')->whereIn('id', $tags);
            }
        )->get()->loadCount(['posts' => function ($query) use ($filters) {
            return $query->postFilters($filters);
        }])->toArray();
    }

    public function build()
    {
        return $this->tags;
    }
}
