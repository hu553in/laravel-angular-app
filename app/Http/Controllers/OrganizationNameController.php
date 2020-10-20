<?php

namespace App\Http\Controllers;

use App\Services\OrganizationNameService;
use Illuminate\Http\Response;

class OrganizationNameController extends Controller
{
    /**
     * Get all organization names.
     *
     * @param  \App\Services\OrganizationNameService  $service
     * @return \Illuminate\Http\Response
     */
    public function getAll(OrganizationNameService $service)
    {
        return response()->common(Response::HTTP_OK, $service->getAll());
    }
}
