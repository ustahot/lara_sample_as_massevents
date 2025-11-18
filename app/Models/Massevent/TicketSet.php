<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string long_url
 * @property string short_url
 */
class TicketSet extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'massevents__ticket_sets';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];


    public function member()
    {
        return $this->hasOne(Member::class);
    }

}
