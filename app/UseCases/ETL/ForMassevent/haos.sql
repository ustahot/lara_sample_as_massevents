SELECT m.*
FROM massevents__members m
         INNER JOIN (select * from etl__stage_transform_members WHERE session_id = 22) e ON e.phone = m.phone_for_massevent
WHERE m.massevent_id = 1;


UPDATE massevents__members m SET (category_id = 7)
-- SELECT *
-- FROM massevents__members m
WHERE m.phone_for_massevent IN (
    select phone from etl__stage_transform_members WHERE session_id = 22
    )
  AND m.massevent_id = 1;


SELECT *
FROM massevents__members
WHERE category_id = 5
    AND phone_for_massevent IN (
        SELECT phone
        FROM etl__stage_transform_members
        WHERE session_id = 24
    );

UPDATE massevents__members SET category_id = 8
WHERE category_id = 5
  AND phone_for_massevent IN (
    SELECT phone
    FROM etl__stage_transform_members
    WHERE session_id = 24
);


SELECT *
FROM etl__stage_transform_members
WHERE session_id = 24
  AND phone NOT IN (
    SELECT phone_for_massevent
    FROM massevents__members
--     WHERE category_id = 5
)
