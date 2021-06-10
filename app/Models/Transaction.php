<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table='transactions';
    protected $filable = ['sender_user_id', 'recipient_user_id', 'amount', 'commission_amount'];
}
