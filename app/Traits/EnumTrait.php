<?php

namespace App\Traits;

trait EnumTrait
{
    /**
     * Возвращает все cases.
     * @return array
     */
    public static function getCases(): array
    {
        $res = [];
        foreach (self::cases() as $case) {
            $res[$case?->name] = $case?->value;
        }
        return $res;
        /*$reflectionClass = new \ReflectionClass(static::class);
        return $reflectionClass->getConstants();*/
    }


    /**
     * Возвращает все name cases.
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
        /*return array_map(function ($case) {
            return $case->name;
        }, self::cases());*/
        /*$values = self::getConstants();
        $values = array_keys($values);
        return array_map('strtolower', $values);*/
    }

    /**
     * Возвращает все value cases.
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Возвращает value cases.
     * @param string|int|null $name
     * @return string|null
     */
    public static function value(string|int|null $name): ?string
    {
        return self::getCases()[$name] ?? null;
    }
}
