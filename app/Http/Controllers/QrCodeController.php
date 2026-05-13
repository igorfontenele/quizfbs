<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function show(Request $request): View
    {
        // Proteção opcional: se QR_ACCESS_KEY estiver definida, exige ?key=...
        $key = config('quiz.qr_access_key');
        abort_if($key && $request->query('key') !== $key, 403);

        $url = route('home');

        $svg = QrCode::format('svg')
            ->size(1000)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($url);

        return view('qr', [
            'url' => $url,
            'svg' => $svg,
        ]);
    }
}
