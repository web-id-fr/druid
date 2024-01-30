<?php

namespace Webid\Druid\App\Models\Dummy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DummyUser extends Authenticatable
{
    use HasFactory;

    /** @var string */
    protected $table = 'users';

    /** @var string[] */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
