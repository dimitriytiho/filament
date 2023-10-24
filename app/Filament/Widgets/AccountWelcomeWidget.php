<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AccountWelcomeWidget extends Widget
{
    protected static ?int $sort = -3;
    protected static string $view = 'filament.widgets.account_welcome';
    protected int | string | array $columnSpan = 12;
}
