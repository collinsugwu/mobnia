<?php


namespace App\Models;


class Plan extends Model
{
    protected $fillable = [
        'name', 'amount', 'duration'];

    protected $visible = [
        'name', 'amount', 'duration'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
