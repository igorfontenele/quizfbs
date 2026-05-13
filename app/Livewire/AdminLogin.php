<?php

namespace App\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Entrar — Painel FBS')]
class AdminLogin extends Component
{
    #[Validate('required|string|max:120')]
    public string $usuario = '';

    #[Validate('required|string|max:120')]
    public string $senha = '';

    public function mount()
    {
        // Sem senha configurada → painel desativado.
        if ((string) config('quiz.admin_password') === '') {
            abort(404);
        }

        // Já autenticado → vai direto pro painel.
        if (session('admin_authenticated') === true) {
            return redirect()->route('admin.leads');
        }
    }

    public function submit()
    {
        $this->validate();

        $key = 'admin-login:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'usuario' => "Muitas tentativas. Tente novamente em {$seconds}s.",
            ]);
        }

        $usuarioOk = hash_equals((string) config('quiz.admin_username'), $this->usuario);
        $senhaOk = hash_equals((string) config('quiz.admin_password'), $this->senha);

        if (! $usuarioOk || ! $senhaOk) {
            RateLimiter::hit($key, 60);
            // Pequeno delay anti-timing
            usleep(random_int(150_000, 350_000));
            throw ValidationException::withMessages([
                'usuario' => 'Usuário ou senha incorretos.',
            ]);
        }

        RateLimiter::clear($key);

        session()->regenerate();
        session(['admin_authenticated' => true, 'admin_login_at' => now()->toIso8601String()]);

        return redirect()->intended(route('admin.leads'));
    }

    public function render()
    {
        return view('livewire.admin-login');
    }
}
