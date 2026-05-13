<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Recebemos seus dados</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#27272a;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">Recebemos seus dados — em breve você receberá o diagnóstico jurídico e a cartilha.</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;">
        <tr>
            <td align="center" style="padding:24px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.08);">
                    {{-- Faixa de gradiente FBS --}}
                    <tr><td style="height:6px;background:#bd213f;background-image:linear-gradient(90deg,#bd213f 0%,#f37521 55%,#fcb813 100%);font-size:0;line-height:0;">&nbsp;</td></tr>

                    {{-- Cabeçalho preto com o logo FBS --}}
                    <tr>
                        <td align="center" style="background:#0a0a0a;padding:26px 24px;">
                            <img src="{{ $logoUrl }}" width="220" alt="FBS — Fonseca Brasil Serrão Advogados" style="display:block;width:220px;max-width:60%;height:auto;">
                        </td>
                    </tr>

                    {{-- Corpo --}}
                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <p style="margin:0 0 4px;font-size:13px;letter-spacing:.12em;text-transform:uppercase;color:#bd213f;font-weight:600;">Empreende Brazil 2026</p>
                            <h1 style="margin:0 0 16px;font-size:24px;line-height:1.25;color:#18181b;">{{ $saudacao }} Recebemos seus dados.</h1>
                            <p style="margin:0 0 14px;font-size:16px;line-height:1.6;color:#3f3f46;">
                                Agradecemos por participar do <strong>Diagnóstico Jurídico</strong> do Empreende Brazil 2026.
                                Já registramos suas informações{{ $empresaFrase }}.
                            </p>
                            <p style="margin:0 0 22px;font-size:16px;line-height:1.6;color:#3f3f46;">
                                Assim que você concluir o questionário, enviaremos por e-mail o seu <strong>resultado</strong> e a <strong>cartilha</strong> correspondente.
                                Se ainda não terminou, é rapidinho:
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 26px;">
                                <tr>
                                    <td style="border-radius:10px;background:#bd213f;">
                                        <a href="{{ $siteUrl }}" style="display:inline-block;padding:14px 28px;font-size:16px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;">Continuar o diagnóstico</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 4px;font-size:14px;line-height:1.6;color:#71717a;">Qualquer dúvida, é só responder este e-mail.</p>
                            <p style="margin:0;font-size:14px;line-height:1.6;color:#71717a;">Atenciosamente,<br><strong style="color:#3f3f46;">Fonseca Brasil Serrão Advogados</strong></p>
                        </td>
                    </tr>

                    {{-- Rodapé --}}
                    <tr>
                        <td style="padding:22px 32px 28px;border-top:1px solid #f0f0f1;">
                            <p style="margin:0 0 4px;font-size:12px;line-height:1.6;color:#a1a1aa;">Você recebeu este e-mail porque preencheu o formulário do Diagnóstico Jurídico no Empreende Brazil 2026.</p>
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#a1a1aa;">Desenvolvido Internamente por Fonseca Brasil Serrão.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
