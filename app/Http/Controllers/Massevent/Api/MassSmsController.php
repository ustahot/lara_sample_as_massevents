<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Exceptions\Massevent\MassSmsException;
use App\Exceptions\Massevent\MemberException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\MassSms\PrepareByFilterRequest;
use App\Http\Requests\Massevent\Api\MassSms\RenderRequest;
use App\Http\Resources\Massevent\MassSms\MassSmsByFilterResource;
use App\Jobs\Massevent\MassSms\MassSmsToPersonalJob;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\MassSms;
use App\UseCases\Massevent\MassSmsBuilder;
use Illuminate\Database\Eloquent\MassAssignmentException;

class MassSmsController extends Controller
{
    public function prepareByFilter(PrepareByFilterRequest $request, Massevent $massevent)
    {
        try {

            $validated = $request->customValidate($massevent);
            $builder = new MassSmsBuilder($validated, $massevent);
            $massSms = $builder->createInstance();
            return MassSmsByFilterResource::make($massSms)->resolve();

        } catch (MassSmsException $exception) {

            return response([
                'message' => $exception->getMessage(),
            ], $exception->getCode());

        }
    }

    public function render(RenderRequest $request)
    {
        try {
            $validated = $request->customValidate();
            $result = MassSmsToPersonalJob::dispatch($validated)->onQueue('mass_sms');
            return;
        } catch (MassSmsException|MemberException $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }
    }
}
