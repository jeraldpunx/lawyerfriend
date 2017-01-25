<?php

class HomeController extends BaseController {

	public function index()
	{
		$title = 'Home';
		return View::make('index', compact('title'));
	}

	public function about()
	{
		$title = 'About';
		return View::make('about', compact('title'));
	}

	public function termsandconditions()
	{
		$title = 'Terms & Conditions';
		return View::make('termsandconditions', compact('title'));
	}

	public function getLogin() 
	{
		$title = 'Login';
		return View::make('auth.login', compact('title'));
	}

	public function postLogin() 
	{
		$user = array(
			'email' 	=> Input::get('email'),
			'password' 	=> Input::get('password'),
			);

		
		if(Auth::attempt($user, (Input::get('remember') == 'on') ? true : false)) {
			if(Auth::user()->active == 1 && Auth::user()->isBanned == 0) {
				return Redirect::route('home');
			}

			if(Auth::user()->active == 0) {
				$message = '<strong>Error!</strong> Account is not verified.';
			} else if (Auth::user()->isBanned == 1) {
				$message = '<strong>Error!</strong> Your account was banned for misconduct.';
			}

			Auth::logout();

		} else {
			$message = '<strong>Error!</strong> Account does not exist/Wrong combination.';
		}
		
		return Redirect::route('login')
			->with('flash', ['message'	=>	$message, 
				'type'		=>	'danger']);
	}

	public function getRegister() 
	{
		$title = 'Register';
		return View::make('auth.register', compact('title'));
	}

	public function getRegisterUser() 
	{
		$title = 'Register';
		return View::make('auth.register_user', compact('title'));
	}

	public function getRegisterLawyer() 
	{
		$title = 'Register';
		$states = State::orderBy('text', 'ASC')->get();
		$practice_areas = PracticeArea::orderBy('name', 'ASC')->get()->toArray();
		return View::make('auth.register_lawyer', compact('title', 'states', 'practice_areas'));
	}

	public function postRegisterUser() 
	{
		$input = Input::all();

		$rules = array(
			'first_name' 			=> 'required|max:255',
			'last_name' 			=> 'required|max:255',
			'email' 				=> 'required|email|max:255|unique:users',
			'password' 				=> 'required|confirmed|min:6',
			'password_confirmation' => 'same:password',
			'birthdate' 			=> 'required|date|date_format:m/d/Y',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = new User;
			$user->role 			= 'customer';
			$user->email 			= $input['email'];
			$user->password 		= Hash::make($input['password']);
			$user->activation_code 	= '';
			$user->active 			= 0;
			$user->isBanned 		= 0;
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			$user->save();

			$profile = new CustomerProfile;
			$profile->user_id = $user->id;
			$profile->save();

			$input['activation_code']  = $user->activation_code;
			Mail::send('emails.welcome', $input, function($message) use ($input)
			{
				$message->from('no-reply@site.com', "Lawyer Friend");
				$message->subject("Welcome to Lawyer Friend");
				$message->to($input['email']);
			});

			return Redirect::route('login')
				->with('flash', ['message'	=>	'Account created successfully! You should receive an e-mail shortly containing your account verification link.', 
								'type'		=>	'warning']);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postRegisterLawyer() 
	{


		$input = Input::all();

		$rules = array(
			'first_name' 			=> 'required|max:255',
			'last_name' 			=> 'required|max:255',
			'email' 				=> 'required|email|max:255|unique:users',
			'password' 				=> 'required|confirmed|min:6',
			'password_confirmation' => 'same:password',
			'birthdate' 			=> 'required|date|date_format:m/d/Y',
			'firm_name' 			=> 'required|max:255',
			'practice_areas.0' 		=> 'required|max:255',
			'license_id' 			=> 'required|max:255',
			'state_issued' 			=> 'required|max:255',
			'date_issued' 			=> 'required|date|date_format:m/d/Y',
		);

		$messages = array(
		    'practice_areas.0' => 'You need to add atleast one practice area!',
		);

		$validation = Validator::make($input, $rules, $messages);

		if($validation->passes()) {
			$user = new User;
			$user->role 			= 'lawyer';
			$user->email 			= $input['email'];
			$user->password 		= Hash::make($input['password']);
			$user->activation_code 	= '';
			$user->active 			= 0;
			$user->isBanned 		= 0;
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			$user->image 			= 'default.jpg';
			$user->save();

			$lawyer = new LawyerProfile;
			$lawyer->user_id 		= $user->id;
			$lawyer->lawyer_active 	= 0;
			$lawyer->firm_name 		= $input['firm_name'];
			$lawyer->license_number = $input['license_id'];
			$lawyer->state_issued 	= $input['state_issued'];
			$lawyer->date_issued 	= date("Y-m-d", strtotime($input['date_issued']));
			$lawyer->save();

			foreach ($input['practice_areas'] as $index => $practice_area) {
				$lawyer_pa 						= new LawyerPracticeArea;
				$lawyer_pa->lawyer_id 			= $lawyer->id;
				$lawyer_pa->practice_area_id 	= $practice_area;
				$lawyer_pa->state_id 			= empty($input['state']) 	? null : $input['state'][$index];
				$lawyer_pa->city_id 			= empty($input['city']) 	? null : $input['city'][$index];
				$lawyer_pa->street 				= empty($input['street']) 	? null : $input['street'][$index];
				$lawyer_pa->zip 				= empty($input['zip']) 		? null : $input['zip'][$index];
				$lawyer_pa->save();
			}


			$input['activation_code']  = $user->activation_code;
			Mail::send('emails.welcome', $input, function($message) use ($input)
			{
				$message->from('no-reply@site.com', "Lawyer Friend");
				$message->subject("Welcome to Lawyer Friend");
				$message->to($input['email']);
			});

			return Redirect::route('login')
				->with('flash', ['message'	=>	'You have been successfully registered. Please verify on email.', 
								'type'		=>	'warning']);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function verify($confirmation)
	{
		$user = User::where('activation_code', '=', $confirmation)->first();

		if($user->active == 1)
			return Redirect::route('login')
				->with('flash', ['message'	=>	'This link is not exist/expired.', 
					'type'		=>	'danger']);
		
		$user->active = 1;
		$user->save();

		return Redirect::route('login')
			->withInput(['email' => $user->email])
			->with('flash', ['message'	=>	'<strong>Account Verified!</strong>', 
				'type'		=>	'success']);
	}

	public function postLogout() 
	{
		Session::forget('profile');
		Auth::logout();
		return Redirect::route('home');
	}

	public function getRemind()
	{
		$title = 'Forgot Password';
		return View::make('auth.password', compact('title'));
	}

	public function postRemind()
	{
		switch ($response = Password::remind(Input::only('email'), function($message){ $message->subject('Lawyer Friend Password Reset'); }))
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::REMINDER_SENT:
				return Redirect::back()->with('status', Lang::get($response));
		}
	}

	public function getReset($token = null)
	{
		$title = 'Reset Password';
		if (is_null($token)) App::abort(404);

		return View::make('auth.reset', compact('title'))->with('token', $token);
	}

	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
			);

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::route('login')
					->with('flash', ['message'	=>	'You have successfully reset your password! You may login now.', 
						'type'		=>	'success']);
		}
	}

