<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int id
 * @property string code
 */
class MemberCategory extends Model
{
    use SoftDeletes;

    protected $table = 'massevents__member_categories';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];


    /**
     * @param string $code
     * @return MemberCategory|null
     */
    public static function findByCode(string $code): ?self
    {
        return self::where('code', $code)->first();
    }

}
