<?php

namespace App\Models\ETL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int session_id
 * @property int massevent_id
 * @property string category_code
 * @property string outer_id
 * @property string name
 * @property string phone
 * @property string manager_name
 * @property string member_guid
 * @property string manager_guid
 */
class StageExtractMember extends Model
{
    use SoftDeletes;

    protected $table = 'etl__stage_extract_members';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

}
