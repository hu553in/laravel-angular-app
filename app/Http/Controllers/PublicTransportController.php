<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicTransport\AddPublicTransportRequest;
use App\Http\Requests\PublicTransport\GetAllPublicTransportRequest;
use App\Http\Requests\PublicTransport\UpdatePublicTransportRequest;
use App\Models\PublicTransport;
use App\Services\PublicTransportService;
use Illuminate\Http\Response;

class PublicTransportController extends Controller
{
    /**
     * Get all public transport.
     *
     * @param  \App\Http\Requests\PublicTransport\GetAllPublicTransportRequest  $request
     * @param  \App\Services\PublicTransportService  $service
     * @return \Illuminate\Http\Response
     */
    public function getAll(GetAllPublicTransportRequest $request, PublicTransportService $service)
    {
        $defaultPaginationParams = config('constants.default_pagination_params');
        $defaultSortingParams = config('constants.default_sorting_params');
        $sortBy = $request['sort_by'] ?? $defaultSortingParams['sort_by'];
        $order = $request['order'] ?? $defaultSortingParams['order'];
        $rows = isset($request['rows'])
            ? intval($request['rows'])
            : $defaultPaginationParams['rows'];
            $types = isset($request['type'])
                ? array_filter($request['type'])
                : [];
            $organizationNames = isset($request['organization_name'])
                ? array_filter($request['organization_name'])
                : [];
            $metaTotal = $service->countAll($types, $organizationNames);
        $metaLast = intval(ceil(floatval($metaTotal) / $rows));
        if (isset($request['page'])) {
            $rawPage = intval($request['page']);
            $page = $rawPage <= $metaLast
                ? $rawPage
                : $metaLast;
        } else {
            $page = $defaultPaginationParams['page'];
        }
        $paginatedData = $service->getAll($types, $organizationNames, $sortBy, $order, $page, $rows);
        $metaNext = $page < $metaLast
            ? $page + 1
            : $metaLast;
        $metaPrev = $page > 1
            ? $page - 1
            : 1;
        $metaSortBy = "&sort_by={$sortBy}";
        $metaOrder = "&order={$order}";
        $metaType = array_reduce($types, function ($carry, $item) {
            return $carry . "&type[]={$item}";
        }, '');
        $metaOrganizationName = array_reduce($organizationNames, function ($carry, $item) {
            return $carry . "&organization_name[]=" . urlencode($item);
        }, '');
        $meta = [
            'total' => $metaTotal,
            'total_pages' => $metaLast,
            'rows' => $rows,
        ];
        if ($metaLast > 0) {
            $meta['page'] = $page;
            $meta['first'] = "/public_transport?page=1&rows={$rows}{$metaSortBy}" .
                "{$metaOrder}{$metaType}{$metaOrganizationName}";
            $meta['last'] = "/public_transport?page={$metaLast}&rows={$rows}{$metaSortBy}" .
                "{$metaOrder}{$metaType}{$metaOrganizationName}";
        }
        if ($page < $metaLast) {
            $meta['next'] = "/public_transport?page={$metaNext}&rows={$rows}{$metaSortBy}" .
                "{$metaOrder}{$metaType}{$metaOrganizationName}";
        }
        if ($page > 1) {
            $meta['prev'] = "/public_transport?page={$metaPrev}&rows={$rows}{$metaSortBy}" .
                "{$metaOrder}{$metaType}{$metaOrganizationName}";
        }
        return response()->common(Response::HTTP_OK, [
            '_meta' => $meta,
            'paginated_data' => $paginatedData,
        ]);
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
