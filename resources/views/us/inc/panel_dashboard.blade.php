@if(auth()->check() && auth()->user()->isAdmin())
    <section class="bg-dark py-1 px-2">
        <a href="{{ session()->get('back_link_admin', route('filament.' . config('filament.slug') . '.pages.dashboard')) }}" class="me-3" title="@lang('dashboard')">
            <i class="fas fa-tachometer-alt"></i>
        </a>
        @if(!empty($id) && !empty($kebab) && Route::has('filament.' . config('filament.slug') . ".resources.{$kebab}.edit"))
            <a href="{{ route('filament.' . config('filament.slug') . ".resources.{$kebab}.edit", $id) }}" class="me-3" target="_blank" title="@lang('edit')">
                <i class="fas fa-edit"></i>
            </a>
        @endif
    </section>
@endif
