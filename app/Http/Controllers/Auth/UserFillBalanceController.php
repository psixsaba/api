<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class UserFillBalanceController extends Controller
{
    public function fill_balance(Request $request)
    {
         $request->validate([
            'user_id' => 'required',
            'amount' => 'required|integer|digits_between:1,5'
        ]);

        $id = $request->user_id;
        $user = User::find($id);

        if ($user == null) {
            return response(['error_message' => 'tkvenze uaria']);
        }

        $user->update($request->all());

        $user->balance += $request->amount;
        $user->update();


        return response(['user' => $user, 'message' => 'Success'], 200);

    }
}
