@extends('layouts.us')
@section('header')
    @include('us.inc.header')
@endsection
@section('content')
    @if(request()->is('/'))
        <section class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">Welcome to Blog Home!</h1>
                    <p class="lead mb-0">A Bootstrap 5 starter layout for your next blog homepage</p>
                </div>
            </div>
        </section>
    @endif
    <div class="container">
        <div class="row">
            {{-- Blog entries --}}
            <div class="col-lg-8">
                {{--https://packagist.org/packages/graham-campbell/markdown--}}
                {{--@markdown(\App\Models\Book::find(1)->body)--}}
                <div class="row">

                    {{-- Content --}}
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <a href="#">
                                <img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="...">
                            </a>
                            <div class="card-body">
                                <h2 class="card-title h4">Post Title</h2>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p>
                                <a class="btn btn-primary btn-pulse" href="#">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <a href="#">
                                <img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="...">
                            </a>
                            <div class="card-body">
                                <h2 class="card-title h4">Post Title</h2>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p>
                                <a class="btn btn-primary btn-pulse" href="#">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <a href="#">
                                <img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="...">
                            </a>
                            <div class="card-body">
                                <h2 class="card-title h4">Post Title</h2>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p>
                                <a class="btn btn-primary btn-pulse" href="#">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <a href="#">
                                <img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="...">
                            </a>
                            <div class="card-body">
                                <h2 class="card-title h4">Post Title</h2>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p>
                                <a class="btn btn-primary btn-pulse" href="#">Подробнее</a>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="col-12">
                        <nav class="mb-5" aria-label="Pagination">
                            <ul class="pagination justify-content-center my-4">
                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Newer</a></li>
                                <li class="page-item active" aria-current="page"><a class="page-link" href="#!">1</a></li>
                                <li class="page-item"><a class="page-link" href="#!">2</a></li>
                                <li class="page-item"><a class="page-link" href="#!">3</a></li>
                                <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                                <li class="page-item"><a class="page-link" href="#!">15</a></li>
                                <li class="page-item"><a class="page-link" href="#!">Older</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- Side widgets --}}
            <div class="col-lg-4">
                <div class="input-group mb-4">
                    <input class="form-control" type="text" placeholder="Что ищем..." aria-label="Что ищем..." aria-describedby="button-search">
                    <button class="btn btn-primary btn-pulse" id="button-search" type="button">Найти</button>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Последние новости</div>
                    <div class="card-body">
                        <div>
                            <h5>News last</h5>
                            <div class="small text-muted">January 1, 2023</div>
                            <p>You can put anything you want inside of these side widgets. They are easy to use, and feature the Bootstrap 5 card component!</p>
                            <div class="mb-3 border-bottom"></div>
                        </div>
                        <div>
                            <h5>News text</h5>
                            <div class="small text-muted">January 1, 2023</div>
                            <p>You can put anything you want inside of these side widgets. They are easy to use, and feature the Bootstrap 5 card component!</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#">Все новости</a>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Теги</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li>
                                        <a href="#">Web Design</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li>
                                        <a href="#">JavaScript</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">Описание</div>
                    <div class="card-body">Здесь краткое описание о чём сайт и пр.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mt-5 mb-4">Form callback</h2>
            </div>
        </div>
        <div class="row justify-content-center mb-5">
            <div class="col-6">
                <form class="validate">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label visually-hidden">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name*" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label visually-hidden">Phone</label>
                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone*" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label visually-hidden">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email*" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label visually-hidden">Message</label>
                        <textarea class="form-control" name="message" id="message" rows="3" placeholder="Message"></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @include('us.inc.footer')
@endsection
