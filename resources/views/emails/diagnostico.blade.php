@php
    $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
    $primeiroNome = $lead?->nome ? \Illuminate\Support\Str::before($lead->nome, ' ') : null;
@endphp

<x-mail::message>
# Olá{{ $primeiroNome ? ', ' . $primeiroNome : '' }}!

Obrigado por participar do **Diagnóstico Jurídico** do Empreende Brazil 2026.

## Resultado: {{ $labels[$diag['classificacao']] }} — {{ $diag['titulo'] }}

{{ $diag['mensagem'] }}

**Índice de exposição a riscos:** {{ $pontuacao }} de {{ $maxPontuacao }}

A sua **{{ $cartilha['titulo'] }}** ({{ $cartilha['subtitulo'] }}) está anexada a este e-mail em PDF. Você também pode acessar o diagnóstico completo online:

<x-mail::button :url="$resultadoUrl">
Ver meu diagnóstico completo
</x-mail::button>

---

**{{ $diag['fechamento'] }}**

<x-mail::button :url="$cafeUrl" color="success">
Agendar o Café com o Advogado
</x-mail::button>

Atenciosamente,
**Empreende Brazil 2026**

<x-slot:subcopy>
Você recebeu este e-mail porque solicitou um diagnóstico jurídico no evento Empreende Brazil 2026.
Se o botão acima não funcionar, copie e cole este endereço no navegador: {{ $resultadoUrl }}
</x-slot:subcopy>
</x-mail::message>
