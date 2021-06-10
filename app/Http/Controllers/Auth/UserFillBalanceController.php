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


        return response(['user' => $user, 'message' => 'Success'], 200);

    }
}
