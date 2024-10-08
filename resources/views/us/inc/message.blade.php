<div id="alert"></div>
{{-- Сообщения об ошибках --}}
@if(session()->has('error') || (isset($errors) && $errors->any()))
    <div class="container">
        <div class="row mt-2">
            <div class="col">
                <div class="alert alert-danger alert-dismissible fade show py-3 px-4" role="alert">
                    @if($errors->any())
                        <ul class="list-unstyled mb-0">
                            @foreach($errors->all() as $error)
                                <li class="mt-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if($errors->any() && session()->has('error'))
                        <br>
                    @endif
                    @if(session()->has('error'))
                        <span>{{ session('error') }}</span>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="@lang('Close')"></button>
                </div>
            </div>
        </div>
    </div>
@endif
{{-- Сообщения об успехе --}}
@if(session()->has('message') || session()->has('success'))
    <div class="container">
        <div class="row mt-2">
            <div class="col">
                <div class="alert alert-success alert-dismissible fade show py-3 px-4" role="alert">
                    @if(session()->has('message'))
                        <span>{{ session('message') }}</span>
                    @endif
                    @if(session()->has('message') && session()->has('success'))
                        <br>
                    @endif
                    @if(session()->has('success'))
                        <span>{{ session('success') }}</span>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="@lang('Close')"></button>
                </div>
            </div>
        </div>
    </div>
@endif
{{-- Сообщения информационные --}}
@if(session()->has('info'))
    <div class="container">
        <div class="row mt-2">
            <div class="col">
                <div class="alert alert-info alert-dismissible fade show py-3 px-4" role="alert">
                    <span>{{ session('info') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="@lang('Close')"></button>
                </div>
            </div>
        </div>
    </div>
@endif
