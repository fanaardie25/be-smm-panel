<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function getHistoryDepo(Request $request)
    {
        $user = $request->user();
        $deposit = Deposit::where('user_id',$user->id)->get();

        if ($deposit->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'berhasil mendapatkan data history deposit',
                'data' => $deposit
            ]);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'gagal mendapatkan data history deposit',
            ]);
        }
    }
}
