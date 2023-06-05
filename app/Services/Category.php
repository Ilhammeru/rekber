<?php

namespace App\Services;

use App\Models\Category as ModelsCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class Category {
    public function model(): Model
    {
        return new ModelsCategory();
    }

    public function datatable()
    {
        $data = ModelsCategory::latest()
            ->get();

        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                $color = $data->status ? 'bg-success' : 'bg-danger';
                $text = $data->status ? 'Active' : 'Disable';

                return '<span class="badge '. $color .'">'. $text .'</span>';
            })
            ->addColumn('action', function ($data) {
                $text = __('global.deactivate');
                $confirm = __('global.confirm_deactivate_category');
                $changeStatus = ModelsCategory::INACTIVE;
                if (!$data->status) {
                    $text = __('global.activate');
                    $confirm = __('global.confirm_activate_category');
                    $changeStatus = ModelsCategory::ACTIVE;
                }
                return '
                    <button class="btn btn-primary btn-sm cursor-pointer"
                        type="button"
                        onclick="openGlobalModal(`'. route('categories.edit', $data->id) .'`, `'. __('global.update_category') .'`, {footer: true, target: `target-category-action`})">
                        <i class="fa fa-pen"></i> Edit
                    </button>
                    <button class="btn btn-warning btn-sm"
                        type="button"
                        onclick="showConfirm(`'. $text . ' ' . __('global.category') .'`, `'. $confirm .'`, `'. __('global.yes') .'`, `'. __('global.no') .'`, `updateStatus`, {id: `'. $data->id .'`, status: '. $changeStatus .'})">
                    '. $text .'
                    </button>
                    <button class="btn btn-danger btn-sm cursor-pointer"
                        type="button"
                        onclick="showConfirm(`'. __('global.delete') . ' ' . __('global.category') .'`, `'. __('global.confirm_delete') .'`, null, null, `deleteItem`, {id: `'. $data->id .'`})">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $store = $this->model();
        $store->name = $request->name;
        $store->save();

        return [
            'message' => __('global.success_store_category'),
            'data' => $store->only('name'),
            'status' => 200,
        ];
    }

    public function update(Request $request, $id)
    {
        $data = $this->model()->find($id);
        $data->name = $request->name;
        $data->save();

        return [
            'message' => __('global.success_update_category'),
            'data' => $data->only('name'),
            'status' => 200,
        ];
    }

    public function changeStatus($id, $status)
    {
        $category = $this->model()->find($id);
        $category->status = $status;
        $category->save();

        return [
            'message' => __('global.success_update_category'),
            'data' => [],
            'status' => 200,
        ];
    }
}
