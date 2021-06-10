<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransactionHistory extends Model
{
    use HasFactory;

    protected $table = 'balance_transaction_historys';

    protected $fillable = [
        'foreign_user_ID', 'transfer_amount',
    ];

    public function user()
    {
        return $this->belongsTo( User::class, 'foreign_user_ID');
    }
}
