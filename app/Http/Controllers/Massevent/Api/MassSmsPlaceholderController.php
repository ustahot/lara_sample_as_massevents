<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Massevent\MassSmsPlaceHolder\MassSmsPlaceholderWithoutPhpVariableResource;
use App\Models\Massevent\Placeholder\MassSmsPlaceholder;

class MassSmsPlaceholderController extends Controller
{
    public function index()
    {
//        MassSmsPlaceholderWithoutPhpVariableResource::collection(MassSmsPlaceholder::all());
        return MassSmsPlaceholderWithoutPhpVariableResource::collection(MassSmsPlaceholder::all())->resolve();
    }
}
