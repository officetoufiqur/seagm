<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SeagmHelper;
use App\Http\Controllers\Controller;

class MobileRechargeController extends Controller
{
    public function index()
    {
        $response = SeagmHelper::get('v1/mobile-recharge/countries');
        return $response;
    }
}
