<?php

namespace App\Repositories;

use App\Item;
use Illuminate\Database\Eloquent\Collection;

class EloquentItemRepository implements ItemRepository
{
    public function search(string $query = ""): Collection
    {
        return Item::where('description', 'like', "%{$query}%")
            ->orWhere('position_comment', 'like', "%{$query}%")
            ->get();
    }
}