<?php

namespace App\Models\Massevent;

use App\UseCases\Massevent\Statuses\MemberStatusCame;
use App\UseCases\Massevent\Statuses\MemberStatusConfirmed;
use App\UseCases\Massevent\Statuses\MemberStatusInvited;
use App\UseCases\Massevent\Statuses\MemberStatusNotCome;
use App\UseCases\Massevent\Statuses\MemberStatusRefused;
use App\UseCases\Massevent\TicketSetBuilder;

class MemberStatus
{

    const POSSIBLE_STATUSES = [
        'invited' => MemberStatusInvited::class,
        'confirmed' => MemberStatusConfirmed::class,
        'refused' => MemberStatusRefused::class,
        'came' => MemberStatusCame::class,
        'not_come' => MemberStatusNotCome::class,
    ];


    public static function all()
    {
        $result = [];

        foreach (self::POSSIBLE_STATUSES as $status) {
            $result[] = [
                'code' => $status::getCode(),
                'name' => $status::getName()
            ];
        }

        return $result;
    }


    public static function set(Member $member, string $code)
    {
        $class = self::POSSIBLE_STATUSES[$code];
        $status = new $class($member);
        return $status->set();
    }


    /**
     * Меняет, если это необходимо, статус участника, учитывая наличие у него билетов
     *
     * @param Member $member
     * @return Member
     */
    public static function changeForTicketPlanWheMemberCreated(Member $member): Member
    {

        // Для участника не бронировали билеты при его создании
        if ($member->ticket_plan < 1){
            return $member;
        }

        MemberStatus::set($member, MemberStatusConfirmed::getCode());

        // todo реализовать отправку СМС в обсервере билетных сетов

        return $member;
    }


    public static function changeForTicketPlanWhenMemberUpdated(Member $member): Member
    {

        // Количество билетов у участника не поменялось (условие отключено, т.к. отказался от обсервера)
//        if (!$member->wasChanged('ticket_plan')) {
//            return $member;
//        }

        // Участнику отменили бронь билетов
        if ($member->ticket_plan === 0) {
            MemberStatus::set($member, MemberStatusInvited::getCode());
        } else {
            MemberStatus::set($member, MemberStatusConfirmed::getCode());
        }

        // todo реализовать отправку СМС в обсервере билетных сетов

        return $member;
    }

    public static function changeForTicketFactWhenMemberUpdated(Member $member): Member
    {

        // Количество билетов у участника не поменялось (условие отключено, т.к. отказался от обсервера)
//        if (!$member->wasChanged('ticket_fact')) {
//            return $member;
//        }

        // Участнику отменили бронь билетов
        if ($member->ticket_fact === 0) {
            MemberStatus::set($member, MemberStatusConfirmed::getCode());
        } else {
            MemberStatus::set($member, MemberStatusCame::getCode());
        }

        return $member;
    }
}
