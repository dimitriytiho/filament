<?php

namespace App\Providers\Filament;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $slug = config('filament.slug', 'admin');
        $filament = request()?->segment(1) === $slug;
        if ($filament) {

            // Записываем в registry данные
            $segment = request()?->segment(2);
            $requestId = request()?->segment(3);
            if (intval($requestId)) {
                $requestId = intval($requestId);
                $requestAction = request()?->segment(4);
            } else {
                $requestAction = $requestId;
                $requestId = null;
            }
            $registry = app('registry');
            $registry->set('slug', $slug);
            $registry->set('segment', $segment);
            $registry->set('requestId', $requestId);
            $registry->set('requestAction', $requestAction);

            // Глобальные настройки
            /*TextColumn::configureUsing(function (TextColumn $column): void {
                $column
                    ->sortable()
                    ->translateLabel();
            });
            TextInput::configureUsing(function (TextInput $input): void {
                $input
                    ->maxLength(255)
                    ->translateLabel();
            });
            Select::configureUsing(function (Select $option): void {
                $option
                    ->searchable()
                    ->translateLabel();
            });*/

            // Изменить шаблон вида
            /*FilamentView::registerRenderHook(
                'panels::global-search.after',
                fn (): View => view('filament.test'),
            );*/

            // Добавить пункт в меню
            /*Filament::serving(function () {
                Filament::registerNavigationItems([
                    NavigationItem::make()
                        ->label('Account')
                        ->url(fn (): string => route('filament.lk.resources.users.edit', 1), shouldOpenInNewTab: true)
                        ->icon('heroicon-o-user')
                        ->sort(80),
                ]);
            });*/
        }
    }
}
