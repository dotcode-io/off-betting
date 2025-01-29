<?php

declare(strict_types=1);

namespace App\Traits\Table;

trait Searchable
{
    public $search = '';

    public function updatedSearchable($property)
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }

    protected function applySearch($query, $columns = [])
    {

        if ($this->search === '' || empty($columns)) {
            return $query;
        }

        return $query->where(function ($query) use ($columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', '%'.$this->search.'%');
            }
        });
    }
}
