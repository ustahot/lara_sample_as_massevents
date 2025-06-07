/*
  Скрипт получает пары полузвонков и возвращает результат в виде композитных звонков.
  Рекомендуется таблицу asterisk_manager__cdr создавать с разбиением на партиции по месяцам
  Рассматриваемые телефоны колл-центра вхардкожены в скрипт, т.к. их мало, а телефоны менеджеров
  внесены в таблицу asterisk_manager__manager_phones. При этом рассматриваются только те телефоны менеджеров,
  у которых признак active = TRUE (или 1). Это значение устанавливается по умолчанию при добавлении нового телефона
 */

select f.id first_id, s.id second_id, f.src actor_phone, f.dst first_phone, s.dst second_phone
     , f.record_file_name first_record
     , s.record_file_name second_record
from asterisk_manager__cdr f
    inner join (
                select c.*
                from asterisk_manager__cdr c
                    inner join asterisk_manager__manager_phones p -- Ограничиваем вторую часть звонка только телефонами менеджеров
                        on p.active = TRUE and c.dst = p.phone
                where c.start > PERIOD_ADD(DATE_FORMAT(NOW(), '%Y%m'), -2) -- Ограничиваем хронологическую глубину
                  and c.composite_call_id is null -- Рассматриваем только ранее не использованные для композитного звонка записи
             ) s
        on f.dst <> 's'
           and f.dst <> s.dst
           and f.src <> s.dst
           and f.src = s.src
           and f.start <= s.start -- Вторая часть звонка должна начаться позже первой
           and f.end >= s.start and f.end <= s.end -- Вторая часть звонка должна закончиться между началом и концом первой части
where f.start > PERIOD_ADD(DATE_FORMAT(NOW(), '%Y%m'), -2) -- Ограничиваем хронологическую глубину
  and f.composite_call_id is null -- Рассматриваем только ранее не использованные для композитного звонка записи
  and f.status = 'queue'
  and f.src in ('1001','1002','1003', '1004')
  and f.src <> f.dst
  and f.dst not in (
      select phone
      from asterisk_manager__manager_phones
    )
limit 5;
