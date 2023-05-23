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
        '/customer/search',
        '/customer/payments_by_bill',
        '/brand/list',
        '/brand/add',
        '/routes/add',
        '/routes/update',
        '/brand/update',
        '/brand/get_active_brands',
        '/supply/add',
        '/supply/getSupplyRecordsForBill',
        '/supply/getSupplyRecordsSummary',
        '/bills/add',
        '/bills/getBillingStatusList',
        '/bills/SearchBills',
        '/supply/getSupplyRecordsSummaryByBillId',
    ];
}
