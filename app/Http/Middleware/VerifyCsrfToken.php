<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/customer/add',
        'login',
        'register',
        'user_details',
        '/customer/profile/update',
        '/customer/dashboard',
        '/customer/bills',
        '/customer/list',
        '/customer/payments_by_bill',
    ];
}
