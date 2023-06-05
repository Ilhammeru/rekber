<?php

namespace App\Http\Controllers;

use App\Models\Category as ModelsCategory;
use App\Services\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Categories';
        return view('categories.index', compact('pageTitle'));
    }

    public function ajax(Category $service)
    {
        return $service->datatable();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->success('success', ['view' => view('categories.update_or_create')->render()]);
    }

    /**
     * Update category status
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Category $service, $id, $status)
    {
        $save = $service->changeStatus($id, $status);
        return $this->success($save['message'], $save['data']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $service)
    {
        $store = $service->store($request);
        return $this->success($store['message'], $store['data']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ModelsCategory $category)
    {
        return $this->success('success', ['view' => view('categories.update_or_create', compact('category'))->render()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $service, $id)
    {
        $store = $service->update($request, $id);
        return $this->success($store['message'], $store['data']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelsCategory $category)
    {
        $category->delete();

        return $this->success(__('global.success_delete_item'));
    }
}
