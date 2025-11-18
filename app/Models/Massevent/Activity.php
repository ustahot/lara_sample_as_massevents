<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $table = 'massevents_activities';

    protected $fillable = [
        'key',
        'massevent_id',
        'member_id',
    ];

    protected $casts = [
        // При необходимости можно добавить касты для дат
    ];

    /**
     * Связь с мероприятием
     */
    public function massevent(): BelongsTo
    {
        return $this->belongsTo(Massevent::class);
    }

    /**
     * Связь с участником
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope для поиска по ключу
     */
    public function scopeWhereKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Scope для мероприятий
     */
    public function scopeForMassevent($query, int $masseventId)
    {
        return $query->where('massevent_id', $masseventId);
    }

    /**
     * Scope для участников
     */
    public function scopeForMember($query, int $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    /**
     * Создать активность изменения статуса
     */
    public static function createStatusChange(int $masseventId, ?int $memberId, string $newStatus, ?string $oldStatus = null): self
    {
        return self::create([
            'key' => 'change_status_to_' . $newStatus,
            'massevent_id' => $masseventId,
            'member_id' => $memberId,
        ]);
    }



    
    /**
     * Получить активности по ключу, мероприятию и дате
     */
    public static function getByKeyForDateAndMassevent(string $key, string $date, int $masseventId): Collection
    {
        return self::query()
            ->whereKey($key)
            ->forMassevent($masseventId)
            ->forDate($date)
            ->get();
    }




    /**
     * Получить все активности по ключу
     */
    public static function getByKey(string $key)
    {
        return self::whereKey($key)->get();
    }


    public function scopeWithKey($query, string $key)
    {
        return $query->where('key', $key);
    }
    /**
     * Scope для фильтрации по ключу, мероприятию и дате
     */
    public function scopeForMasseventKeyAndDate($query, int $masseventId, string $startDateTime, string $endDateTime)
    {
        return $query
            ->where('massevent_id', $masseventId)
            ->whereBetween('created_at', [$startDateTime, $endDateTime]);
    }
    
    /**
     * Получить все активности для мероприятия
     */
    public static function getForMassevent(int $masseventId)
    {
        return self::forMassevent($masseventId)->get();
    }

    /**
     * Получить все активности для участника
     */
    public static function getForMember(int $memberId)
    {
        return self::forMember($memberId)->get();
    }
}