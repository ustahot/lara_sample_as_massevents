<?php

namespace App\Models\Massevent;

use App\Models\SingleSmsInterface;
use App\Services\SMS\SmsServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string status
 */
class MemberSmsNotification extends Model implements SingleSmsInterface
{
    use SoftDeletes;

    protected $table = 'massevents__member_sms_notifications';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getPhone(): string
    {
        return $this->real_used_phone_at_sending;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function send(SmsServiceInterface $service)
    {
        $this->service_class = $service::class;
        $this->save();
        $serviceResponse = $service->sendMessage($this->getPhone(), $this->getText()); // Фактическая отправка сервисом (разкоментить на бою)
        $this->service_sent_response = json_encode($serviceResponse ?? null, JSON_UNESCAPED_UNICODE);
        $this->status = 'sent';
        $this->sent_at = date('Y-m-d H:i:s');
        $this->save();

        return $this->service_sent_response;

    }
}
