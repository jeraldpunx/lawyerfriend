<?php

class UsersController extends \BaseController {
	private $profile;
	public function __construct()
	{
		if(Auth::user()->role == 'customer') {
			$profile = CustomerProfile::where('user_id', '=', Auth::user()->id)->get();
		} else if(Auth::user()->role == 'lawyer') {
			$profile = LawyerProfile::where('user_id', '=', Auth::user()->id)->get();
		}

		$this->profile = $profile;
	}

	public function index()
	{
		return Redirect::route('overview');
	}

	public function overview()
	{
		$title = 'Overview';
		$profile = $this->profile->first();

		$friendlevels = FriendLevel::orderBy('points_needed', 'ASC')->get();

		$progress = array();

		$progress['personal']['points'] = 0;
		$progress['personal']['numberOfTextfield'] = 5;
		if(!empty(Auth::user()->first_name)) 	$progress['personal']['points']++;
		if(!empty(Auth::user()->last_name)) 	$progress['personal']['points']++;
		if(!empty(Auth::user()->birthdate)) 	$progress['personal']['points']++;
		if(!empty(Auth::user()->gender)) 		$progress['personal']['points']++;
		if(!empty(Auth::user()->ethnicity)) 	$progress['personal']['points']++;

		$progress['contact']['points'] = 0;
		$progress['contact']['numberOfTextfield'] = 7;
		if(!empty(Auth::user()->home_number)) 		$progress['contact']['points'] ++;
		if(!empty(Auth::user()->mobile_number)) 	$progress['contact']['points'] ++;
		if(!empty(Auth::user()->email)) 			$progress['contact']['points'] ++;
		if(!empty(Auth::user()->street)) 			$progress['contact']['points'] ++;
		if(!empty(Auth::user()->city_id)) 			$progress['contact']['points'] ++;
		if(!empty(Auth::user()->state_id)) 			$progress['contact']['points'] ++;
		if(!empty(Auth::user()->zip)) 				$progress['contact']['points'] ++;

		$progress['work']['points'] = 0;
		$progress['work']['numberOfTextfield'] = 5;
		if(!empty($profile->work_employed)) 		$progress['work']['points']++;
		if(!empty($profile->work_retired)) 			$progress['work']['points']++;
		if(!empty($profile->work_position)) 		$progress['work']['points']++;
		if(!empty($profile->work_salary)) 			$progress['work']['points']++;
		if(!empty($profile->work_income)) 			$progress['work']['points']++;

		$progress['trans']['points'] = 0;
		$progress['trans']['numberOfTextfield'] = 4;
		if(!empty($profile->trans_own)) 			$progress['trans']['points']++;
		if(!empty($profile->trans_type)) 			$progress['trans']['points']++;
		if(!empty($profile->trans_insurance)) 		$progress['trans']['points']++;
		if(strlen($profile->trans_tickets) > 0)		$progress['trans']['points']++;

		$progress['edu']['points'] = 0;
		$progress['edu']['numberOfTextfield'] = 5;
		if(!empty($profile->edu_hs_name)) 			$progress['edu']['points']++;
		if(!empty($profile->edu_hs_gd)) 			$progress['edu']['points']++;
		if(!empty($profile->edu_coll_name)) 		$progress['edu']['points']++;
		if(!empty($profile->edu_coll_degree)) 		$progress['edu']['points']++;
		if(!empty($profile->edu_coll_gd)) 			$progress['edu']['points']++;

		$progress['crim']['points'] = 0;
		$progress['crim']['numberOfTextfield'] = 1;
		if(!empty($profile->crim_arrests)) 			$progress['crim']['points']++;

		return View::make('user.overview', compact('title', 'profile', 'friendlevels', 'progress'));
	}

