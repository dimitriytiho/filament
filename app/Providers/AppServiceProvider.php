<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\{DatabaseCheck, DatabaseSizeCheck, DebugModeCheck, EnvironmentCheck, OptimizedAppCheck, ScheduleCheck, UsedDiskSpaceCheck};
use Spatie\Health\Facades\Health;
use Illuminate\Support\Facades\Validator;
use App\Helpers\GoogleRecaptchaHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Добавляем правила в валидатор
        $this->validator();

        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            ScheduleCheck::new()->heartbeatMaxAgeInMinutes(5),
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            DatabaseSizeCheck::new()->failWhenSizeAboveGb(errorThresholdGb: 5.0),
        ]);
    }

    private function validator(): void
    {
        // Добавляем Google ReCaptcha в валидатор
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            return GoogleRecaptchaHelper::check($value, request()->ip());
        });

        // Валидатор номера телефона (допускаются +()- и цифры)
        Validator::extend('phone', function ($attribute, $value, $parameters) {
            return preg_match('/^[\+\(\)\- 0-9]+$/', $value) && strlen($value) > 10;
        });
    }
}
