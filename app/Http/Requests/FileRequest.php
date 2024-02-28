<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'file' => 'required|max:5120|mimes:doc,docx,txt,pdf,pptx',//file size upto 5MB
            'type' => 'required|string',
            'groups_ids' => 'required|array',
            'groups.*' => 'required|exists:groups,id'
        ];
    }
}
