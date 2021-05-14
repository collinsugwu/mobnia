<?php

namespace App\Models\Traits;


trait HasSerialReference
{

    public static function getSerialReferenceColumnName(): string
    {
        return 'reference';
    }

    protected static function bootHasSerialReference()
    {
        static::created(function ($model) {
            //For easy identification
            $start = date('ymd');
            //randomness
            $random = random_chars(1, 'n');
            //Uniqueness
            $serial = str_pad($random.$model->id, 4, 0,STR_PAD_LEFT);

            $model->reference = "{$start}{$serial}";
            $model->save();
        });
    }

    public static function findByReference($ref)
    {
        return self::where(static::getSerialReferenceColumnName(), $ref)->first();
    }

    public static function searchByReference($id)
    {
        return self::where(static::getSerialReferenceColumnName(), 'like', "%$id%");
    }
}
