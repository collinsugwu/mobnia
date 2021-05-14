<?php


namespace App\Models;


use App\Models\Auth\User;
use App\Models\Traits\HasReference;

class Subscription extends Model
{
    use HasReference;

    protected $fillable = [
        'reference', 'user_id', 'plan_id', 'is_active', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
