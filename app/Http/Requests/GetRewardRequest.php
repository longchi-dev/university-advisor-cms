<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $id
 * @property string $phone
 */
class GetRewardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User|null $admin */
        $admin = auth()->user();
        return $admin != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'phone_hashed' => 'string'
        ];
    }
}
