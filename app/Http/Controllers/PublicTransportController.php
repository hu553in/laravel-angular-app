<?php

namespace App\Http\Controllers;

use App\Models\PublicTransport;
use Illuminate\Http\Request;

class PublicTransportController extends Controller
{
    public function getAll()
    {
        return response()->common(true, 200, PublicTransport::all());
    }

    public function get(PublicTransport $publicTransport)
    {
        return response()->common(true, 200, $publicTransport);
    }

    public function add(Request $request)
    {
        $publicTransport = PublicTransport::create($request->all());
        return response()->common(true, 201, $publicTransport);
    }

    public function update(Request $request, PublicTransport $publicTransport)
    {
        $publicTransport->update($request->all());
        return response()->common(true, 200, $publicTransport);
    }

    public function delete(PublicTransport $publicTransport)
    {
        $publicTransport->delete();
        return response()->common(true, 204);
    }
}
