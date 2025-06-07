<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\MemberAnswer\checkinAboutParticipationRequest;
use App\Http\Resources\Massevent\MemberAnswer\MemberAnswerResource;
use App\Models\Massevent\MemberAnswer;
use App\UseCases\Massevent\MemberAnswers\MemberAnswerBuilder;

class MemberAnswersController extends Controller
{
    public function checkinAboutParticipation(checkinAboutParticipationRequest $request)
    {
        $validated = $request->validated();
        $builder = new MemberAnswerBuilder($validated);
        $instance = $builder->createInstance();
        return MemberAnswerResource::make($instance)->resolve();
    }
}
