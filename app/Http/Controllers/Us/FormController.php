<?php

namespace App\Http\Controllers\Us;

use App\Helpers\StrHelper;
use App\Http\Controllers\Controller;
use App\Jobs\EmailSender;
use App\Models\{Form, User};
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\{RedirectResponse, Request};

class FormController extends Controller
{
    public function contactUs(Request $request): RedirectResponse
    {
        $request->merge(['phone' => StrHelper::phoneFormat($request->get('phone'))]);
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'min:11', 'max:11'],
            'message' => ['nullable', 'string', 'max:255'],
            'policy' => ['required'],
        ];
        if (app()->environment('production')) {
            $rules += ['recaptcha' => ['required', 'recaptcha']];
        }
        $request->validate($rules);

        // Данные формы
        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $message = $request->get('message');

        // Данные пользователя
        $userData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];
        // Сохраняем данные пользователя
        $user = $this->saveUser($userData);

        // Данные формы
        $formData = [
            'user_id' => $user->id,
            'message' => $message,
            'image' => $imageUrl ?? null,

        ];
        // Сохраняем данные формы
        $form = $this->saveForm($formData);

        // Данные для отправки писем
        $site = config('app.name');
        $userMessage = view('sender.email.contact_us.user')->render();
        $managerMessage = view('sender.email.contact_us.manager', compact('site', 'name', 'email', 'phone', 'message'))->render();
        $userEmailData['subject'] = __('sender.you_submitted_form') . $site;
        $managerEmailData['subject'] = __('sender.form_sent') . $site;
        $managerEmails = explode(',', getenv('MAIL_MANAGERS'));

        // Отправка письма пользователю
        /*EmailSender::dispatch($userMessage, $email, $userEmailData);
        // Отправка письма менеджеру
        if (app()->environment('production')) {
            EmailSender::dispatch($managerMessage, $managerEmails, $managerEmailData);
        }*/

        return redirect()
            ->route('main')
            ->with('success', $userMessage);
    }

    /**
     * Сохраняем данные пользователя
     *
     * @param array $data
     * @return User
     * @throws BindingResolutionException
     */
    protected function saveUser(array $data): User
    {
        $user = User::withTrashed()
            ->where('phone', $data['phone'])
            ->orWhere('email', $data['email'])
            ->first();
        if (!$user) {
            $user = app()->make(User::class);
        }

        // Проверка на уникальность
        if (User::where('email', $data['email'])->exists()) {
            unset($data['email']);
        }
        if (User::where('phone', $data['phone'])->exists()) {
            unset($data['phone']);
        }

        // Проверка пароля
        if (!$user->password) {
            $user->password = rand(10000000, 99999999);
        }

        $user->fill($data);
        if ($user->deleted_at) {
            $user->restore();
        } else {
            $user->save();
        }
        return $user;
    }

    /**
     * Сохраняем данные формы
     *
     * @param array $data
     * @return Form
     * @throws BindingResolutionException
     */
    protected function saveForm(array $data): Form
    {
        $form = app()->make(Form::class);
        $form->fill($data);
        $form->save();
        return $form;
    }
}
