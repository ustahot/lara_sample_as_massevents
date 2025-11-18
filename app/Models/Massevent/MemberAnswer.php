<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int id
 * @property string member_hash_id
 * @property string request
 * @property string type
 * @property string answer
 * @property string status
 */
class MemberAnswer extends Model
{
    use SoftDeletes;

    protected $table = 'massevents__member_answers';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_hash_id', 'hash_id');
    }
}
