<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Models\Massevent\MemberStatus;
use Illuminate\Http\Request;

class MemberStatusController extends Controller
{
    public function index()
    {
        return MemberStatus::all();
    }
}
