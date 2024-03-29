<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function getEmailAttribute($value)
    {
        if (auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->id === $this->id))
            return decrypt($value);
        else return null;
    }

    public function getRoleAttribute($value)
    {
        return decrypt($value);
    }

    public function getNameAttribute($value)
    {
        if (auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->id === $this->id))
            return decrypt($value);
        else return null;
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin' && !$this->is_new && !$this->is_disabled;
    }

    public function isNewAdmin()
    {
        return $this->role === 'admin' && $this->is_new && !$this->is_disabled;
    }

    public function isClient()
    {
        return $this->role === 'client' && !$this->is_new && !$this->is_disabled;
    }

    public function isNewClient()
    {
        return $this->role === 'client' && $this->is_new && !$this->is_disabled;
    }
}
