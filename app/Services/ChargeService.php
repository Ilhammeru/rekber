<?php

namespace App\Services;

use App\Models\Charge;
use Illuminate\Database\Eloquent\Model;

class ChargeService extends Service
{

    public function model(): Model
    {
        // Object Model
        return new Charge();
    }

    public function datatable($request)
    {

    }

    public function all()
    {

    }

    public function show($id)
    {

    }

    public function update($request, $id)
    {

    }

    public function store($request, $additional = null)
    {
        $this->model()->create([
            'trx_id' => $request->trx_id,
            'fixed_charge' => $request->fixed_charge,
            'percent_charge' => $request->percent_charge,
            'total_charge' => $request->charge_total,
            'payment_gateaway_id' => base64url_decode($request->payment),
        ]);
    }

    public function destroy($id)
    {

    }
}
