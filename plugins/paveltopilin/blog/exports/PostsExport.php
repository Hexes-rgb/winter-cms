<?php

namespace PavelTopilin\Blog\Exports;

use Winter\Storm\Database\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PostsExport implements FromCollection
{
    use Exportable;

    public function __construct(Collection $posts)
    {
        $this->posts = $posts;

        return $this;
    }

    public function collection()
    {
        return $this->posts;
    }
}
