<?php

namespace App\Services;

use App\Http\Requests\PublicTransport\GetAllPublicTransportRequest;
use App\Models\PublicTransport;
use Illuminate\Support\Facades\DB;

class PublicTransportService
{
    /**
     * Get all public transport.
     *
     * @param  array  $types
     * @param  array  $organizationNames
     * @param  array  $sortBy
     * @param  string  $order
     * @param  int  $page
     * @param  int  $rows
     * @return \Illuminate\Support\Collection
     */
    public function getAll(array $types, array $organizationNames, array $sortingParams, int $page, int $rows)
    {
        $query = DB::table('public_transport');
        if (!empty($types)) {
            $query->whereIn('type', $types);
        }
        if (!empty($organizationNames)) {
            $query->whereIn('organization_name', $organizationNames);
        }
        foreach ($sortingParams as $sortBy => $order) {
            $query->orderBy($sortBy, $order);
        }
        return $query
            ->skip(($page - 1) * $rows)
            ->take($rows)
            ->get();
    }

    /**
     * Count all public transport.
     *
     * @param  array  $types
     * @param  array  $organizationNames
     * @return int
     */
    public function countAll(array $types, array $organizationNames)
    {
        $query = DB::table('public_transport');
        if (!empty($types)) {
            $query->whereIn('type', $types);
        }
        if (!empty($organizationNames)) {
            $query->whereIn('organization_name', $organizationNames);
        }
        return $query->count();
    }

    /**
     * Add public transport.
     *
     * @param  array  $data
     * @return mixed
     */
    public function add(array $data)
    {
        return PublicTransport::create($data);
    }

    /**
     * Update public transport by ID.
     *
     * @param  array  $data
     * @param  \App\Models\PublicTransport  $publicTransport
     * @return \App\Models\PublicTransport
     */
    public function update(array $data, PublicTransport $publicTransport)
    {
        $publicTransport->update($data);
        if (count($publicTransport->getChanges()) > 0) {
            $publicTransport->touch();
        }
        return $publicTransport;
    }

    /**
     * Delete public transport by ID.
     *
     * @param  \App\Models\PublicTransport  $publicTransport
     * @return void
     */
    public function delete(PublicTransport $publicTransport)
    {
        $publicTransport->delete();
    }
}
