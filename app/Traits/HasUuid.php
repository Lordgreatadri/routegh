<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getUuidKey()})) {
                $model->{$model->getUuidKey()} = (string) Str::uuid();
            }
        });
    }

    public function getUuidKeyName()
    {
        return 'uuid';
    }

    public function getUuidKey()
    {
        return $this->getUuidKeyName();
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
