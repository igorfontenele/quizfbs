@php
    $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
    $resultadoLabel = $resposta && $resposta->classificacao ? $labels[$resposta->classificacao] : null;
    $pontuacao = $resposta?->pontuacao_total;
    $maxPontuacao = \App\Services\QuizClassifierService::PONTUACAO_MAXIMA;
@endphp

@extends('pdfs.layout')

@section('accent', '#16a34a')

@section('conteudo')
    @include('cartilhas.conteudo.expansao')
@endsection
