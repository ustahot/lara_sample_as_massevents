<?php

namespace App\Models\Massevent;

use App\Models\CallInterface;
use App\Services\Call\CallServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string member_id
 * @property string phone_from
 * @property string real_used_phone_at_calling
 * @property string status
 * @property string data_from_service
 * @property string service_class
 * @property string record_link
 */
class CallToMember extends Model implements CallInterface
{
    use SoftDeletes;

    protected $table = 'massevents__call_to_members';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    public function getPhoneFrom(): string
    {
        return $this->phone_from;
    }

    public function getPhoneTo(): string
    {
        return $this->real_used_phone_at_calling;
    }

    public function do(CallServiceInterface $service)
    {
        $this->service_class = $service::class;
        $this->save();
        return $service->call($this);
    }

    public function setCompletedStatus()
    {
        $this->status = 'completed';
    }
}
