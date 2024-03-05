<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static string $disk = 'http';


    /**
     * Рассчитать размер кб в мб или байт в кб.
     *
     * @param int|float|null $size
     * @return ?int
     */
    public static function getBigSize(int|float|null $size): ?int
    {
        if ($size) {
            return round(floatval($size) / 1024);
        }
        return null;
    }

    /**
     * Получить данные файла.
     *
     * @param string|null $path
     * @param string|null $disk
     * @return array
     */
    public static function getDataFile(?string $path, string $disk = null): array
    {
        $res = [];
        if ($path) {
            if (!$disk) {
                $disk = self::$disk;
            }
            $disk = Storage::disk($disk);
            if ($disk->exists($path)) {
                $info = pathinfo($disk->path($path));
                $res = [
                    'file' => $path,
                    'name' => $info['filename'],
                    'mime_type' => $disk->mimeType($path),
                    'size' => $disk->size($path),
                    'ext' => $info['extension'], //pathinfo($disk->path($path), PATHINFO_EXTENSION),
                ];
            }
        }
        return $res;
    }

    /**
     * Удалить файл с сервера.
     *
     * @param string|null $path
     * @param string|null $disk
     * @return void
     */
    public static function deleteFile(?string $path, string $disk = null): void
    {
        if ($path) {
            if (!$disk) {
                $disk = self::$disk;
            }
            $disk = Storage::disk($disk);
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }
}
