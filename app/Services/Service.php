<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class Service
{
    public abstract function model(): Model;

    public function modelQuery()
    {
        return $this->model()->query();
    }

    public function table()
    {
        return $this->model()->getTable();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->modelQuery()->find($id, $columns);
    }

    public function store($data, $additional = null)
    {
        return $this->modelQuery()->create($data);
    }

    public function update($model, $data)
    {
        if (!$model instanceof Model) {
            $model = $this->modelQuery()->findOrFail($model);
        }

        $model->update($data);
        $model->refresh();

        return $model;
    }

    public function delete($model, $force = false)
    {
        if (!$model instanceof Model) {
            $model = $this->modelQuery()->findOrFail($model);
        }

        if ($force) {
            return $model->forceDelete();
        }

        return $model->delete();
    }
}
