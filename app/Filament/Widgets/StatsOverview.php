<?php

namespace App\Filament\Widgets;

use App\Helpers\FilamentHelper;
use App\Models\Dummy;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Data
        $cacheTime = FilamentHelper::cacheTime();
        $dummiesCount = cache()->remember('dummies_count', $cacheTime, fn (): int => Dummy::count());
        $dummiesUrl = FilamentHelper::getUrl('dummies');
        $usersCount = cache()->remember('users_count', $cacheTime, fn (): int => User::count());
        $usersUrl = FilamentHelper::getUrl('users');

        return [
            Stat::make(__('dummies'), $dummiesCount)
                ->url($dummiesUrl)
                //->description('32k increase')
                //->descriptionIcon('heroicon-o-users')
                //->color('success')
                ->icon('heroicon-o-rectangle-stack'),
            Stat::make(__('users'), $usersCount)
                ->url($usersUrl)
                ->icon('heroicon-o-users'),
        ];
    }
}
