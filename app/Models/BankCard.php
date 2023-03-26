<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankCard extends Model
{
    use HasFactory;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_number',
        'cvv',
        'expiry_date',
        'expiry_date_month',
        'expiry_date_day',
    ];




    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
