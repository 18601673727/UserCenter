<?php

class HomeController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | You may wish to use controllers instead of, or in addition to, Closure
  | based routes. That's great! Here is an example controller method to
  | get you started. To route to this controller, just add the route:
  |
  | Route::get('/', 'HomeController@showWelcome');
  |
  */

    public function showWelcome()
    {
        return View::make('hello');
    }

    public function me()
    {
        echo '<pre>';
        dd(Sentry::getUser());
    }

    public function getLogin()
    {
        return View::make('login');
    }

    public function getRegister()
    {
        return View::make('register');
    }

    public function postLogin($value='')
    {
        try
        {
            // Login credentials
            $credentials = array(
                'email'    => Input::get('username'),
                'password' => Input::get('password'),
            );

            // Authenticate the user
            $user = Sentry::authenticate($credentials, false);
        }
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            echo 'Login field is required.';
        }
        catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            echo 'Password field is required.';
        }
        catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
        {
            echo 'Wrong password, try again.';
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            echo 'User was not found.';
        }
        catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            echo 'User is not activated.';
        }

        // The following is only required if the throttling is enabled
        catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
        {
            echo 'User is suspended.';
        }
        catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
            echo 'User is banned.';
        }


        try
        {
            // Log the user in
            Sentry::login($user, false);
        }
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            echo 'Login field is required.';
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            echo 'User not found.';
        }
        catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            echo 'User not activated.';
        }

        // Following is only needed if throttle is enabled
        catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
        {
            $time = $throttle->getSuspensionTime();

            echo "User is suspended for [$time] minutes.";
        }
        catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
            echo 'User is banned.';
        }
    }

    public function postRegister($value='')
    {
        try
        {
            // Let's register a user.
            $user = Sentry::register(array(
                'email'    => Input::get('username'),
                'password' => Input::get('password'),
            ));

            // Let's get the activation code
            $activationCode = $user->getActivationCode();

            // Send activation code to the user so he can activate the account
            echo '您的激活码是：' . $activationCode;
        }
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            echo 'Login field is required.';
        }
        catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            echo 'Password field is required.';
        }
        catch (Cartalyst\Sentry\Users\UserExistsException $e)
        {
            echo 'User with this login already exists.';
        }
    }

    public function getActive($value='')
    {
        return View::make('activeByCode');
    }

    public function postActive($value='')
    {
        try
        {
            // Find the user using the user id
            $user = Sentry::findUserByActivationCode(Input::get('activationCode'));

            // Attempt to activate the user
            if ($user->attemptActivation(Input::get('activationCode')))
            {
                // User activation passed
                return '验证成功！';
            }
            else
            {
                // User activation failed
                return '验证失败！';
            }
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            echo 'User was not found.';
        }
        catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
        {
            echo 'User is already activated.';
        }
    }
}
