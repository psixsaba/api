<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransactionHistory;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;


class UserFillBalanceController extends Controller
{
    public function fill_balance(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'amount' => 'required|numeric|between:0,999.99'
        ]);

        $id = $request->user_id;
        $user = User::find($id);

        if ($user == null) {
            return response(['error_message' => 'User not found'], 404);
        }
        elseif ($validator->fails()){
            return response(['error' => $validator->errors(),
                'Validation Error'], 422);
        }


        $user->update($request->all());

        $user->balance += $request->amount;
        $user->update();

        $transaction = new BalanceTransactionHistory();

        $transaction->foreign_user_id = $id;
        $transaction->transfer_amount = $request->amount;

        $transaction->save();


        return response(['user' => $user, 'message' => 'Success'], 200);

    }

    public function transaction(Request $request) {

        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required'
        ]);

        $id = $request->user_id;
        $user = User::find($id);

        if ($user == null) {
            return response(['error_message' => 'User not found'], 404);
        }
        elseif ($validator->fails()){
            return response(['error' => $validator->errors(),
                'Validation Error'], 422);
        }
        $recipient_user_ID = $id;
        $transactions = BalanceHistory::query()->where('foreign_user_ID', $recipient_user_ID)->get();


        return response(['transactions' => $transactions, 'message' => 'Success'], 200);
    }
}
