<?php

namespace App\Traits;

use App\Helpers\ModelHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;

trait InfoTrait
{
    public string|false $controller = false; // classDummyController
    public string|false $class = false; // ClassDummy
    public string|false $action = false; // indexAction
    public string $snake = ''; // class_dummy
    public string $kebab = ''; // class-dummy
    public string|false $model = false; // App\Models\ClassDummy
    public string|false $table = false; // class_dummies
    public string|false $view = false; // index_action
    public string|false $route = false; // index-action из routes/web.php из метода name()
    public string $slug = ''; // route-slug
    public string $folder = '';
    public string|false $title = false;

    /**
     * @return void
     */
    protected function info(): void
    {
        if (request()?->route() && method_exists(request()?->route(), 'getActionName')) {
            $this->controller = Str::before(request()?->route()?->getActionName(), '@');
            //$this->controller = Str::before(class_basename(request()->route()->getActionName()), '@');
            $this->controller = Str::replace('App\Http\Controllers\\', '', $this->controller);
            $this->folder = Str::snake(Str::before($this->controller, '\\'));
            $this->controller = class_basename($this->controller);
            $this->class = Str::before($this->controller, 'Controller');
            $this->action = request()?->route()?->getActionMethod();
            $model = '\App\Models\\' . $this->class;
            $this->snake = Str::snake($this->class);
            $this->kebab = Str::kebab($this->class);
            $this->slug = Str::kebab($this->action);
            if (class_exists($model)) {
                $this->model = $model;
            }
            $table = Str::plural($this->snake); // к множественному числу
            if (ModelHelper::hasTable($table)) {
                $this->table = $table;
            }
            $snakeAction = Str::snake($this->action);
            if (view()->exists($view = $this->folder . '.' . $this->snake . '.' . $snakeAction)) {
                $this->view = $view;
            } elseif (view()->exists($view2 = $this->folder . '.' . $snakeAction)) {
                $this->view = $view2;
            }
            $this->route = request()?->route()?->getName();

            if (!Str::contains($snakeAction, ['index']) && Lang::has($snakeAction)) {
                $this->title = __($snakeAction);
            } elseif (Lang::has($table)) {
                $this->title = __($table);
            } elseif (Lang::has($this->snake)) {
                $this->title = __($this->snake);
            }
        }
    }

    /**
     * @param string $functionName = __FUNCTION__
     * @return void
     * @throws \ReflectionException
     */
    /*protected function info(string $functionName): void
    {
        $reflection = new ReflectionClass($this);
        $this->classSnake = Str::snake($reflection->getShortName());
        $this->methodSnake = Str::snake($reflection->getMethod($functionName)->getShortName());
    }*/
}
