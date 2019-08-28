<?php


namespace App\Models\Traits;


use App\Models\BaseModel;
use Illuminate\Support\Str;

trait UsesUUID
{
    protected static function bootUsesUuid()
    {
        static::creating(function (BaseModel $model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string)Str::uuid();
            }
        });
    }

    public function getKeyType()
    {
        return 'string';
    }
}