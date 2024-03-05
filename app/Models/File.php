<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use SoftDeletes;

    // Запрещается редактировать
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // При сохранении элемента
        static::saving(function (self $model) {
            $old = self::find($model->id);
            // Если файл поменялся
            if ($old && $old->file !== $model->file) {
                // Поменять данные файла
                $newData = FileHelper::getDataFile($model->file);
                if ($newData) {
                    $newData['user_id'] = auth()?->id();
                    $model->fill($newData);
                    // Удалить старый файл с сервера
                    FileHelper::deleteFile($old->file);
                }
            }

            // Удалить кэш
            cache()->flush();
        });

        // При удалении элемента
        static::deleting(function (self $model) {

            // При мягком удалении не сработает
            if ($model->isForceDeleting()) {
                // Удалить файл с сервера
                FileHelper::deleteFile($model->file);
            }

            // Удалить кэш
            cache()->flush();
        });
    }
}
