<?php


namespace App\Models;


class Payment extends Model
{
    protected $fillable = [
        'subscription_id', 'start_at', 'payment_ref', 'end_at', 'paid_at', 'authorization'];

    protected $casts = [
        'start_at' => 'datetime', 'end_at' => 'datetime', 'paid_at' => 'datetime'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
