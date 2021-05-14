<?php


namespace App\Models;


class Plan extends Model
{
    protected $fillable = [
        'name', 'amount', 'duration'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
