<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,jpg,png',
            'artist' => 'required',
            'producer' => 'required',
            'release_yr' => 'required',
        ];
    }
}



// if(request() -> isMethod('post')){
//     return [
//         'title' => 'required',
//         'artist' => 'required',
//         'img' => 'required',
//         'producer' => 'required',
//         'release_yr' => 'required',
//     ];
// } else {
//     return [
//         'title' => 'required',
//         'artist' => 'required',
//         'img' => 'nullable',
//         'producer' => 'required',
//         'release_yr' => 'required',
//     ];
// }

