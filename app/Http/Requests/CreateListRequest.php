<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\LinkedListType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Since we don't have users implemented in this app yet
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(LinkedListType::class)],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('linked_lists', 'name')
                    ->ignore($this->route('linkedList')?->id)
                    ->where(function ($query) {
                        return $query->where('type', $this->type);
                    }),
            ],
            'description' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A list with this name already exists for the selected type.',
            'name.min' => 'The name must be at least 3 characters.',
            'name.max' => 'The name must not be greater than 100 characters.',
            'description.max' => 'The description must not be greater than 255 characters.',
        ];
    }
}
