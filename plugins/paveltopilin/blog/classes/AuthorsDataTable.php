<?php

namespace PavelTopilin\Blog\Classes;

use Winter\User\Models\User;

class AuthorsDataTable
{

    function __construct($filters)
    {
        $this->authors = User::when(
            (empty($filters['authors'])) ? false : $filters['authors'],
            function ($query, $authors) {
                return $query->select('id', 'name')->whereIn('id', $authors);
            }
        )->get()->loadCount(['posts' => function ($query) use ($filters) {
            return $query->postFilters($filters);
        }])->toArray();
    }

    public function build()
    {
        return $this->authors;
    }
}
