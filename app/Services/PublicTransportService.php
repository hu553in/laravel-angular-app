<?php

namespace App\Services;

use App\Models\PublicTransport;

class PublicTransportService
{
    public function getAll()
    {
        return PublicTransport::all();
    }

    public function add(array $data)
    {
        return PublicTransport::create($data);
    }

    public function update(array $data, PublicTransport $publicTransport)
    {
        $publicTransport->update($data);
        if (count($publicTransport->getChanges()) > 0) {
            $publicTransport->touch();
        }
        return $publicTransport;
    }

    public function delete(PublicTransport $publicTransport)
    {
        $publicTransport->delete();
    }
}
