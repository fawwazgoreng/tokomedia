<?php

namespace App\Http\Controllers;

use App\services\resReturn;
use Illuminate\Http\Request;

class StoreLogController extends Controller
{
    public function __construct(protected resReturn $resReturn) {}
    protected function getSecretKey () {
        $key = config("");
    }
}
