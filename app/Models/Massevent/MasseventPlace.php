<?php

namespace App\Models\Massevent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasseventPlace extends Model
{
    use SoftDeletes;

    protected $table = 'massevents__massevent_places';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];


    public function getMapUrl()
    {
        return $this->map_url;
    }

}
