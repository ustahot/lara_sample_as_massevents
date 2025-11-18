<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string id
 * @property string text
 * @property string status
 * @property string member_id_collection
 * @property string sent_at
 */
class MassSms extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'massevents__mass_sms';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];
}
