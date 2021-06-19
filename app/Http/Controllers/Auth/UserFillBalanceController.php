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

    public function transfer(Request $request , $user_id ) {

        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required',


        ]);

        $dataValidator = $request->all();

        $validator = Validator::make($dataValidator, [
            'amount' => 'required'
        ]);

//        $id = $request->user_id;
        $id = $user_id;
        $user = User::find($id);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'momxmareblis paroli an meili arasworia.
            gtxovt scadot tavidan']);
        }
        elseif ($user == null) {
            return response(['error_message' => 'momxmarebeli ar moidzebna'], 404);
        }
        elseif ($validator->fails()){
            return response(['error' => $validator->errors(),
                'Validation Error'], 422);
        }


        $token = auth()->user()->createToken('API Token')->accessToken;

//        $user->update($request->all());

        $id = auth()->user()->id;
        $auth_user = User::find($id);
//        $auth_user->balance -= $request->amount;
        $auth_user->balance -= $request->amount;
        $auth_user->update();

//        $user->balance += ($request->amount)*99/100;
        $user->balance += ($request->amount)*99/100;
        $user->update();



        return response(['user' => auth()->user(), 'token' => $token]);

    }

    public function mytransaction(Request $request){
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'araswori informacia.
            Please try again'], 422);
        }
        $id = auth()->user()->id;

        $mytransaction = Transaction::where('sender_user_id', $id)->get();

        return response(['message' => $mytransaction]);

}

    public function history(Request $request) {

        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required'
        ]);

        $id = $request->user_id;
        $user = User::find($id);

        if ($user == null) {
            return response(['error_message' => 'momxmarebeli ar moidzebna'], 404);
        }
        elseif ($validator->fails()){
            return response(['error' => $validator->errors(),
                'Validation Error'], 422);
        }
        $foreign_user_ID = $id;
        $transactions = BalanceTransactionHistory::query()->where('foreign_user_ID', $foreign_user_ID)->get();


        return response(['transactions' => $transactions, 'message' => 'Success']);
    }

    public function transactions(Request $request){
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'araswori informacia.
            Please try again'], 422);
        }

        $is_admin = auth()->user()->is_admin;

        $transaction = Transaction::all();
        $commission_sum = Transaction::sum('commission_amount');

        if ($is_admin == 1) {
            return response(['message' => [$transaction, $commission_sum]]);
        }
        else {
            return response(['message' => 'tkven ar gaqvt wvdoma']);
        }
    }

}
