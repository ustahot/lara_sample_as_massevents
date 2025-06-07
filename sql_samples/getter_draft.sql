SELECT c.name AS category_name
     , SUM(mg.member_quantity) AS member_quantity_in_db
     , SUM(mg.came) / SUM(mg.member_quantity) AS conversion
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
GROUP BY mg.category_id
