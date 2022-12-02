<?php

namespace PavelTopilin\Blog\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostsExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    public function headings(): array
    {
        return [
            '#',
            'Title',
            'Text',
            'Author',
            'Created at',
            'Updated at',
        ];
    }

    public function query()
    {
        return $this->posts;
    }
}
