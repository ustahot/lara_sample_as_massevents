<?php

namespace App\UseCases\Massevent\Reports\StatWithCalls;

use App\Models\Massevent\CallToMember;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberCategory;
use App\Models\Massevent\TicketSet;
use Illuminate\Support\Facades\DB;

class MasseventStatWithCallsReportBuilder
{
    public function __construct(private readonly Massevent $massevent)
    {
    }

    public function getData()
    {

        $memberTable = app(Member::class)->getTable();
        $categoryTable = app(MemberCategory::class)->getTable();
        $ticketSetsTable = app(TicketSet::class)->getTable();
        $callsTable = app(CallToMember::class)->getTable();
        $todayStartDateTime = date('Y-m-d 00:00:00');

            $sql = "
                SELECT c.name AS category_name
                     , SUM(mg.member_quantity) AS member_quantity_in_db
                     , ( SUM(mg.agg_ticket_fact) / SUM(mg.agg_ticket_plan) ) * 100 AS conversion
                     , SUM(mg.refused) AS refused
                     , SUM(mg.confirmed) AS confirmed
                     , SUM(mg.agg_ticket_plan) AS agg_ticket_plan
                     , SUM(mg.came) AS came
                     , SUM(mg.agg_ticket_fact) AS agg_ticket_fact
                     , SUM(mg.calls_today) AS calls_today
                     , SUM(mg.ticket_plan_today) AS ticket_plan_today
                     , SUM(mg.members_without_tickets) AS members_without_tickets
                FROM (
                         SELECT m.category_id
                              , m.status
                              , COUNT(*) AS member_quantity
                              , SUM(m.ticket_plan) AS agg_ticket_plan
                              , SUM(m.ticket_fact) AS agg_ticket_fact
                              , IF(status = 'refused', COUNT(*), 0) AS refused
                              , IF(status = 'confirmed', COUNT(*), 0) AS confirmed
                              , IF(status = 'came', COUNT(*), 0) AS came
                              , SUM(ticket_plan_today_table.ticket_plan) as ticket_plan_today
                              , SUM(member_without_ticket.quantity) AS members_without_tickets
                              , SUM(calls_today_table.quantity) AS calls_today
                         FROM {$memberTable} m
                              LEFT JOIN (
                                 SELECT id, ticket_plan
                                 FROM {$ticketSetsTable}
                                 WHERE deleted_at IS NULL
                                   AND created_at >= '{$todayStartDateTime}'
                              ) ticket_plan_today_table
                                  ON ticket_plan_today_table.id = m.ticket_set_id -- оформлено билетов сегодня
                              LEFT JOIN (
                                 SELECT member_id, 1 as quantity
                                 FROM {$callsTable}
                                 WHERE deleted_at IS NULL
                                   AND created_at >= '{$todayStartDateTime}'
                              ) calls_today_table
                                  ON calls_today_table.member_id = m.id -- Звонков сегодня
                             LEFT JOIN (
                                 SELECT id, 1 as quantity
                                 FROM {$memberTable}
                                 WHERE deleted_at IS NULL
                                   AND (status = 'invited' OR status IS NULL)
                                   AND massevent_id = {$this->massevent->id}
                             ) member_without_ticket
                                 ON member_without_ticket.id = m.id -- безбилетники сгруппированные по категориям
                         WHERE m.deleted_at IS NULL
                           AND m.massevent_id = {$this->massevent->id}
                         GROUP BY m.category_id, m.status, member_without_ticket.quantity
                ) mg -- основной выбор
                    RIGHT JOIN {$categoryTable} c ON c.id = mg.category_id -- чтобы пустые категории тоже попали в выборку
                GROUP BY c.name, mg.category_id, c.sort
                ORDER BY c.sort
        ";

        $detailed = DB::select($sql);

        $total = [
            'total_ticket_plan' => 0,
            'total_ticket_fact' => 0,
        ];

        foreach ($detailed as $row) {
            $total['total_ticket_plan'] += $row->agg_ticket_plan ?? 0;
            $total['total_ticket_fact'] += $row->agg_ticket_fact ?? 0;
        }

        $total['rest'] = $this->massevent->total_ticket_max_quantity - $total['total_ticket_plan'];
        if ($total['total_ticket_plan'] === 0) {
            $total['total_conversion'] = 0;
        } else {
            $total['total_conversion'] = ( $total['total_ticket_fact'] / $total['total_ticket_plan'] ) * 100;
        }

        return [
            'detailed' => $detailed,
            'total' => $total
        ];

    }


}
