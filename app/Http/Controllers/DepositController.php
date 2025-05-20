<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DepositController extends Controller
{
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'method' => 'required|string',
        ]);

        $deposit = Deposit::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending',
        ]);

        $user = Auth::user();
        $formattedAmount = number_format($deposit->amount, 0, ',', '.');
        $waktu = now()->format('d-m-Y H:i');

        $pesanUser = "✅ *PERMINTAAN DEPOSIT DIAJUKAN*\n\n" .
            "Halo {$user->name}, kami telah menerima permintaan deposit kamu sebesar *Rp {$formattedAmount}* dengan metode *{$deposit->method}*.\n\n" .
            "📅 Tanggal: {$waktu}\n\n" .
            "Mohon segera kirim bukti transfer ke admin melalui WhatsApp: ini (pastikan nominal sesuai).\n" .
            "Deposit akan diproses setelah bukti transfer diterima. Terima kasih 🙏";

        $this->kirimPesanWhatsapp($user->telephone, $pesanUser);

        return response()->json([
            'message' => 'Deposit berhasil diajukan. Silakan kirim bukti transfer ke admin.',
        ]);
    }

    private function kirimPesanWhatsapp($nomor, $pesan)
    {
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $pesan,
        ]);
    }
}
