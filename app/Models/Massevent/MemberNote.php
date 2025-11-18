<?php

namespace App\Models\Massevent;

use App\Models\HR\Employee;
use App\Observers\Massevent\MemberNoteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([MemberNoteObserver::class])]
class MemberNote extends Model
{
    use SoftDeletes;

    protected $table = 'massevents__member_notes';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];


    /**
     * Возвращает участника мероприятия, по которому примечание
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class,'member_id');
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
