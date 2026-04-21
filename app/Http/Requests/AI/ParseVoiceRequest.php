<?php

namespace App\Http\Requests\AI;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ParseVoiceRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'audio' => [
                'required',
                'file',
                'mimetypes:audio/mpeg,audio/wav,audio/mp4,audio/m4a,audio/x-m4a,audio/ogg,audio/webm,video/mp4',
                'max:20480',
            ],
        ];
    }
}
