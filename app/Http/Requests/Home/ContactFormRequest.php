<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest para validação do formulário de contato
 */
class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Qualquer um pode enviar o formulário
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:1000'],
            'preferred_contact' => ['nullable', 'in:email,phone,whatsapp'],
            'newsletter' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.max' => 'O nome deve ter no máximo 100 caracteres',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Digite um e-mail válido',
            'subject.required' => 'O assunto é obrigatório',
            'message.required' => 'A mensagem é obrigatória',
            'message.max' => 'A mensagem deve ter no máximo 1000 caracteres',
        ];
    }

    public function prepareForValidation(): void
    {
        // Limpar e formatar dados antes da validação
        $this->merge([
            'name' => trim($this->name),
            'email' => strtolower(trim($this->email)),
            'phone' => $this->phone ? preg_replace('/[^0-9+()-]/', '', $this->phone) : null,
            'subject' => trim($this->subject),
            'message' => trim($this->message),
            'newsletter' => (bool) $this->newsletter,
        ]);
    }
} 