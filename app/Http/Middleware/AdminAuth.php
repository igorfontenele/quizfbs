<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protege as rotas administrativas com sessão (login pelo Livewire `AdminLogin`).
 * Se ADMIN_PASSWORD não estiver configurada, o painel fica indisponível.
 */
class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if ((string) config('quiz.admin_password') === '') {
            abort(404);
        }

        if ($request->session()->get('admin_authenticated') !== true) {
            return redirect()->guest(route('admin.login'));
        }

        return $next($request);
    }
}
