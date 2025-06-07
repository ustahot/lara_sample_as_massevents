<?php

namespace App\Models\ETL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer id
 * @property string entity_code
 */
class ImportSession extends Model
{
    use SoftDeletes;

    protected $table = 'etl__import_sessions';
    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

}
