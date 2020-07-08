<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use App\Notifications\UserRegistered;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use KickAssSubtitles\Support\UserInterface;
use ReflectionException;
use Throwable;

/**
 * Class RegisterController.
 */
class RegisterController extends AbstractController
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Show the application registration form.
     *
     * @return Response
     */
    public function showRegistrationForm(): Response
    {
        /** @var ResponseFactory $response */
        $response = response();

        return $response->view(
            'controllers.auth.register.show_registration_form'
        );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function register(Request $request): RedirectResponse
    {
        /** @var Validator $validator */
        $validator = $this->validator($request->all());
        $validator->validate();
        /** @var User $user */
        $user = $this->create($request->all());
        $user->notify(new UserRegistered($user));
        event(new Registered($user));

        /** @var Redirector $redirect */
        $redirect = redirect();

        return $redirect->back()->with(
            'status',
            __('messages.register_activate')
        );
    }

    /**
     * @return RedirectResponse
     *
     * @throws Throwable
     */
    public function registerTemporary(): RedirectResponse
    {
        $user = $this->userRepository->registerTemporary();

        /** @var StatefulGuard $auth */
        $auth = auth();
        $auth->login($user, true);

        /** @var Redirector $redirect */
        $redirect = redirect();

        return $redirect->back()->with(
            'status',
            __('messages.register_temporary')
        );
    }

    /**
     * @param string $token
     *
     * @return RedirectResponse
     *
     * @throws Throwable
     */
    public function activate(string $token): RedirectResponse
    {
        try {
            $user = $this->userRepository->activate($token);
            /** @var StatefulGuard $auth */
            $auth = auth();
            $auth->login($user);
            $status = __('messages.activate_success');
        } catch (Exception $e) {
            /** @var Log $logger */
            $logger = logger();
            $logger->error((string) $e);
            $status = __('messages.activate_error');
        }

        /** @var Redirector $redirect */
        $redirect = redirect();

        return $redirect->to($this->redirectTo)->with('status', $status);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return ValidatorContract
     *
     * @throws ReflectionException
     */
    protected function validator(array $data): ValidatorContract
    {
        /** @var ValidatorFactory $factory */
        $factory = app(ValidatorFactory::class);

        return $factory->make($data, [
            UserInterface::USERNAME => 'bail|required|regex:/^[a-z]{1}[a-z0-9]{1,19}$/|unique:'.User::getTableName(),
            UserInterface::EMAIL => 'bail|required|string|email|max:255|unique:'.User::getTableName(),
            UserInterface::PASSWORD => 'bail|required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return UserInterface
     */
    protected function create(array $data): UserInterface
    {
        return $this->userRepository->register(
            $data[UserInterface::USERNAME],
            $data[UserInterface::EMAIL],
            $data[UserInterface::PASSWORD]
        );
    }
}
