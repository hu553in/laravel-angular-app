<?php

namespace App\Http\Controllers;

use App\Models\PublicTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $publicTransportTypes = config('constants.public_transport_types');
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', Rule::in($publicTransportTypes)],
            'route_number' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'gte:1'],
            'organization_name' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->common(400, null, $validator->errors()->all());
        }
        $publicTransport = PublicTransport::create($request->all());
        $headers = [
            'Location' => "/public_transport/{$publicTransport->id}",
        ];
        return response()->common(201, $publicTransport, [], $headers);
    }

    public function update(Request $request, PublicTransport $publicTransport)
    {
        $publicTransportTypes = config('constants.public_transport_types');
        $validator = Validator::make($request->all(), [
            'type' => ['string', Rule::in($publicTransportTypes)],
            'route_number' => ['string'],
            'capacity' => ['integer', 'gte:1'],
            'organization_name' => ['string'],
        ]);
        if ($validator->fails()) {
            return response()->common(400, null, $validator->errors()->all());
        }
        $publicTransport->update($request->all());
        if (count($publicTransport->getChanges()) > 0) {
            $publicTransport->touch();
        }
        return response()->common(200, $publicTransport);
    }

    public function delete(PublicTransport $publicTransport)
    {
        $publicTransport->delete();
        return response()->common(204);
    }
}
