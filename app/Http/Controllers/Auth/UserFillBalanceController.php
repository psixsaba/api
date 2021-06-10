<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class UserFillBalanceController extends Controller
{
    public function fill_balance(Request $request){
        $transaction = new Transaction();
        $data = $request->validate([
            'sender_user_id' => 1,
            'recipient_user_id' => 2,
            'amount' => 2,
            'commission_amount' => 2
        ]);

        $transaction = Transaction::created($data);


        return response([ 'status' => 200 ]);

    }
}
