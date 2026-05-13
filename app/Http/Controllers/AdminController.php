<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function export(Request $request): StreamedResponse
    {
        $search = trim((string) $request->query('q', ''));
        $classificacao = (string) $request->query('classificacao', '');

        $query = Lead::query()
            ->with('quizResposta')
            ->when($search !== '', function (Builder $q) use ($search) {
                $term = '%'.str_replace(['%', '_'], ['\%', '\_'], $search).'%';
                $q->where(fn (Builder $q) => $q
                    ->where('nome', 'like', $term)
                    ->orWhere('empresa', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('telefone', 'like', $term)
                    ->orWhere('area_atuacao', 'like', $term));
            })
            ->when($classificacao !== '', function (Builder $q) use ($classificacao) {
                $classificacao === 'incompleto'
                    ? $q->whereDoesntHave('quizResposta', fn (Builder $r) => $r->whereNotNull('classificacao'))
                    : $q->whereHas('quizResposta', fn (Builder $r) => $r->where('classificacao', $classificacao));
            })
            ->latest();

        $filename = 'leads-empreende-brazil-'.now()->format('Y-m-d-Hi').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            // BOM para o Excel reconhecer UTF-8
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID', 'Nome', 'Empresa', 'E-mail', 'Telefone/WhatsApp', 'Área de atuação', 'Origem',
                'Consentimento LGPD', 'Criado em',
                'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9',
                'Pontuação', 'Classificação', 'Cartilha baixada', 'Clicou no Café', 'Quiz concluído em',
            ]);

            $query->chunk(500, function ($leads) use ($out) {
                foreach ($leads as $lead) {
                    $r = $lead->quizResposta;
                    fputcsv($out, [
                        $lead->id,
                        $lead->nome,
                        $lead->empresa,
                        $lead->email,
                        $lead->telefone,
                        $lead->area_atuacao,
                        $lead->origem,
                        $lead->consentimento_lgpd ? 'Sim' : 'Não',
                        $lead->created_at?->format('d/m/Y H:i'),
                        $r?->q1, $r?->q2, $r?->q3, $r?->q4, $r?->q5, $r?->q6, $r?->q7, $r?->q8, $r?->q9,
                        $r?->pontuacao_total,
                        $r?->classificacao,
                        $r ? ($r->cartilha_baixada ? 'Sim' : 'Não') : '',
                        $r ? ($r->cta_clicado ? 'Sim' : 'Não') : '',
                        $r && $r->classificacao ? $r->updated_at?->format('d/m/Y H:i') : '',
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
