<?php

namespace App\Http\Requests\PublicTransport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePublicTransportRequest extends FormRequest
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
            'type' => ['string', Rule::in(config('constants.public_transport_types'))],
            'route_number' => ['string'],
            'capacity' => ['integer', 'gte:1'],
            'organization_name' => ['string'],
        ];
    }
}