	public function blogs($blog_id = null)
	{
		if(empty($blog_id)) {
			$title = 'Blog';
			$blogs = Blog::select(DB::RAW("(case when char_length(content) < 200 then content else concat(left(content,200),'</p>') end) as content"), "id", "user_id", "title", "updated_at", "created_at")->orderBy('created_at', 'DESC')->paginate(5);

			return View::make('blog', compact('title', 'blogs'));
		} else {
			$token = Input::get('token');
			if(!empty($token)) {
				$achievement = Achievement::where('ref', '=', 'share')->get()->first();
				$user = User::where('remember_token', '=', $token)->get()->first();
				$customer_profile = CustomerProfile::where('user_id', '=', $user->id)->get()->first();
				

				$prevShareLink = ShareLink::where('user_id', '=', $user->id)->orderBy('created_at', 'DESC')->get()->first();
				if($prevShareLink) {
					if( date('Ymd') != date('Ymd', strtotime($prevShareLink->created_at)) && Request::getClientIp() != $user->ip_address ) {
						$sharelink = new ShareLink;
						$sharelink->blog_id 		= $blog_id;
						$sharelink->user_id 		= $user->id;
						$sharelink->points_reward 	= $achievement->points;
						$sharelink->created_at 		= date('Y-m-d H:i:s');
						$sharelink->save();

						$customer_profile->friend_points += $achievement->points;
						$customer_profile->save();
					}
				} else {
					if(Request::getClientIp() != $user->ip_address) {
						$sharelink = new ShareLink;
						$sharelink->blog_id 		= $blog_id;
						$sharelink->user_id 		= $user->id;
						$sharelink->points_reward 	= $achievement->points;
						$sharelink->created_at 		= date('Y-m-d H:i:s');
						$sharelink->save();

						$customer_profile->friend_points += $achievement->points;
						$customer_profile->save();
					}
				}
			}

			$blogs = Blog::where('id', '=', $blog_id)->get();
			$title = $blogs[0]->title;
			return View::make('blog', compact('title', 'blogs'));
		}
	}

	public function mail($token = null)
	{
		$title = "Mail";

		$user = User::where('activation_code', '=', $token)->get()->first();

		if(empty($user)) {
			return Redirect::route('home');
		}

		return View::make('mail', compact('title', 'user'));
	}

	public function sendMail($token = null)
	{
		$user = User::where('activation_code', '=', $token)->get()->first();

		if(empty($user)) {
			return Redirect::route('home');
		}

		$email_conf = Config::get('mail');
	
		$input = [
			'body' 	=> Input::get('body'),
			'user' 	=> $user,
			'bcc' 	=> $email_conf['from']
		];

		Mail::send('emails.sendmail', $input, function($message) use ($input)
		{
			$message->from('no-reply@site.com', "Lawyer Friend");
			$message->subject("New email | Lawyer Friend");
			$message->to($input['user']['email']);
			$message->replyTo($input['user']['email'], $input['user']['first_name'] . " " . $input['user']['last_name']);
			$message->bcc($input['bcc']['address'], $input['bcc']['name']);
		});

		return Redirect::back()
			->with('flash', ['message'	=>	'<strong>Successfully sent Mail!</strong>', 
						'type'		=>	'success']);
	}

	public function getCityByState()
	{
		return Response::json( City::where('state_id', '=', Input::get('state_id'))->get()->toArray() );
	}
}
