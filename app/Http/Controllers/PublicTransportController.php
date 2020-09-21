<?php

namespace App\Http\Controllers;

use App\Models\PublicTransport;
use Illuminate\Http\Request;

class PublicTransportController extends Controller
{
    public function getAll()
    {
        return response()->common(200, PublicTransport::all());
    }

    public function get(PublicTransport $publicTransport)
    {
        return response()->common(200, $publicTransport);
    }

    public function add(Request $request)
    {
        $publicTransport = PublicTransport::create($request->all());
        $apiLocation = env("API_LOCATION", env("APP_URL", ""));
        $headers = [
            'Location' => "{$apiLocation}/api/public_transport/{$publicTransport->id}",
        ];
        return response()->common(201, $publicTransport, null, $headers);
    }

    public function update(Request $request, PublicTransport $publicTransport)
    {
        $publicTransport->update($request->all());
        return response()->common(200, $publicTransport);
    }

    public function delete(PublicTransport $publicTransport)
    {
        $publicTransport->delete();
        return response()->common(204);
    }
}
