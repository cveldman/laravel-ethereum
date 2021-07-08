<?php

namespace Appbakkers\Ethereum\Tests\Fixtures;

use Appbakkers\Ethereum\Traits\Billable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Billable; // todo: CanInteractWithContract Trait?

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
