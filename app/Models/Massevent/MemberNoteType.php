<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Model;

class MemberNoteType extends Model
{
    protected $table = 'massevents__member_note_types';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    /**
     * @param string $code
     * @return MemberNoteType|null
     */
    public static function findByCode(string $code): ?self
    {
        return self::where('code', $code)->first();
    }

    public static function getUserType()
    {
        return self::findByCode('user');
    }
}
