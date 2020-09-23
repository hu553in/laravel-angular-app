<?php

namespace App\Services;

use App\Models\PublicTransport;

class PublicTransportService
{
    /**
     * Get all public transport.
     *
     * @return \Illuminate\Database\Eloquent\Collection<mixed, \App\Models\PublicTransport>
     */
    public function getAll()
    {
        return PublicTransport::all();
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
