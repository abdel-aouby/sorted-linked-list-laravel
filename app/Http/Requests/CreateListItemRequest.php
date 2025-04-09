<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\LinkedListType;
use App\Models\LinkedList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateListItemRequest extends FormRequest
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
        $linkedList = $this->route('linkedList');

        return [
            'value' => [
                'required',
                ...$this->getTypeValidationRule($linkedList),
                Rule::unique('linked_list_items')
                    ->where('linked_list_id', $linkedList->id)
                    ->ignore($this->route('item')?->id),
            ],
            'linked_list_id' => ['required', 'exists:linked_lists,id']
        ];
    }

    private function getTypeValidationRule(LinkedList $linkedList): array
    {
        return match($linkedList->type) {
            LinkedListType::INTEGERS_LINKED_LIST => ['integer'],
            LinkedListType::STRINGS_LINKED_LIST => ['string', 'min:1', 'max:255'],
        };
    }

    public function messages(): array
    {
        return [
            'value.unique' => __('This value already exists in the list.'),
            'value.integer' => __('The value must be a valid integer and with no decimals for this type of list.'),
            'value.string' => __('The value must be a valid string for this type of list.'),
        ];
    }
}
