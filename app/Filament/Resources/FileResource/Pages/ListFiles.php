<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Helpers\FileHelper;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;

class ListFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload_files')
                ->modalHeading(__('Upload files'))
                ->form([
                    FileUpload::make('files')
                        ->disk('http')
                        ->directory('file/' . date('m-Y'))
                        ->multiple()
                        ->openable()
                        ->required()
                        ->maxSize(config('filament.file.size')) // Указать в кб
                        ->acceptedFileTypes(config('filament.file.mine_type')) // Указать разрешаемую миме-типы
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
                        ->hint(__('max') . FileHelper::getBigSize(config('filament.file.size')) . __('mb') . ', ' . implode(', ', config('filament.file.mine_type')))
                        ->translateLabel(),
                ])
                ->action(function (Action $action, array $data) {
                    //$data = $action->getFormData();
                    if (!empty($data['files'])) {
                        $files = [];
                        // Формирование данные для сохранения
                        $userId = auth()?->id();
                        foreach ($data['files'] as $path) {
                            $file = FileHelper::getDataFile($path);
                            $file['user_id'] = $userId;
                            $file['created_at'] = $file['updated_at'] = now();
                            $files[] = $file;
                        }

                        if ($files) {

                            // Сохранить файлы
                            $model = self::$resource::$model;
                            $model::insert($files);

                            // Сообщение об успешном сохранении
                            Notification::make()
                                ->title(__('successfully_changed'))
                                ->success()
                                ->send();
                        }
                    }
                })
                ->translateLabel(),
        ];
    }
}
