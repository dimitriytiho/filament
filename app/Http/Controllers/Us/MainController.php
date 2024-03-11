<?php

namespace App\Http\Controllers\Us;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MainController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        // Mail send
         /*try {
             \Illuminate\Support\Facades\Mail::send([], [], function ($message) {
                 $email = 'test@site.ru';
                 $subject = 'Test';
                 $text = 'Lorem ipsum dolor sit amet, consecrate radicalising edit.';
                 $message
                     ->to($email)
                     ->subject($subject)
                     ->text($text);
             });
             dd('Mail send');
         } catch (\Exception $e) {
             dd($e->getMessage());
         }*/

        $title = __('Main_page');
        return view(
            $this->view,
            compact('title')
        );
    }
}
