<?php

namespace App\Http\Requests\PublicTransport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetAllPublicTransportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sort_by' => ['string', Rule::in([
                'id', 'type', 'route_number', 'capacity', 'organization_name', 'created_at', 'updated_at',
            ])],
            'order' => ['string', Rule::in(['asc', 'desc'])],
            'page' => ['integer', 'gte:1'],
            'rows' => ['integer', 'gte:1'],
            'type.*' => ['string', Rule::in(config('constants.public_transport_types'))],
            'organization_name.*' => ['string'],
        ];
    }
}
