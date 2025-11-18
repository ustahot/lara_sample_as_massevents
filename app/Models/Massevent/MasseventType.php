<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasseventType extends Model
{
    use SoftDeletes;

    protected $table = 'massevents__massevent_types';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

}
