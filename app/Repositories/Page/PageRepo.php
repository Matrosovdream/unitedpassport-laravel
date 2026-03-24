<?php

namespace App\Repositories\Page;

use App\Models\Page;
use App\Repositories\AbstractRepo;

class PageRepo extends AbstractRepo
{
    protected $withRelations = ['author', 'parent', 'children'];

    public function __construct()
    {
        $this->model = new Page();
    }

    public function mapItem($item)
    {
        if (empty($item)) {
            return null;
        }

        return [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'content' => $item->content,
            'excerpt' => $item->excerpt,
            'meta_title' => $item->meta_title,
            'meta_description' => $item->meta_description,
            'status' => $item->status,
            'template' => $item->template,
            'parent_id' => $item->parent_id,
            'author_id' => $item->author_id,
            'sort_order' => $item->sort_order,
            'published_at' => $item->published_at,
            'Model' => $item,
        ];
    }
}
