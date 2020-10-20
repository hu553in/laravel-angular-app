<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class OrganizationNameService
{
    /**
     * Get all organization names.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        $mappingCallback = function(object $element) {
            return $element->organization_name;
        };
        $organizationNames = DB::table('public_transport')
            ->select('organization_name')
            ->distinct()
            ->orderBy('organization_name')
            ->get()
            ->toArray();
        return array_map($mappingCallback, $organizationNames);
    }
}
