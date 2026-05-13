<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protege as rotas administrativas com HTTP Basic Auth.
 * Usuário: qualquer um. Senha: ADMIN_PASSWORD (config quiz.admin_password).
 */
class AdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('quiz.admin_password');

        // Se nenhuma senha foi configurada, o painel fica indisponível (em vez de aberto).
        if ($expected === '') {
            abort(404);
        }

        $provided = (string) $request->getPassword();

        if (! hash_equals($expected, $provided)) {
            return response('Acesso restrito.', Response::HTTP_UNAUTHORIZED, [
                'WWW-Authenticate' => 'Basic realm="Painel — Diagnóstico Jurídico", charset="UTF-8"',
            ]);
        }

        return $next($request);
    }
}
