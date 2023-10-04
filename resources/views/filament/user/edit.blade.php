@if(app('registry')->get('requestId') === auth()->user()?->id)
    <a href="//gravatar.com" class="text-primary-600" target="_blank">@lang('to_add_or_change_avatar')</a>
@endif
