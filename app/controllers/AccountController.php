<?php

class AccountController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Account Controller
	|--------------------------------------------------------------------------
	|
	| Register, Forgotten password, Edit account details, view groups.
	| Register with Code & Register without code and request activation.
	|
	*/
	public function __construct()
    {
        $this->beforeFilter('auth', array('only' => 'getIndex'));
        $this->beforeFilter('guest', array('only' => 'getRegister'));
    }

	public function getIndex()
	{
		$data['user'] = Sentry::getUser();

		
		$data['age'] = retireveAgeInMonths();

		return View::make('account.index' , $data );
	}

	public function getRegister()
	{
		return View::make('account.register.index');
	}

	public function postRegister()
	{
		//Register Code can be passed in the URL!
		//register
		Input::flash();

		$data = Input::all();

		//Google + sign trauma
		$data['email'] = str_replace('+', '%2b', $data['email']);

		$rules = array(
			'code' 			=> 'required',
			'first_name'	=> 'required',
			'last_name'		=> 'required',
			'email'			=> 'required|email|unique:users,email',
			'password'		=> 'required|confirmed',
			);

		$validation = Validator::make( $data, $rules );

		if( $validation->fails() )
		{
			return Redirect::to('account/register')->withErrors($validation)->withInput();
		}



		 //create the admin user
	    $user = Sentry::createUser(array(
	        'email'     => $data['email'],
	        'password'  => $data['password'],
	        'first_name'=> $data['first_name'],
	        'last_name'	=> $data['last_name'],
	    ));

	    $data['activationCode'] = $user->getActivationCode();
	    $data['id'] = $user->id;

	    Mail::queue('emails.account.welcome', $data, function( $message ) use ( $data , $user )
		{
		    $message->to($user->email, $user->first_name)->subject('Please Activate Account');
		});

		return Redirect::to( 'login' )->withErrors('Successfully Registered. Please activate your account by checking your email.');
	}

	public function getActivate( $code = null , $id = null )
	{
		try
		{
		    // Find the user using the user id
		    $user = Sentry::findUserById($id);

		    // Attempt to activate the user
		    if ($user->attemptActivation($code))
		    {
		        
		        //Do we need to add groups here? As per registration code? /		        

		        Mail::queue('emails.account.activated', $data, function( $message ) use ( $user )
				{
				    $message->to($user->email, $user->first_name)->subject('Welcome.');
				});

		        // User activation passed
		        return Redirect::to( 'login' )->withErrors('Activation Successfull! Please Login');
		    }
		    else
		    {
		        // User activation failed
		        return Redirect::to( 'login' )->withErrors('The activation link you used is not valid.');
		    }
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return Redirect::to( 'login' )->withErrors('User was not found.');
		}
		catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
		{
		    return Redirect::to( 'login' )->withErrors('User is already activated!');
		}
	}

	public function getForgottenPassword()
	{
		return View::make('account.resetpassword');
	}

	public function postForgottenPassword()
	{
		$data = Input::all();
	}

	public function getEdit()
	{
		return 'edit yoself';
	}

	public function postEdit()
	{
		$data = Input::all();
		dd($data);
	}

}
