<div>
    @if ($analisando)
        {{-- Tela de loading curta antes do resultado --}}
        <div
            class="flex flex-col items-center justify-center py-20 text-center"
            x-data
            x-init="setTimeout(() => window.location.assign(@js($resultadoUrl)), 1500)"
        >
            <flux:icon.loading variant="solid" class="size-10 text-brand-600 dark:text-brand-400" />
            <flux:heading size="lg" level="2" class="mt-5">Analisando suas respostas...</flux:heading>
            <flux:subheading>Estamos preparando seu diagnóstico jurídico personalizado.</flux:subheading>
        </div>
    @else
        <div wire:key="eixo-{{ $eixoAtual }}">
            <x-quiz.progress
                :current="$eixoAtual"
                :total="$this->totalEixos"
                :respondidasNoEixo="$this->respondidasNoEixo"
                :perguntasNoEixo="count($this->perguntasDoEixo)"
                :percent="$this->progresso"
                :eixoNome="$this->nomeDoEixo"
            />

            <div class="flex flex-col gap-4">
                @php $offset = ($eixoAtual - 1) * 3; @endphp
                @foreach ($this->perguntasDoEixo as $key => $pergunta)
                    <x-quiz.pergunta-card
                        :numero="$offset + $loop->iteration"
                        :wireKey="$key"
                        :pergunta="$pergunta"
                    />
                @endforeach
            </div>

            @error('eixo')
                <flux:callout variant="warning" icon="exclamation-triangle" class="mt-4">
                    {{ $message }}
                </flux:callout>
            @enderror

            <div class="mt-6 flex items-center gap-3">
                @if ($eixoAtual > 1)
                    <flux:button wire:click="voltar" variant="ghost" icon="arrow-left">Voltar</flux:button>
                @endif

                <flux:spacer />

                <flux:button
                    wire:click="proximo"
                    variant="primary"
                    class="min-h-12 text-base"
                    :icon-trailing="$eixoAtual < $this->totalEixos ? 'arrow-right' : 'check'"
                    wire:loading.attr="disabled"
                >
                    {{ $eixoAtual < $this->totalEixos ? 'Próximo eixo' : 'Ver meu diagnóstico' }}
                </flux:button>
            </div>
        </div>
    @endif
</div>
