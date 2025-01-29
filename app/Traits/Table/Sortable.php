<?php

declare(strict_types=1);

namespace App\Traits\Table;

use Livewire\Attributes\Url;

trait Sortable
{
    #[Url]
    public $sortCol;

    #[Url]
    public $sortAsc = false;

    public function sortBy($column)
    {
        if ($this->sortCol === $column) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortCol = $column;
            $this->sortAsc = false;
        }
    }

    protected function applySorting($query, $default = null)
    {
        if ($this->sortCol) {

            if (method_exists($this, 'getMatches')) {
                $column = $this->getMatches();
                $query->orderBy($column, $this->sortAsc ? 'asc' : 'desc');
            }
        } else {
            if ($default) {
                $query->orderBy($default, 'desc');
            }
        }

        return $query;
    }
}
