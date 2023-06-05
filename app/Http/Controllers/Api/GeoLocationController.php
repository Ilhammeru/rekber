<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;

class GeoLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show all available country
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountries()
    {
        $query = Country::query();
        $query->select('id', 'code', 'name');
        if (request()->name) {
            $query->where('name', 'LIKE', '%'. request()->name .'%');
        }

        $data = $query->get();

        return $this->success('Success get country', $data);
    }

    /**
     * Show all available provinces
     *
     * @return \Illuminate\Http\Response
     */
    public function getProvinces()
    {
        $query = Province::query();
        $query->with('country:id,name,code');
        if (request()->country_id) {
            $query->where('country_id', request()->country_id);
        }

        if (request()->name) {
            $query->where('name', 'LIKE', '%'. request()->name .'%');
        }

        $data = $query->get();

        return $this->success('Success get provinces', $data);
    }

    /**
     * Show all available regencies
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegencies()
    {
        $query = Regency::query();
        $query->with(['province', 'province.country:id,name,code']);
        if (request()->province_id) {
            $query->where('province_id', request()->province_id);
        }

        if (request()->name) {
            $query->where('name', 'LIKE', '%'. request()->name .'%');
        }

        $data = $query->get();

        return $this->success('Success get regencies', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
