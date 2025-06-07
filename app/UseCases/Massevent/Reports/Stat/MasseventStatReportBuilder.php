<?php

namespace App\UseCases\Massevent\Reports\Stat;

use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberCategory;
use Illuminate\Support\Facades\DB;

class MasseventStatReportBuilder
{
    public function __construct(private readonly Massevent $massevent)
    {
    }

    public function getData()
    {

        $memberTable = app(Member::class)->getTable();
        $categoryTable = app(MemberCategory::class)->getTable();

        $sql = "
            SELECT c.name AS category_name
                 , SUM(mg.member_quantity) AS member_quantity_in_db
                 , ( SUM(mg.agg_ticket_fact) / SUM(mg.agg_ticket_plan) ) * 100 AS conversion
                 , SUM(mg.refused) AS refused
                 , SUM(mg.confirmed) AS confirmed
                 , SUM(mg.agg_ticket_plan) AS agg_ticket_plan
                 , SUM(mg.came) AS came
                 , SUM(mg.agg_ticket_fact) AS agg_ticket_fact
            FROM (
                     SELECT m.category_id
                         , m.status
                         , COUNT(*) AS member_quantity
                         , SUM(m.ticket_plan) AS agg_ticket_plan
                         , SUM(m.ticket_fact) AS agg_ticket_fact
                         , IF(status = 'refused', COUNT(*), 0) AS refused
                         , IF(status = 'confirmed', COUNT(*), 0) AS confirmed
                         , IF(status = 'came', COUNT(*), 0) AS came
                     FROM {$memberTable} m
                     WHERE m.massevent_id = {$this->massevent->id}
                       AND m.deleted_at IS NULL
                     GROUP BY m.category_id, status
                 ) mg
            RIGHT JOIN {$categoryTable} c ON c.id = mg.category_id
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
