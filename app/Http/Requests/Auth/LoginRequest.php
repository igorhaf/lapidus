<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        try {
            $throttleKey = $this->throttleKey();
            
            // Debug detalhado
            \Log::error('DEBUG: Throttle key info', [
                'throttle_key' => $throttleKey,
                'throttle_key_type' => gettype($throttleKey),
                'throttle_key_length' => strlen($throttleKey),
                'email_input' => $this->input('email'),
                'email_type' => gettype($this->input('email')),
                'ip' => $this->ip(),
            ]);
            
            if (! RateLimiter::tooManyAttempts($throttleKey, 5)) {
                return;
            }

            event(new Lockout($this));

            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        } catch (\Exception $e) {
            \Log::error('DEBUG: Exception in ensureIsNotRateLimited', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        try {
            $email = $this->input('email');
            
            // Debug detalhado
            \Log::error('DEBUG: throttleKey method', [
                'email_input' => $email,
                'email_type' => gettype($email),
                'email_is_array' => is_array($email),
                'ip' => $this->ip(),
            ]);
            
            // Garantir que email seja uma string
            if (is_array($email)) {
                $email = implode('', $email);
                \Log::error('DEBUG: Email was array, converted to: ' . $email);
            }
            
            // Converter para string e normalizar
            $email = strtolower((string) $email);
            
            $result = $email . '|' . $this->ip();
            
            \Log::error('DEBUG: throttleKey result', [
                'result' => $result,
                'result_type' => gettype($result),
                'result_length' => strlen($result),
            ]);
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('DEBUG: Exception in throttleKey', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}
