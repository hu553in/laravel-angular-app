<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicTransport\AddPublicTransportRequest;
use App\Http\Requests\PublicTransport\UpdatePublicTransportRequest;
use App\Models\PublicTransport;
use App\Services\PublicTransportService;
use Illuminate\Http\Response;

class PublicTransportController extends Controller
{
    /**
     * Get all public transport.
     *
     * @param  \App\Services\PublicTransportService  $service
     * @return \Illuminate\Http\Response
     */
    public function getAll(PublicTransportService $service)
    {
        return response()->common(Response::HTTP_OK, $service->getAll());
    }

    /**
     * Get public transport by ID.
     *
     * @param  \App\Models\PublicTransport  $publicTransport
     * @return \Illuminate\Http\Response
     */
    public function get(PublicTransport $publicTransport)
    {
        return response()->common(Response::HTTP_OK, $publicTransport);
    }

    /**
     * Add public transport.
     *
     * @param  \App\Http\Requests\PublicTransport\AddPublicTransportRequest  $request
     * @param  \App\Services\PublicTransportService  $service
     * @return \Illuminate\Http\Response
     */
    public function add(AddPublicTransportRequest $request, PublicTransportService $service)
    {
        $publicTransport = $service->add($request->all());
        $headers = ['Location' => "/public_transport/{$publicTransport->id}"];
        return response()->common(Response::HTTP_CREATED, $publicTransport, [], $headers);
    }

    /**
     * Update public transport by ID.
     *
     * @param  \App\Http\Requests\PublicTransport\UpdatePublicTransportRequest  $request
     * @param  \App\Models\PublicTransport  $publicTransport
     * @param  \App\Services\PublicTransportService  $service
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdatePublicTransportRequest $request,
        PublicTransport $publicTransport,
        PublicTransportService $service
    ) {
        return response()->common(
            Response::HTTP_OK,
            $service->update($request->all(), $publicTransport)
        );
    }

    /**
     * Delete public transport by ID.
     *
     * @param  \App\Models\PublicTransport  $publicTransport
     * @param  \App\Services\PublicTransportService  $service
     * @return \Illuminate\Http\Response
     */
    public function delete(PublicTransport $publicTransport, PublicTransportService $service)
    {
        $service->delete($publicTransport);
        return response()->common(Response::HTTP_NO_CONTENT);
    }
}
