<?php

namespace App\Filament\Pages;

use App\Filament\Traits\PageTrait;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class Additionally extends Page
{
    use PageTrait;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';
    protected static string $view = 'filament.pages.additionally';
    protected static ?string $title = 'additionally';
    protected static ?int $navigationSort = 99;


    /**
     * Доступ к данному ресурсу ресурс.
     *
     * @param string $action
     * @param Model|null $record
     * @return bool
     */
    public static function can(string $action, ?Model $record = null): bool
    {
        return true;
        //return parent::can($action, $record);
    }

    /**
     * Группа в левом меню.
     *
     * @return string|null
     */
    public static function getNavigationGroup(): ?string
    {
        return __('management');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make(__('cache'))
                //->color('warning')
                ->action(function (): void {

                    //

                    Notification::make()
                        ->title(__('completed_successfully'))
                        ->success()
                        ->send();
                })
        ];
    }

    public static function cache()
    {
        dd(123);
        /*cache()->flush();
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        return redirect()->back();*/
    }
}
