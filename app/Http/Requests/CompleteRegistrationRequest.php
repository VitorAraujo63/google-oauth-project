<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * PREPARE FOR VALIDATION
     * Isso roda ANTES das regras. Aqui removemos a máscara.
     * O 'cpf' chega '111.222.333-44' e vira '11122233344'
     */
    protected function prepareForValidation()
    {
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', $this->cpf),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'cpf' => ['required', 'digits:11', 'unique:users,cpf,' . $this->user()->id],
            'birth_date' => ['required', 'date', 'before:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.digits' => 'O CPF deve conter exatamente 11 números.',
            'cpf.unique' => 'Este CPF já está em uso.',
            'birth_date.before' => 'A data de nascimento deve ser válida.',
        ];
    }
}
