<?php

namespace App\UseCases\Massevent;

use App\Models\HR\Employee;
use App\Models\Massevent\Massevent;

class MasseventBuilder
{
    private array $request;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Создает экземпляр модели Мероприятие, а также экземпляры сопутствующих моделей
     *
     * @param array $validatedRequest
     * @return Massevent
     */
    public static function createModelInstances(array $validatedRequest): Massevent
    {


        // todo Эту логику перенести в Обсервер, который повесить на контроллер
        // Если в запросе не пришел guid_1с сотрудника-создателя мероприятия, создаем мероприятие без него
        if (!isset($validatedRequest['guid_1c'])) {
            $massevent = new Massevent($validatedRequest);
            $massevent->save();
            return $massevent;
//            return Massevent::create($validatedRequest);
        }

        $builder = new self($validatedRequest);
        $employee = Employee::findByCode($builder->request['guid_1c']);

        // Если такого сотрудника не существовало, создаем его
        if (!isset($employee)) {

            $employee = new Employee();
            $employee->code = $builder->request['guid_1c'];
            $employee->save();

        }

        unset($builder->request['guid_1c']);
        $validatedRequest['creator_employee_id'] = $employee->id;

        return Massevent::create($validatedRequest);
    }

    /**
     * Создает экземпляр модели Мероприятие, а также экземпляры сопутствующих моделей
     *
     * @param array $validatedRequest
     * @return Massevent
     */
    public static function updateModelInstances(array $validatedRequest, Massevent $modelInstance): Massevent
    {

        // Если в запросе не пришел guid_1с сотрудника-создателя мероприятия, создаем мероприятие без него
        if (!isset($validatedRequest['guid_1c'])) {
            $modelInstance->update($validatedRequest);

            return $modelInstance;
        }

        $builder = new self($validatedRequest);
        $employee = Employee::findByCode($builder->request['guid_1c']);

        // Если такого сотрудника не существовало, создаем его
        if (!isset($employee)) {

            $employee = new Employee();
            $employee->code = $builder->request['guid_1c'];
            $employee->save();

        }

        unset($builder->request['guid_1c']);
        $validatedRequest['updater_employee_id'] = $employee->id;
        $modelInstance->update($validatedRequest);

        return $modelInstance;
    }
}
