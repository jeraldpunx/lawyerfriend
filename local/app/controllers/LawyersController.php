<?php

class LawyersController extends \BaseController {
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
		return Redirect::route('profile/personal');
	}

	public function overview()
	{
		return Redirect::route('profile/personal');
	}

	public function getProfilePersonal()
	{
		$title = 'Profile';

		$profile = $this->profile->first();
		$states = State::orderBy('text', 'ASC')->get();
		$cities = City::where('state_id', '=', Auth::user()->state_id)->orderBy('name', 'ASC')->get();
		$ethnicities = Ethnicity::orderBy('name', 'ASC')->get();

		return View::make('lawyer.personal', compact('title', 'profile', 'states', 'cities', 'ethnicities'));
	}

	public function postProfilePersonal()
	{
		$input = Input::all();

		$rules = array(
				'first_name' 		=> 'required|max:255',
				'last_name' 		=> 'required|max:255',
				'birthdate' 		=> 'required|date|date_format:m/d/Y',
				'email'				=> 'required|email|max:255|unique:users,email,'.Auth::user()->id,
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find(Auth::user()->id);
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->email 			= $input['email'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			$user->gender 			= isset($input['gender']) ? $input['gender'] : null;
			$user->ethnicity 		= empty($input['ethnicity']) ? null : $input['ethnicity'];
			$user->street 			= empty($input['street']) 			? null : $input['street'];
			$user->city_id 			= empty($input['city']) 			? null : $input['city'];
			$user->state_id 		= empty($input['state']) 			? null : $input['state'];
			$user->zip 				= empty($input['zip']) 				? null : $input['zip'];
			$user->home_number 		= empty($input['home_number']) 		? null : $input['home_number'];
			$user->mobile_number 	= empty($input['mobile_number']) 	? null : $input['mobile_number'];
			$user->save();

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!", 
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/personal')
				->withInput()
				->withErrors($validation);
		}
	}

	public function postProfilePersonalImage()
	{
		$input = Input::all();

		$rules = [
        	'image'             => 'required|mimes:jpeg,bmp,png|max:3000'
	    ];

	    $messages = array(
		    'image.required' => 'You need to select image!',
		);

		$validation = Validator::make($input, $rules, $messages);

		if($validation->passes()) {
			$file = Input::file('image');
		    $name = str_random(20) . time() . '.jpg';

		    $image = Image::make($file)->encode('jpg')->orientate()->fit(200)->save(public_path() . '/uploads/' . $name);

		    $user = User::find(Auth::user()->id);
		    $user->image 	= $name;
			$user->save();

		    return Redirect::back()
				->with('flash', ['message'	=>	"Successfully updated image!", 
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/personal')
					->withErrors($validation);
		}
	}

	public function getProfilePractice()
	{
		$title = 'Profile';
		$profile = $this->profile->first();
		$practice_areas = PracticeArea::orderBy('name', 'ASC')->get()->toArray();
		$states = State::orderBy('text', 'ASC')->get();
		$cities = City::where('state_id', '=', $profile->state_id)->orderBy('name', 'ASC')->get();
		$lawyer_pas = LawyerPracticeArea::with(['cities'])->where('lawyer_id', '=', $profile->id)->get()->toArray();

		return View::make('lawyer.practice', compact('title', 'profile', 'practice_areas', 'states', 'cities', 'lawyer_pas'));
	}

	public function postProfilePractice()
	{
		$input = Input::all();

		$rules = array(
				'firm_name' 			=> 	'required|max:255',
				'practice_areas.0' 		=> 	'required|max:255',
			);

		$messages = array(
		    'practice_areas.0' => 'You need to add atleast one practice area!',
		);

		$validation = Validator::make($input, $rules, $messages);

		if($validation->passes()) {
			$lawyer_profile = LawyerProfile::where('user_id', '=', Auth::user()->id)->first();
			$lawyer_profile->firm_name 		= $input['firm_name'];
			$lawyer_profile->firm_desc 		= empty($input['description']) 		? null : $input['description'];
			$lawyer_profile->save();

			LawyerPracticeArea::where('lawyer_id', '=', $lawyer_profile->id)->delete();

			foreach ($input['practice_areas'] as $index => $practice_area) {
				$lawyer_pa 						= new LawyerPracticeArea;
				$lawyer_pa->lawyer_id 			= $lawyer_profile->id;
				$lawyer_pa->practice_area_id 	= $practice_area;
				$lawyer_pa->state_id 			= empty($input['state']) 	? null : $input['state'][$index];
				$lawyer_pa->city_id 			= empty($input['city']) 	? null : $input['city'][$index];
				$lawyer_pa->street 				= empty($input['street']) 	? null : $input['street'][$index];
				$lawyer_pa->zip 				= empty($input['zip']) 		? null : $input['zip'][$index];
				$lawyer_pa->save();
			}

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved!", 
					'type'		=>	'success']);
		} else {
			return Redirect::route('profile/practice')
				->withInput()
				->withErrors($validation);
		}
	}

	public function contacts()
	{
		$title = "Contacts";

		$contacts = Contact::with(['contact_detail'])->where('user_id', '=', Auth::user()->id)->get();

		return View::make('lawyer.contacts', compact('title', 'contacts'));
	}

	public function getContact()
	{
		$contact = Contact::with(['contact_detail'])->where('id', '=', Input::get('id'))->get()->first();
	        
		return Response::json($contact);
	}

	public function deleteContact()
	{
		$contact = Contact::findOrFail( Input::get('id') );
		$contact->delete();

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully removed!";
		return Response::json($return);
	}

	public function getSetting()
	{
		return Redirect::route('getAddCredits');
	}

	public function getChangePassword()
	{
		$title = "Change Password";
		$profile = $this->profile->first();

		return View::make('lawyer.changepassword', compact('title', 'profile'));
	}

	public function postChangePassword()
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

	public function getAddCredits()
	{
		$title = "Add Credits";
		$profile = $this->profile->first();

		return View::make('lawyer.credits', compact('title', 'profile'));
	}
}
