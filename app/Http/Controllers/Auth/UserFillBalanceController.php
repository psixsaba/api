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

    public function transfer(Request $request , $user_id , $amount) {

        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
//            'amount' => 'required|numeric|between:0,999.99'

        ]);

//        $dataValidator = $request->all();
//
//        $validator = Validator::make($dataValidator, [
//            'amount' => 'required|numeric|between:0,999.99'
//        ]);

//        $id = $request->user_id;
        $id = $user_id;
        $user = User::find($id);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details.
            Please try again']);
        }
//        elseif ($user == null) {
//            return response(['error_message' => 'User not found'], 404);
//        }
//        elseif ($validator->fails()){
//            return response(['error' => $validator->errors(),
//                'Validation Error'], 422);
//        }


        $token = auth()->user()->createToken('API Token')->accessToken;

//        $user->update($request->all());

        $id = auth()->user()->id;
        $auth_user = User::find($id);
//        $auth_user->balance -= $request->amount;
        $auth_user->balance -= $amount;
        $auth_user->update();

//        $user->balance += ($request->amount)*99/100;
        $user->balance += ($amount)*99/100;
        $user->update();


        return response(['user' => auth()->user(), 'token' => $token]);

    }
}
