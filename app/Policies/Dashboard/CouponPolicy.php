<?php

namespace App\Policies\Dashboard;

use App\Policies\ModelPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy extends ModelPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
