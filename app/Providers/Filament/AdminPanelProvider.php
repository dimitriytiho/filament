<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Pages\Auth\EditProfile;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Widgets;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Blade;
use Filament\Pages\Dashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentAsset::register([
            Css::make('custom-stylesheet', __DIR__ . '/../../../resources/css/custom.css'), // php artisan filament:assets
        ]);
        $color = Color::Blue;
        $rgb = 'rgb(' . $color[600] . ')';
        $slug = config('filament.slug', 'admin');
        return $panel
            ->domain(config('app.url'))
            ->default()
            ->id($slug)
            ->path($slug)
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->authGuard('web')

            // Ссылка на аккаунт
            ->profile(EditProfile::class)
            /*->userMenuItems([
                'profile' => MenuItem::make()->url(fn (): string => route("filament.{$slug}.resources.users.edit", auth()->id()))
            ])*/

            ->colors([
                'primary' => $color,
            ])
            /*->navigationItems([
                NavigationItem::make('dashboard')
                    ->label(fn (): string => __('filament-panels::pages/dashboard.title'))
                    ->url(fn (): string => Dashboard::getUrl())
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.dashboard'))
                    ->icon('heroicon-o-presentation-chart-line')
                    ->group('Reports')
                    ->sort(1),
            ])*/
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            /*->renderHook(
                'panels::body.start',
                fn (): string => Blade::render('filament.inc.body_start'),
            )*/
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->plugins([
                FilamentProgressbarPlugin::make()->color($rgb),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