	public function getProfilePersonal()
	{
		$title = 'Profile';

		$profile = $this->profile;
		$ethnicities = Ethnicity::orderBy('name', 'ASC')->get();

		$progressPoints = 0;
		$numberOfTextfield = 5;

		if(!empty(Auth::user()->first_name)) 	$progressPoints += 1;
		if(!empty(Auth::user()->last_name)) 	$progressPoints += 1;
		if(!empty(Auth::user()->birthdate)) 	$progressPoints += 1;
		if(!empty(Auth::user()->gender)) 		$progressPoints += 1;
		if(!empty(Auth::user()->ethnicity)) 	$progressPoints += 1;


		return View::make('user.profile.personal', compact('title', 'profile', 'ethnicities', 'progressPoints', 'numberOfTextfield'));
	}

	public function postProfilePersonal()
	{
		$input = Input::all();

		$rules = array(
				'first_name' 		=> 'required|max:255',
				'last_name' 		=> 'required|max:255',
				'birthdate' 		=> 'required|date|date_format:m/d/Y',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find(Auth::user()->id);
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			$user->ethnicity 		= empty($input['ethnicity']) ? null : $input['ethnicity'];
			$user->gender 			= isset($input['gender']) ? $input['gender'] : null;
			$user->save();

			helper::updateFriendPoints(Auth::user()->id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!",
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/personal')
				->withInput()
				->withErrors($validation);
		}
	}

	public function getProfileContact()
	{
		$title = 'Contact';

		$profile = $this->profile;
		$states = State::orderBy('text', 'ASC')->get();
		$cities = City::where('state_id', '=', Auth::user()->state_id)->orderBy('name', 'ASC')->get();

		$progressPoints = 0;
		$numberOfTextfield = 7;

		if(!empty(Auth::user()->home_number)) 		$progressPoints += 1;
		if(!empty(Auth::user()->mobile_number)) 	$progressPoints += 1;
		if(!empty(Auth::user()->email)) 			$progressPoints += 1;
		if(!empty(Auth::user()->street)) 			$progressPoints += 1;
		if(!empty(Auth::user()->city_id)) 			$progressPoints += 1;
		if(!empty(Auth::user()->state_id)) 			$progressPoints += 1;
		if(!empty(Auth::user()->zip)) 				$progressPoints += 1;

		return View::make('user.profile.contact', compact('title', 'profile', 'states', 'cities', 'progressPoints', 'numberOfTextfield'));
	}

	public function postProfileContact()
	{
		$input = Input::all();

		$rules = array(
				'email'		=> 'required|email|max:255|unique:users,email,'.Auth::user()->id,
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find(Auth::user()->id);
			$user->email 			= $input['email'];
			$user->home_number 		= empty($input['home_number']) 		? null : $input['home_number'];
			$user->mobile_number 	= empty($input['mobile_number']) 	? null : $input['mobile_number'];
			$user->street 			= empty($input['street']) 			? null : $input['street'];
			$user->city_id 			= empty($input['city']) 			? null : $input['city'];
			$user->state_id 		= empty($input['state']) 			? null : $input['state'];
			$user->zip 				= empty($input['zip']) 				? null : $input['zip'];
			$user->save();

			helper::updateFriendPoints(Auth::user()->id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!",
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/contact')
				->withInput()
				->withErrors($validation);
		}
	}

	public function getProfileWork()
	{
		$title = 'Profile';

		$profile = $this->profile->first();

		$progressPoints = 0;
		$numberOfTextfield = 5;

		if(!empty($profile->work_employed)) 		$progressPoints += 1;
		if(!empty($profile->work_retired)) 			$progressPoints += 1;
		if(!empty($profile->work_position)) 		$progressPoints += 1;
		if(!empty($profile->work_salary)) 			$progressPoints += 1;
		if(!empty($profile->work_income)) 			$progressPoints += 1;

		return View::make('user.profile.work', compact('title', 'profile', 'progressPoints', 'numberOfTextfield'));
	}

	public function postProfileWork()
	{
		$input = Input::all();

		// Validate currency regex rule
		$currency = array("regex:/^\s*[$]?\s*((\d+)|(\d{1,3}(\,\d{3})+))(\.\d{2})?\s*$/");

		$rules = array(
			'work_salary' => $currency,
			'work_income' => $currency,
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$customer_profile = CustomerProfile::where('user_id', '=', Auth::user()->id)->first();
			$customer_profile->work_employed 	= empty($input['work_employed']) 	? null : $input['work_employed'];
			$customer_profile->work_retired 	= empty($input['work_retired']) 	? null : $input['work_retired'];
			$customer_profile->work_position 	= empty($input['work_position']) 	? null : $input['work_position'];
			$customer_profile->work_salary 		= empty($input['work_salary']) 		? null : $input['work_salary'];
			$customer_profile->work_income 		= empty($input['work_income']) 		? null : $input['work_income'];
			$customer_profile->save();

			helper::updateFriendPoints(Auth::user()->id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!",
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/work')
				->withInput()
				->withErrors($validation);
		}
	}

	public function getProfileTransportation()
	{
		$title = 'Profile';

		$profile = $this->profile->first();

		$progressPoints = 0;
		$numberOfTextfield = 4;

		if(!empty($profile->trans_own)) 			$progressPoints += 1;
		if(!empty($profile->trans_type)) 			$progressPoints += 1;
		if(!empty($profile->trans_insurance)) 		$progressPoints += 1;
		if(strlen($profile->trans_tickets) > 0) 	$progressPoints += 1;

		return View::make('user.profile.transportation', compact('title', 'profile', 'progressPoints', 'numberOfTextfield'));
	}

	public function postProfileTransportation()
	{
		$input = Input::all();

		$rules = array(
				'trans_tickets' => 'numeric',
			);

		$messages = array(
		    'trans_tickets.numeric' => 'The number of tickets must be a number.',
		);

		$validation = Validator::make($input, $rules, $messages);

		if($validation->passes()) {
			$customer_profile = CustomerProfile::where('user_id', '=', Auth::user()->id)->first();
			$customer_profile->trans_own 		= empty($input['trans_own']) 		? null : $input['trans_own'];
			$customer_profile->trans_type 		= empty($input['trans_type']) 		? null : $input['trans_type'];
			$customer_profile->trans_insurance 	= empty($input['trans_insurance']) 	? null : $input['trans_insurance'];
			$customer_profile->trans_tickets 	= (!isset($input['trans_tickets']) && strlen($input['trans_tickets']) <= 0) 	? null : $input['trans_tickets'];
			$customer_profile->save();

			helper::updateFriendPoints(Auth::user()->id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!",
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/transportation')
				->withInput()
				->withErrors($validation);
		}
	}

	public function getProfileEducation()
	{
		$title = 'Profile';

		$profile = $this->profile->first();

		$progressPoints = 0;
		$numberOfTextfield = 5;

		if(!empty($profile->edu_hs_name)) 			$progressPoints += 1;
		if(!empty($profile->edu_hs_gd)) 			$progressPoints += 1;
		if(!empty($profile->edu_coll_name)) 		$progressPoints += 1;
		if(!empty($profile->edu_coll_degree)) 		$progressPoints += 1;
		if(!empty($profile->edu_coll_gd)) 			$progressPoints += 1;

		return View::make('user.profile.education', compact('title', 'profile', 'progressPoints', 'numberOfTextfield'));
	}

	public function postProfileEducation()
	{
		$input = Input::all();

		$rules = array(
				'edu_hs_gd' 	=> 'date|date_format:m/d/Y',
				'edu_coll_gd' 	=> 'date|date_format:m/d/Y',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$customer_profile = CustomerProfile::where('user_id', '=', Auth::user()->id)->first();
			$customer_profile->edu_hs_name 		= empty($input['edu_hs_name']) 		? null : $input['edu_hs_name'];
			$customer_profile->edu_hs_gd 		= empty($input['edu_hs_gd']) 		? null : date("Y-m-d", strtotime($input['edu_hs_gd']));
			$customer_profile->edu_coll_name 	= empty($input['edu_coll_name']) 	? null : $input['edu_coll_name'];
			$customer_profile->edu_coll_degree 	= empty($input['edu_coll_degree']) 	? null : $input['edu_coll_degree'];
			$customer_profile->edu_coll_gd 		= empty($input['edu_coll_gd']) 		? null : date("Y-m-d", strtotime($input['edu_coll_gd']));
			$customer_profile->save();

			helper::updateFriendPoints(Auth::user()->id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!",
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/education')
				->withInput()
				->withErrors($validation);
		}
	}


	public function getProfileCriminal()
	{
		$title = 'Profile';

		$profile = $this->profile->first();

		$progressPoints = 0;
		$numberOfTextfield = 1;

		if(!empty($profile->crim_arrests)) 			$progressPoints += 1;

		return View::make('user.profile.criminal', compact('title', 'profile', 'progressPoints', 'numberOfTextfield'));
	}

	public function postProfileCriminal()
	{
		$input = Input::all();

		$rules = array();

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$customer_profile = CustomerProfile::where('user_id', '=', Auth::user()->id)->first();
			$customer_profile->crim_arrests = empty($input['crim_arrests']) ? null : $input['crim_arrests'];
			$customer_profile->save();

			helper::updateFriendPoints(Auth::user()->id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!",
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/criminal')
				->withInput()
				->withErrors($validation);
		}
	}

	public function getSetting()
	{
		$title = "Settings";
		$profile = $this->profile->first();
		return View::make('user.setting', compact('title', 'profile'));
	}

	public function postSetting()
	{
		Validator::extend('checkOld', function($attribute, $value, $parameters)
		{
			$hashedPassword = Auth::user()->password;
			return Hash::check($value, $hashedPassword);
		}, 'Current password is not correct.');

		$input = Input::all();

		$rules = array(
			'current_password' 		=> 'required|checkOld',
			'password' 				=> 'required|confirmed|min:6',
			'password_confirmation' => 'same:password',
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find(Auth::user()->id);
			$user->password = Hash::make($input['password']);
			$user->save();

			return Redirect::back()
				->with('flash', ['message'	=>	'<strong>Successfully changed password!</strong>',
								'type'		=>	'success']);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($validation);
		}
	}

	public function findLawyer()
	{
		$title = 'Find A Lawyer';

		$profile = $this->profile;

		$page = Input::get('page');
		if($page == 1 || $page == null) {
			$practice_areas = PracticeArea::orderBy('name', 'ASC')->get();
			$states = State::orderBy('text', 'ASC')->get();
			$cities = City::where('state_id', '=', Input::get('state'))->orderBy('name', 'ASC')->get();
			Session::put('find_lawyer-last_page', 1);

			return View::make('user.find_lawyer.first', compact('title', 'profile', 'states', 'cities', 'practice_areas'));
		} elseif($page == 2 && ( Input::get('practice_area') && Input::get('state') && Input::get('contact') ) ) {
			if(Session::get('find_lawyer-last_page') == 1)
				Session::put('find_lawyer-custom_form', '');

			Session::put('find_lawyer-last_page', 2);

			$practicearea 	= PracticeArea::where( 'id', '=', Input::get('practice_area') )->get()->first();
			$forms 			= CustomForm::with('questions')->where('id', '=', $practicearea->form_id)->get()->toArray();

			return View::make('user.find_lawyer.second', compact('title', 'profile', 'forms'));
		} elseif ($page == 3) {
			Session::put('find_lawyer-last_page', 3);

			$friendlevels = FriendLevel::orderBy('rate_percent', 'ASC')->get();

			return View::make('user.find_lawyer.third', compact('title', 'profile', 'friendlevels'));
		} elseif ($page == 4) {
			$practice_area_id = Input::get('practice_area');
			$state_id = Input::get('state');
			$city_id = Input::get('city');

			$lawyers = LawyerPracticeArea::with(['lawyer', 'lawyer.user'])->where('practice_area_id', '=', $practice_area_id)->where('state_id', '=', $state_id)->where('city_id', '=', $city_id)->groupBy('lawyer_id', 'practice_area_id')->get()->toArray();

			return View::make('user.find_lawyer.fourth', compact('title', 'profile', 'lawyers'));
		} else {
			return Redirect::route('find-a-lawyer');
		}
	}

	public function findLawyerSecond()
	{
		$custom_forms = Input::get('custom_form');
		Session::put('find_lawyer-custom_form', serialize($custom_forms));
		Session::put('find_lawyer-practice_area', Input::get('practice_area'));
		Session::put('find_lawyer-state', Input::get('state'));
		Session::put('find_lawyer-city', Input::get('city'));
		Session::put('find_lawyer-contact', Input::get('contact'));

		return Redirect::route('find-a-lawyer', ['page'=>3, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]);
	}

	public function findLawyerThird()
	{
		return Redirect::route('find-a-lawyer', ['page'=>4, 'practice_area'=>Input::get('practice_area'), 'state'=>Input::get('state'), 'city'=>Input::get('city'), 'contact'=>Input::get('contact')]);
	}

	public function lawyerView($user_id)
	{
		$title = 'Lawyer';

		$profile = $this->profile;
		$custom_forms = Session::get('find_lawyer-custom_form');

		$lawyer = LawyerProfile::with('user')->where('user_id', '=', $user_id)->get()->first()->toArray();

		return View::make('user.find_lawyer.lawyerView', compact('title', 'profile', 'lawyer', 'custom_forms'));
	}

	public function sendRequest($lawyer_id)
	{
		$customer = User::where('id', '=', Auth::user()->id)->get()->first();
		$lawyer = User::where('id', '=', $lawyer_id)->get()->first();
		$lawyerprofile = LawyerProfile::where('user_id', '=', $lawyer->id)->get()->first();


		$rate = CustomerProfile::getCurrentRateByCityAndPracticeArea(Session::get('find_lawyer-practice_area'), Session::get('find_lawyer-city'), CustomerProfile::getFriendLevel(Auth::user()->id)->rate_percent);

		$input = [
			'bcc' 		=> Config::get('mail')['from'],
			'customer' 	=> $customer,
			'lawyer' 	=> $lawyer,
			'input' 	=> [
				'practice_area' => PracticeArea::where('id', '=', Session::get('find_lawyer-practice_area'))->get()->first()->name,
				'state' 		=> State::where('id','=', Session::get('find_lawyer-state'))->get()->first(),
				'city' 			=> City::where('id','=', Session::get('find_lawyer-city'))->get()->first(),
				'contact' 		=> Session::get('find_lawyer-contact'),
				'form' 			=> unserialize( Session::get('find_lawyer-custom_form') ),
				'info' 			=> Input::get('info'),
				'rate' 			=> $rate,
			]
		];

		Mail::send('emails.requestLawyer', $input, function($message) use ($input)
		{
			$message->from($input['customer']['email'], $input['bcc']['address']);
			$message->subject("You Have Received a New Lawyer Friend Message");
			$message->to($input['lawyer']['email']);
			$message->replyTo($input['customer']['email'], $input['customer']['first_name'] . " " . $input['customer']['last_name']);
			$message->bcc($input['bcc']['address'], $input['bcc']['name']);
		});

		$lawyerprofile->credits = $lawyerprofile->credits - 2;
		$lawyerprofile->save();

		$contactExist = Contact::where('user_id', '=', 1)->where('contact_user_id', '=', Auth::user()->id)->get()->first();
		if(!$contactExist) {
			$contact 					= new Contact;
			$contact->user_id 			= $lawyer_id;
			$contact->contact_user_id 	= Auth::user()->id;
			$contact->save();
		}

		return Redirect::back()
				->with('flash', ['message'	=>	"Successfully sent request!",
								'type'		=>	'success']);
	}
}
