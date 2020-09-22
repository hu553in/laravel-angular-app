<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicTransport\AddRequest;
use App\Http\Requests\PublicTransport\UpdateRequest;
use App\Models\PublicTransport;
use App\Services\PublicTransportService;
use Illuminate\Http\Response;

class PublicTransportController extends Controller
{
    public function getAll(PublicTransportService $service)
    {
        return response()->common(Response::HTTP_OK, $service->getAll());
    }

    public function get(PublicTransport $publicTransport)
    {
        return response()->common(Response::HTTP_OK, $publicTransport);
    }

    public function add(AddRequest $request, PublicTransportService $service)
    {
        $publicTransport = $service->add($request->all());
        $headers = ['Location' => "/public_transport/{$publicTransport->id}"];
        return response()->common(Response::HTTP_CREATED, $publicTransport, [], $headers);
    }

    public function update(UpdateRequest $request, PublicTransport $publicTransport, PublicTransportService $service)
    {
        return response()->common(
            Response::HTTP_OK,
            $service->update($request->all(), $publicTransport)
        );
    }

    public function delete(PublicTransport $publicTransport, PublicTransportService $service)
    {
        $service->delete($publicTransport);
        return response()->common(Response::HTTP_NO_CONTENT);
    }
}
