<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartilhaController;
use App\Http\Controllers\QrCodeController;
use App\Livewire\AdminLeads;
use App\Livewire\AdminLogin;
use App\Livewire\LeadCapture;
use App\Livewire\QuizResultado;
use App\Livewire\QuizRunner;
use App\Models\QuizResposta;
use Illuminate\Support\Facades\Route;

// Landing — destino do QR Code do evento
Route::view('/', 'welcome')->name('home');

// Política de privacidade (LGPD)
Route::view('/privacidade', 'privacidade')->name('privacidade');

// Coleta de leads
Route::get('/diagnostico', LeadCapture::class)->name('diagnostico');

// Quiz — URL assinada (gerada após a coleta de leads)
Route::get('/diagnostico/quiz/{lead}', QuizRunner::class)
    ->name('quiz.run')
    ->middleware('signed');

// Resultado — URL assinada (gerada ao finalizar o quiz)
Route::get('/diagnostico/resultado/{resposta}', QuizResultado::class)
    ->name('resultado.show')
    ->middleware('signed');

// Cartilha exibida na tela (ideal para o totem) — URL assinada
Route::get('/cartilha/{slug}/{resposta}/ver', [CartilhaController::class, 'show'])
    ->name('cartilha.ver')
    ->middleware('signed');

// Download da cartilha em PDF — URL assinada
Route::get('/cartilha/{slug}/{resposta}', [CartilhaController::class, 'download'])
    ->name('cartilha.download')
    ->middleware('signed');

// CTA "Café com o Advogado" — registra o clique e redireciona
Route::get('/cafe-com-advogado/{resposta?}', function (?QuizResposta $resposta = null) {
    if ($resposta && ! $resposta->cta_clicado) {
        $resposta->forceFill(['cta_clicado' => true])->save();
    }

    return redirect()->away(config('quiz.cafe_com_advogado_url'));
})->name('cafe-com-advogado');

// Página com o QR Code para impressão (A4) — protegida por chave simples
Route::get('/qr', [QrCodeController::class, 'show'])->name('qr');

// Painel administrativo — login por sessão (ADMIN_USERNAME / ADMIN_PASSWORD)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', AdminLogin::class)->name('login');

    Route::post('logout', function () {
        request()->session()->forget(['admin_authenticated', 'admin_login_at']);
        request()->session()->regenerate();

        return redirect()->route('admin.login');
    })->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', AdminLeads::class)->name('leads');
        Route::get('export', [AdminController::class, 'export'])->name('export');
    });
});
