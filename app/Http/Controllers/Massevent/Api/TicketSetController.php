<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Exceptions\Massevent\TicketSetException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\TicketSet\CheckinRequest;
use App\Http\Requests\Massevent\App\TicketSet\StoreRequest;
use App\Http\Resources\Massevent\TicketSets\TicketSetsResource;
use App\Models\Massevent\Member;
use App\Models\Massevent\TicketSet;
use App\UseCases\Massevent\TicketSetBuilder;
use App\UseCases\Massevent\TicketSetCheckin;
use Illuminate\Http\Response;

class TicketSetController extends Controller
{
//    public function createFromApp(StoreRequest $request, Member $member)
//    {
//        try {
//            $request->customValidate($member);
//            return TicketSetBuilder::createModelInstance($member);
//        } catch (TicketSetException $exception) {
//            // Скорее всего билеты уже были выданы. По состоянию на 10/03/2025, проверяется только это.
//            // Если негативных сценариев будет больше, то стоит добавить анализ кода исключения.
//            return null;
//        }
//    }


    public function showCheckin(TicketSet $ticketSet)
    {
        return TicketSetsResource::make($ticketSet);
    }

    /**
     * @param CheckinRequest $request
     * @param TicketSet $ticketSet
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response|Member
     */
    public function checkin(CheckinRequest $request, TicketSet $ticketSet)
    {
        $validated = $request->validated();
        $case = new TicketSetCheckin($ticketSet);
        try {
            return $case->chckin($validated['ticket_fact']);
        } catch (TicketSetException $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

    }
}
