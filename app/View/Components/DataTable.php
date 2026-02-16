<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTable extends Component
{
    public $tableData;
    public $model;
    public $idField;
    public $editRoute;
    public $deleteRoute;
    public $createRoute;
    public $title;

    public function __construct($tableData = [], $model = null, $idField = 'id', $editRoute = null, $deleteRoute = null, $createRoute = null, $title = 'Data Table')
    {
        $this->tableData = $tableData;
        $this->model = $model;
        $this->idField = $idField;
        $this->editRoute = $editRoute;
        $this->deleteRoute = $deleteRoute;
        $this->createRoute = $createRoute;
        $this->title = $title;
    }

    public function render()
    {
        return view('components.data-table');
    }
}
