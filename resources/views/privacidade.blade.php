<x-layouts.app title="Política de Privacidade — Diagnóstico Jurídico">
    <div>
        <flux:heading size="lg" level="1">Política de Privacidade</flux:heading>
        <flux:text class="mt-2">Última atualização: {{ now()->translatedFormat('d \d\e F \d\e Y') }}</flux:text>

        <div class="mt-6 flex flex-col gap-4 text-zinc-700 dark:text-zinc-300">
            <p>
                Esta aplicação coleta dados pessoais para gerar e enviar um diagnóstico jurídico no contexto do
                evento <strong>Empreende Brazil 2026</strong>, em conformidade com a Lei Geral de Proteção de
                Dados (Lei nº 13.709/2018 — LGPD).
            </p>

            <flux:heading size="md" level="2">Dados coletados</flux:heading>
            <p>Nome, empresa, e-mail, área de atuação, respostas do questionário, endereço IP e informações do navegador.</p>

            <flux:heading size="md" level="2">Finalidade</flux:heading>
            <p>
                Identificar o nível de maturidade jurídica da sua empresa, gerar a cartilha correspondente,
                enviar o resultado por e-mail e, mediante seu consentimento, entrar em contato sobre os temas tratados.
            </p>

            <flux:heading size="md" level="2">Compartilhamento</flux:heading>
            <p>Os dados não são vendidos. Podem ser tratados por prestadores de serviço (hospedagem e envio de e-mail) estritamente para as finalidades acima.</p>

            <flux:heading size="md" level="2">Seus direitos</flux:heading>
            <p>
                Você pode solicitar acesso, correção ou exclusão dos seus dados, bem como revogar o consentimento,
                a qualquer momento, pelos canais de contato do evento.
            </p>
        </div>

        <div class="mt-8">
            <flux:button :href="route('home')" variant="ghost" icon="arrow-left" wire:navigate>Voltar</flux:button>
        </div>
    </div>
</x-layouts.app>
