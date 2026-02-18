<?php

namespace App\Policies\Dashboard;

use App\Policies\ModelPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy extends ModelPolicy
{
    use HandlesAuthorization;
    public function __construct()
    {
        //
    }


}
