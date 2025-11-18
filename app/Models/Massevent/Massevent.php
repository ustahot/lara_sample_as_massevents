<?php

namespace App\Models\Massevent;

use App\Models\HR\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer id
 * @property integer total_ticket_max_quantity
 */
class Massevent extends Model
{

    protected $table = 'massevents__massevents';
    use SoftDeletes;



    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    /**
     * Возвращает сотрудника, создавшего мероприятие
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creatorEmployee()
    {
        return $this->belongsTo(Employee::class, 'creator_employee_id');
    }

    /**
     * Возвращает сотрудника, изменившего мероприятие последним
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updaterEmployee()
    {
        return $this->belongsTo(Employee::class, 'updater_employee_id');
    }


    /**
     * Возвращает всех участников мероприятия
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(Member::class)->orderBy('name_for_massevent');
    }

    /**
     * Возвращает место проведения мероприятия
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function place()
    {
        return $this->belongsTo(MasseventPlace::class);
    }


    /**
     * Возвращает всех участников мероприятия
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function membersByCategory(MemberCategory $category)
    {
        return $this->hasMany(Member::class)->where('category_id', $category->id)->get();
    }

    public function totalTicketFactQuantity()
    {
        return $this->members->sum('ticket_fact');
    }
    public function totalTicketPlanQuantity()
    {
        return $this->members->sum('ticket_plan');
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDateInHumanFormat()
    {
        return date('d-m-Y', strtotime($this->massevent_date));
    }

    public function getTimeInHumanFormatFrom()
    {
        return date('H:i', strtotime($this->massevent_time) - 60*60); // минус час
    }

    public function getTimeInHumanFormatTo()
    {
        return date('H:i', strtotime($this->massevent_time));
    }
    public function getBookingNotifyMessage()
    {
        return $this->booking_notify_message;
    }
}
