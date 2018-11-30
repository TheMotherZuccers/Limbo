<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface ItemRepository
{
    public function search(string $query = ""): Collection;
}