<?php

namespace App\Models\Massevent;

use App\Services\Telegram\TelegramServiceInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string telegram_user_id
 * @property string content
 * @property string status
 * @property string service_response
 */
class ManagerTelegramNotification extends Model
{
    use SoftDeletes;

    protected $table = 'massevents__manager_telegram_notifications';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];


    public function send(TelegramServiceInterface $service)
    {
        return $service->sendMessageByTelegramUserId(telegramUserId: $this->telegram_user_id, content: $this->content);
    }

    public function setStatusAsSent()
    {
        $this->status = 'sent';
    }
}
