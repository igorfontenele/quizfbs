@php
    $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
    $resultadoLabel = $resposta && $resposta->classificacao ? $labels[$resposta->classificacao] : null;
    $pontuacao = $resposta?->pontuacao_total;
    $maxPontuacao = \App\Services\QuizClassifierService::PONTUACAO_MAXIMA;
    $urgente = $resposta && $resposta->classificacao === 'vermelho';
@endphp

@extends('pdfs.layout')

@section('accent', $urgente ? '#dc2626' : '#eab308')

@section('conteudo')
    @include('cartilhas.conteudo.gestao-riscos', ['urgente' => $urgente])
@endsection
