<?php

namespace App\Http\Requests\PublicTransport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddPublicTransportRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::in(config('constants.public_transport_types'))],
            'route_number' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'gte:1', 'lte:32767'],
            'organization_name' => ['required', 'string', 'max:255'],
        ];
    }
}
