<?php

namespace App\Livewire;

use App\Mail\ConfirmacaoPreenchimento;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Seus dados — Diagnóstico Jurídico')]
class LeadCapture extends Component
{
    #[Validate('required|string|min:2|max:150')]
    public string $nome = '';

    #[Validate('required|string|min:2|max:150')]
    public string $empresa = '';

    #[Validate('required|email:rfc|max:180')]
    public string $email = '';

    public string $telefone = '';

    #[Validate('required|string|max:120')]
    public string $area_atuacao = '';

    #[Validate('accepted')]
    public bool $consentimento_lgpd = false;

    /** Honeypot anti-bot — deve permanecer vazio. */
    public string $website = '';

    public function mount(): void
    {
        $this->area_atuacao = '';
    }

    public function rules(): array
    {
        return [
            'telefone' => ['required', 'string', 'min:10', 'max:30', 'regex:/^[0-9()+\-\s]{10,}$/'],
            'area_atuacao' => ['required', 'string', Rule::in(config('quiz.areas_atuacao'))],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'telefone' => 'telefone (WhatsApp)',
            'consentimento_lgpd' => 'consentimento',
            'area_atuacao' => 'área de atuação',
        ];
    }

    public function submit()
    {
        // Honeypot: se preenchido, é bot — finge sucesso e descarta.
        if (filled($this->website)) {
            return redirect()->route('home');
        }

        $validated = $this->validate();

        $lead = Lead::create([
            'nome' => $validated['nome'],
            'empresa' => $validated['empresa'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'area_atuacao' => $validated['area_atuacao'],
            'origem' => config('quiz.origem'),
            'ip' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 1000),
            'consentimento_lgpd' => true,
        ]);

        // E-mail de agradecimento pelo preenchimento (Resend) — enfileirado.
        Mail::to($lead->email)->queue(new ConfirmacaoPreenchimento($lead));

        // URL assinada para impedir pular direto pro quiz sem passar por aqui.
        return redirect()->to(URL::signedRoute('quiz.run', ['lead' => $lead]));
    }

    public function render()
    {
        return view('livewire.lead-capture', [
            'areas' => config('quiz.areas_atuacao'),
        ]);
    }
}
