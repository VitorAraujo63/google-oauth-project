<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Retorna true porque o usuário já está logado via Sanctum
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cpf' => 'required|string|min:11|max:14|unique:users,cpf',
            'birth_date' => 'required|date|before:today',
        ];
    }

    /**
     * Mensagens personalizadas (Opcional, mas bom para UX)
     */
    public function messages(): array
    {
        return [
            'cpf.unique' => 'Este CPF já está sendo utilizado por outro usuário.',
            'cpf.size' => 'O CPF deve conter 14 caracteres.',
            'birth_date.before' => 'A data de nascimento deve ser válida.',
        ];
    }
}
