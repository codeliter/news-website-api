<?php


namespace App\Models;


use App\Models\Traits\UsesUUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use UsesUUID;

    /**
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F jS, Y h:ia');
    }

    /**
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F jS, Y h:ia');
    }

    /**
     * Get the Table name
     * @return mixed
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}