<?php

namespace App\Models\Auth;

use App\Models\Model;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Traits\HasFiles;
use App\Models\Traits\HasHttpToken;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, Notifiable;
    use HasHttpToken;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'other_names', 'email', 'phone'
    ];

    protected $casts = ['last_seen' => 'datetime'];

    /**
     * The attributes visible from the model's JSON form.
     *
     * @var array
     */
    protected $visible = [
        'id', 'first_name', 'last_name', 'other_names',
        'email', 'phone', 'username', 'created_at', 'admin', 'push_id', 'push_os'];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Subscription::class);
    }

    public function hasActiveSubscription()
    {
         if(is_object($this->subscription)) {
             return $this->subscription->is_active;
         };

         return false;
    }
}
