<?php

namespace App\Models\Massevent;

use App\Jobs\Massevent\Member\LinkManagerJob;
use App\Models\CRM\Contact;
use App\Models\HR\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer id
 * @property integer massevent_id
 * @property string phone_for_massevent
 * @property string name_for_massevent
 * @property string status
 * @property integer ticket_plan
 * @property integer ticket_fact
 * @property integer ticket_set_id
 * @property integer ticket_url
 * @property string hash_id
 * @property string manager_guid
 * @property string manager_status
 * @property integer manager_id
  */
class Member extends Model
{
    use SoftDeletes;

    protected $table = 'massevents__members';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    /**
     * Возвращает контакта из модуля CRM, связанного с этим участником мероприятия, если таковой существует
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }


    /**
     * Возвращает мероприятие
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function massevent()
    {
        return $this->belongsTo(Massevent::class);
    }

    protected static function booted()
    {
        static::updated(function ($member) {
            if ($member->isDirty('status')) {
                Activity::createStatusChange(
                    $member->massevent_id,
                    $member->id,
                    $member->status,
                    $member->getOriginal('status')
                );
            }
        });
    }

    /**
     * Активности участника
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'member_id');
    }

    /**
     * Примечания по участнику
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MemberNote::class);
    }

    public function notesQuantity(): int
    {
        return $this->notes->count();
    }

    public function category()
    {
        return $this->belongsTo(MemberCategory::class);
    }

    public function ticketSet()
    {
        return $this->belongsTo(TicketSet::class);
    }

    public function smsNotifications()
    {
        return $this->hasMany(MemberSmsNotification::class);
    }

    /**
     */
    public function setHashId()
    {
        $this->hash_id = $this->massevent_id . '_' .  $this->id . '_' . hash('sha256'
            , $this->massevent_id . $this->id . 'gwd_any_string' . $this->phone_for_massevent . $this->name_for_massevent
        );
    }

    public static function findByHashId(string $hashId)
    {
        return self::where('hash_id', $hashId)->first();
    }

    public static function findByPhoneForMassevent(string $phoneForMassevent): ?self
    {
        return self::where('phone_for_massevent', $phoneForMassevent)->first();
    }

    public function answers()
    {
        return $this->hasMany(MemberAnswer::class, 'member_hash_id', 'hash_id');
    }

    public function setStatusAsUndefinedManagerGuid()
    {
        $this->manager_status = 'undefined_guid';
    }

    public function setStatusAsLinked()
    {
        $this->manager_status = 'linked';
    }

    public function linkManager()
    {
        LinkManagerJob::dispatch($this);
    }

    public function setManagerStatusNotified()
    {
        $this->manager_status = 'notified';
    }

    public function setManagerStatusUndefined()
    {
        $this->manager_status = 'manager_undefined';
    }

    public function setManagerStatusNotifyFailed()
    {
        $this->manager_status = 'notify_failed';
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getTicketUrl()
    {

        return $this->ticketSet()->long_url;
    }
}
