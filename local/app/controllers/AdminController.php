<?php

class AdminController extends \BaseController {
	public function index()
	{
		return Redirect::route('users');
	}

	public function overview()
	{
		return Redirect::route('users');
	}

	public function users($section = 'all')
	{
		$sectionText = ucfirst(strtolower($section));
		$title = "Users($sectionText)";
		$users = User::all();

		if($section == 'customers')
			$users = User::where('active', '=', 1)->where('role', '=', 'customer')->get();
		else if($section == 'lawyers')
			$users = User::leftjoin('lawyer_profiles', 'lawyer_profiles.user_id', '=', 'users.id')->select('users.*')->where('users.active', '=', 1)->where('users.role', '=', 'lawyer')->where('lawyer_profiles.lawyer_active', '=', 1)->get();
		else if($section == 'all')
			$users = User::where('active', '=', 1)->get();
		else if($section == 'pending')
			$users = User::leftjoin('lawyer_profiles', 'lawyer_profiles.user_id', '=', 'users.id')->select('users.*')->where('users.active', '=', 1)->where('users.role', '=', 'lawyer')->where('lawyer_profiles.lawyer_active', '=', 0)->get();
		else
			return Redirect::route('users', ['section'=>'all']);

		return View::make('admin.users', compact('title', 'sectionText', 'users'));
	}

	public function user($user_id = null)
	{
		$title = "Users";

		if (Request::ajax()) {
			$user = User::find(Input::get('id'));
		} else if(!User::find($user_id)) {
			return Redirect::route('users');
		} else {
			$user = User::find($user_id);
		}

		if($user->role == 'customer') {
			$ethnicities = Ethnicity::orderBy('name', 'ASC')->get();
			$states = State::orderBy('text', 'ASC')->get();
			$cities = City::where('state_id', '=', $user->state_id)->orderBy('name', 'ASC')->get();
			$profile = CustomerProfile::where('user_id', '=',$user->id)->get()->first();

			$progress = array();

			$progress['personal']['points'] = 0;
			$progress['personal']['numberOfTextfield'] = 5;
			if(!empty($user->first_name)) 	$progress['personal']['points']++;
			if(!empty($user->last_name)) 	$progress['personal']['points']++;
			if(!empty($user->birthdate)) 	$progress['personal']['points']++;
			if(!empty($user->gender)) 		$progress['personal']['points']++;
			if(!empty($user->ethnicity)) 	$progress['personal']['points']++;

			$progress['contact']['points'] = 0;
			$progress['contact']['numberOfTextfield'] = 7;
			if(!empty($user->home_number)) 		$progress['contact']['points'] ++;
			if(!empty($user->mobile_number)) 	$progress['contact']['points'] ++;
			if(!empty($user->email)) 			$progress['contact']['points'] ++;
			if(!empty($user->street)) 			$progress['contact']['points'] ++;
			if(!empty($user->city_id)) 			$progress['contact']['points'] ++;
			if(!empty($user->state_id)) 		$progress['contact']['points'] ++;
			if(!empty($user->zip)) 				$progress['contact']['points'] ++;

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

			return View::make('admin.user_customer', compact('title', 'ethnicities', 'states', 'cities', 'user', 'profile', 'progress'));
		} else if ($user->role == 'lawyer') {
				$ethnicities = Ethnicity::orderBy('name', 'ASC')->get();
				$profile = LawyerProfile::where('user_id', '=',$user->id)->get()->first();
				$practice_areas = PracticeArea::orderBy('name', 'ASC')->get()->toArray();
				$states = State::orderBy('text', 'ASC')->get();
				$cities = City::where('state_id', '=', $profile->state_id)->orderBy('name', 'ASC')->get();
				$lawyer_pas = LawyerPracticeArea::with(['cities'])->where('lawyer_id', '=', $profile->id)->get()->toArray();

			if(Request::ajax()) {
				$return = [	'user' 				=> $user,
							'profile' 			=> $profile ];
				return Response::json($return);
			} else {
				return View::make('admin.user_lawyer', compact('title', 'ethnicities', 'states', 'cities', 'user', 'profile', 'practice_areas', 'lawyer_pas'));
				
			}

		}
	}

	public function postNewUser()
	{
		$input = Input::all();

		$rules = array(
			'role' 					=> 'required',
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
			$user->role 			= $input['role'];
			$user->email 			= $input['email'];
			$user->password 		= Hash::make($input['password']);
			$user->activation_code 	= '';
			$user->active 			= 1;
			$user->isBanned 		= 0;
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			$user->save();

			if($input['role'] == 'customer') {
				$profile = new CustomerProfile;
				$profile->user_id = $user->id;
				$profile->save();
			} else if($input['role'] == 'lawyer') {
				$profile = new LawyerProfile;
				$profile->user_id 		= $user->id;
				$profile->lawyer_active	= 0;
				$profile->save();
			}


			$return['flash']['type'] =	true;
			if($input['role'] == 'admin') {
				$return['flash']['message'] = "Successfully registered! You can login now";
			} else {
				$return['flash']['message'] = "Successfully registered! Redirecting to profile...";
			}
			$return['data'] = $user;

			return Response::json($return);
		} else {
			$return['flash']['type'] = false;
			$return['flash']['message'] = $validation->messages();

			return Response::json($return);
		}
	}

	public function postEditUser()
	{
		$input = Input::all();

		$rules = array(
			'first_name' 			=> 'required|max:255',
			'last_name' 			=> 'required|max:255',
			'email' 				=> 'required|email|max:255|unique:users,email,'.$input['id'],
			'birthdate' 			=> 'required|date|date_format:m/d/Y',
		);

		if(!empty($input['password']) || !empty($input['password_confirmation'])) {
			$rules = array(
				'password' 				=> 'required|confirmed|min:6',
				'password_confirmation' => 'same:password',
			);
		}

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find($input['id']);
			$user->email 			= $input['email'];
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			if(!empty($input['password']) || !empty($input['password_confirmation'])) $user->password = Hash::make($input['password']);
			$user->save();

			$return['flash']['type'] =	true;
			$return['flash']['message'] = "Successfully saved!";
			$return['data'] = $user;

			return Response::json($return);
		} else {
			$return['flash']['type'] = false;
			$return['flash']['message'] = $validation->messages();

			return Response::json($return);
		}
	}

	public function getUser()
	{
		$user = User::where('id', '=', Input::get('id'))->firstOrFail();
		return Response::json($user);
	}

	public function deleteUser()
	{
		$user = User::findOrFail( Input::get('id') );

		if($user->role == 'customer') {
			CustomerProfile::where('user_id', '=', $user->id )->delete();
			Contact::where('contact_user_id', '=', $user->id)->delete();
			ShareLink::where('user_id', '=', $user->id)->delete();
		} else if($user->role == 'lawyer') {
			$profile = LawyerProfile::where('user_id', '=', $user->id )->first();
			LawyerPracticeArea::where('lawyer_id', '=', $profile->id )->delete();
			Contact::where('user_id', '=', $user->id)->delete();
			$profile->delete();
		}

		$user->delete();

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully deleted!";
		return Response::json($return);
	}

	public function postCustomerPersonal($user_id)
	{
		$input = Input::all();

		$rules = array(
				'first_name' 		=> 'required|max:255',
				'last_name' 		=> 'required|max:255',
				'birthdate' 		=> 'required|date|date_format:m/d/Y',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find($user_id);
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			$user->ethnicity 		= empty($input['ethnicity']) ? null : $input['ethnicity'];
			$user->gender 			= isset($input['gender']) ? $input['gender'] : null;
			$user->save();

			helper::updateFriendPoints($user_id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved PERSONAL!", 
								'type'		=>	'success']);
		} else {
			return Redirect::route('user', ['user_id'=>$user_id])
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postCustomerContact($user_id)
	{
		$input = Input::all();

		$rules = array(		 			
				'email'		=> 'required|email|max:255|unique:users,email,'.$user_id,
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find($user_id);
			$user->email 			= $input['email'];
			$user->home_number 		= empty($input['home_number']) 		? null : $input['home_number'];
			$user->mobile_number 	= empty($input['mobile_number']) 	? null : $input['mobile_number'];
			$user->street 			= empty($input['street']) 			? null : $input['street'];
			$user->city_id 			= empty($input['city']) 			? null : $input['city'];
			$user->state_id 		= empty($input['state']) 			? null : $input['state'];
			$user->zip 				= empty($input['zip']) 				? null : $input['zip'];
			$user->save();

			helper::updateFriendPoints($user_id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved CONTACT!", 
								'type'		=>	'success']);
		} else {
			return Redirect::route('user', ['user_id'=>$user_id])
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postCustomerWork($user_id)
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
			$customer_profile = CustomerProfile::where('user_id', '=', $user_id)->first();
			$customer_profile->work_employed 	= empty($input['work_employed']) 	? null : $input['work_employed'];
			$customer_profile->work_retired 	= empty($input['work_retired']) 	? null : $input['work_retired'];
			$customer_profile->work_position 	= empty($input['work_position']) 	? null : $input['work_position'];
			$customer_profile->work_salary 		= empty($input['work_salary']) 		? null : $input['work_salary'];
			$customer_profile->work_income 		= empty($input['work_income']) 		? null : $input['work_income'];
			$customer_profile->save();

			helper::updateFriendPoints($user_id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved WORK!", 
								'type'		=>	'success']);
		} else {
			return Redirect::route('user', ['user_id'=>$user_id])
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postCustomerEducation($user_id)
	{
		$input = Input::all();

		$rules = array(
				'edu_hs_gd' 	=> 'date|date|date_format:m/d/Y',
				'edu_coll_gd' 	=> 'date|date|date_format:m/d/Y',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$customer_profile = CustomerProfile::where('user_id', '=', $user_id)->first();
			$customer_profile->edu_hs_name 		= empty($input['edu_hs_name']) 		? null : $input['edu_hs_name'];
			$customer_profile->edu_hs_gd 		= empty($input['edu_hs_gd']) 		? null : date("Y-m-d", strtotime($input['edu_hs_gd']));
			$customer_profile->edu_coll_name 	= empty($input['edu_coll_name']) 	? null : $input['edu_coll_name'];
			$customer_profile->edu_coll_degree 	= empty($input['edu_coll_degree']) 	? null : $input['edu_coll_degree'];
			$customer_profile->edu_coll_gd 		= empty($input['edu_coll_gd']) 		? null : date("Y-m-d", strtotime($input['edu_coll_gd']));
			$customer_profile->save();

			helper::updateFriendPoints($user_id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved EDUCATION!", 
								'type'		=>	'success']);
		} else {
			return Redirect::route('user', ['user_id'=>$user_id])
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postCustomerCriminal($user_id)
	{
		$input = Input::all();

		$rules = array();

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$customer_profile = CustomerProfile::where('user_id', '=', $user_id)->first();
			$customer_profile->crim_arrests = empty($input['crim_arrests']) ? null : $input['crim_arrests'];
			$customer_profile->save();

			helper::updateFriendPoints($user_id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved CRIMINAL!", 
								'type'		=>	'success']);
		} else {
			return Redirect::route('user', ['user_id'=>$user_id])
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postCustomerTransportation($user_id)
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
			$customer_profile = CustomerProfile::where('user_id', '=', $user_id)->first();
			$customer_profile->trans_own 		= empty($input['trans_own']) 		? null : $input['trans_own'];
			$customer_profile->trans_type 		= empty($input['trans_type']) 		? null : $input['trans_type'];
			$customer_profile->trans_insurance 	= empty($input['trans_insurance']) 	? null : $input['trans_insurance'];
			$customer_profile->trans_tickets 	= (!isset($input['trans_tickets']) && strlen($input['trans_tickets']) <= 0) 	? null : $input['trans_tickets'];
			$customer_profile->save();

			helper::updateFriendPoints($user_id);

			return Redirect::back()
				->with('flash', ['message'	=>	"Successfully saved TRANSPORTATION!", 
								'type'		=>	'success']);
		} else {
			return Redirect::route('user', ['user_id'=>$user_id])
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postCustomerPassword($user_id)
	{
		$input = Input::all();

		$rules = array(
			'password' 				=> 'required|confirmed|min:6',
			'password_confirmation' => 'same:password',
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find($user_id);
			$user->password = Hash::make($input['password']);
			$user->save();

			return Redirect::back()
				->with('flash', ['message'	=>	'Successfully changed PASSWORD!',
								'type'		=>	'success']);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postLawyerPersonalImage($user_id)
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

		    $user = User::find($user_id);
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

	public function postLawyerPersonal($user_id)
	{
		$input = Input::all();

		$rules = array(
				'first_name' 		=> 'required|max:255',
				'last_name' 		=> 'required|max:255',
				'birthdate' 		=> 'required|date|date_format:m/d/Y',
				'email'				=> 'required|email|max:255|unique:users,email,'.$user_id,
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find($user_id);
			$user->first_name 		= $input['first_name'];
			$user->last_name 		= $input['last_name'];
			$user->email 			= $input['email'];
			$user->birthdate 		= date("Y-m-d", strtotime($input['birthdate']));
			$user->gender 			= isset($input['gender']) 			? $input['gender'] : null;
			$user->ethnicity 		= empty($input['ethnicity']) 		? null : $input['ethnicity'];
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
			return Redirect::back()
				->withInput()
				->withErrors($validation);
		}
	}

	public function postLawyerPractice($user_id)
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
			$lawyer_profile = LawyerProfile::where('user_id', '=', $user_id)->first();
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
			return Redirect::back()
				->withInput()
				->withErrors($validation);
		}
	}

	public function postLawyerPassword($user_id)
	{
		$input = Input::all();
		
		$rules = array(
			'password' 				=> 'required|confirmed|min:6',
			'password_confirmation' => 'same:password',
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = User::find($user_id);
			$user->password = Hash::make($input['password']);
			$user->save();

			return Redirect::back()
				->with('flash', ['message'	=>	'Successfully changed PASSWORD!',
								'type'		=>	'success']);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function postLawyerApprove()
	{
		$user = User::findOrFail( Input::get('id') );
		$lawyer_profile = LawyerProfile::where('user_id', '=', $user->id )->get()->first();
		$lawyer_profile->lawyer_active = 1;
		$lawyer_profile->save();

		$input = [
			'body' 	=> "Congratulations, you are now officially a member of Lawyer Friend! To begin checking out all of the features that Lawyer Friend has to offer, follow this <a href=".URL::route('login').">link</a>.",
			'email' => $user->email,
		];
		Mail::send('emails.pending', $input, function($message) use ($input)
		{
			$message->from('no-reply@site.com', "Lawyer Friend");
			$message->subject("You Have Been Approved by Lawyer Friend");
			$message->to($input['email']);
		});

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully approved!";
		return Response::json($return);
	}

	public function postLawyerDecline()
	{
		$user = User::findOrFail( Input::get('id') );
		$profile = LawyerProfile::where('user_id', '=', $user->id )->first();
		LawyerPracticeArea::where('lawyer_id', '=', $profile->id )->delete();
		Contact::where('user_id', '=', $user->id)->delete();

		$input = [
			'body' 	=> "We're sorry, but your account cannot be created at this time.",
			'email' => $user->email,
		];
		Mail::send('emails.pending', $input, function($message) use ($input)
		{
			$message->from('no-reply@site.com', "Lawyer Friend");
			$message->subject("New email | Lawyer Friend");
			$message->to($input['email']);
		});

		$profile->delete();
		$user->delete();

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully declined!";
		return Response::json($return);
	}

	public function lawyers()
	{
		$title = "Lawyers";
		$users = User::leftjoin('lawyer_profiles', 'lawyer_profiles.user_id', '=', 'users.id')->select('users.*', 'lawyer_profiles.credits')->where('users.active', '=', 1)->where('users.role', '=', 'lawyer')->where('lawyer_profiles.lawyer_active', '=', 1)->get();

		return View::make('admin.send_credits', compact('title', 'users'));
	}

	public function postSendCredits()
	{
		$input = Input::all();

		$rules = array(
				'credit' 		=> 	'required|max:255',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$profile = LawyerProfile::where('user_id', '=', $input['id'])->get()->first();
			$profile->credits = $profile->credits + $input['credit'];
			$profile->save();

			if(!$profile->id){
			    App::abort(500, 'Some Error');
			}

			$return['flash']['type'] 	= true;
			$return['flash']['message'] = "Successfully saved!";
		} else {
			$errors = $validation->messages();
			$returnMessage = "";

			foreach ($errors->all('<li>:message</li>') as $error) {
				$returnMessage .= $error;
            }

			$return['flash']['type'] 	= false;
			$return['flash']['message'] = $returnMessage;
		}

		return Response::json($return);
	}

	public function getLawyerTransHistory()
	{
		$profile = LawyerProfile::where('user_id', '=', Input::get('id'))->get()->first();
		$histories = LawyerTransHistory::where('lawyer_id', '=', $profile->id)->get();

		$returnedValue = ["profile"=>$profile, "histories"=>$histories];

		return Response::json($returnedValue);
	}

	public function states()
	{
		$title = 'States';

		$states = State::all();

		return View::make('admin.states', compact('title', 'states'));
	}

	public function getState()
	{
		$state = State::where('id', '=', Input::get('id'))->firstOrFail();
		return Response::json($state);
	}

	public function postState()
	{
		$input = Input::all();

		$rules = array(
				'state' 		=> 	'required|max:255',
				'abbreviation' 	=> 	'required|max:5',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			if(Input::get('id'))
			$state = State::where('id', '=', Input::get('id'))->firstOrFail();
			else
				$state = new State;
			$state->text 				= Input::get('state');
			$state->abbr 				= Input::get('abbreviation');
			$state->save();

			if(!$state->id){
			    App::abort(500, 'Some Error');
			}

			$return['flash']['type'] 	= true;
			$return['flash']['message'] = "Successfully saved $state->text!";
			$return['data'] = $state;
		} else {
			$errors = $validation->messages();
			$returnMessage = "";

			foreach ($errors->all('<li>:message</li>') as $error) {
				$returnMessage .= $error;
            }

			$return['flash']['type'] 	= false;
			$return['flash']['message'] = $returnMessage;
		}

		return Response::json($return);
	}

	public function deleteState()
	{
		$state = State::findOrFail( Input::get('id') );
		$cities = City::select('id')->where('state_id', '=', $state->id);

		User::where('state_id', '=', $state->id)->update(['state_id' => null]);
		User::whereIn('city_id', $cities->get()->toArray())->update(['city_id' => null]);
		LawyerPracticeArea::where('state_id', '=', $state->id)->update(['state_id' => null]);
		LawyerPracticeArea::whereIn('city_id', $cities->get()->toArray())->update(['city_id' => null]);

		$cities->delete();
		$state->delete();

		$return['flash']['type'] 	=	true;
		$return['flash']['message'] = "Successfully deleted!";
		return Response::json($return);
	}

	public function cities($state_id = null)
	{
		$title = 'Cities';

		if($state_id)
			$cities = City::where('state_id', '=', $state_id)->get();
		else
			$cities = City::all();

		return View::make('admin.cities', compact('title', 'cities', 'state_id'));
	}

	public function getCity()
	{
		$city = City::where('id', '=', Input::get('id'))->firstOrFail();
		return Response::json($city);
	}

	public function postCity($state_id)
	{
		$input = Input::all();

		$rules = array(
				'name' 			=> 	'required|max:255',
				'rate_percent' 	=> 	'required|numeric|min:0',
			);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			if(Input::get('id'))
				$city = City::where('id', '=', Input::get('id'))->firstOrFail();
			else
				$city = new City;
			$city->state_id 			= $state_id;
			$city->name 				= Input::get('name');
			$city->rate_percent 		= Input::get('rate_percent');
			$city->save();

			if(!$city->id){
			    App::abort(500, 'Some Error');
			}

			$return['flash']['type'] 	= true;
			$return['flash']['message'] = "Successfully saved $city->name!";
			$return['data'] = $city;

		} else {
			$errors = $validation->messages();
			$returnMessage = "";

			foreach ($errors->all('<li>:message</li>') as $error) {
				$returnMessage .= $error;
            }

			$return['flash']['type'] 	= false;
			$return['flash']['message'] = $returnMessage;
		}

		return Response::json($return);
	}

	public function deleteCity()
	{
		$city = State::findOrFail( Input::get('id') );

		User::where('city_id', '=', $city->id)->update(['city_id' => null]);
		LawyerPracticeArea::where('city_id', '=', $city->id)->update(['city_id' => null]);
		$cities->delete();

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully deleted!";
		return Response::json($return);
	}

	public function bloglists()
	{
		$title = 'Blogs';

		$blogs = Blog::all();

		return View::make('admin.blogs', compact('title', 'blogs'));
	}

	public function getBlog($blog_id = null)
	{
		if(Request::ajax()) {
	        $blog = Blog::where('id', '=', Input::get('id'))->get()->first();
	        
			return Response::json($blog);
	    } else {
			$title = 'Blog';
			$blog = Blog::where('id', '=', $blog_id)->get()->first();

			return View::make('admin.blog_edit', compact('title', 'blog'));
		}
	}

	public function postBlog($blog_id = null)
	{
		$input = Input::all();

		$rules = array(
			'title' 		=> 'required',
			'content' 		=> 'required',
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			if($blog_id) {
				$blog = Blog::find($blog_id);
				$blog->user_id 		= Auth::user()->id;
				$blog->title 		= $input['title'];
				$blog->content 		= $input['content'];
				$blog->save();
			} else {
				$blog = new Blog;
				$blog->user_id 		= Auth::user()->id;
				$blog->title 		= $input['title'];
				$blog->content 		= $input['content'];
				$blog->save();
			}

			return Redirect::route('getBlog', [$blog->id])
				->with('flash', ['message'	=>	'Successfully saved. <a href="'. URL::route('blogs', [$blog->id]) .'">Preview</a>', 
								'type'		=>	'success']);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('flash_error', 'Validation Errors!');
		}
	}

	public function deleteBlog()
	{
		$blog = Blog::findOrFail( Input::get('id') );
		$blog->delete();

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully deleted!";
		return Response::json($return);
	}

	public function friendlevels()
	{
		$title = 'Friend Levels';

		$friendlevels = FriendLevel::all();

		return View::make('admin.friendlevels', compact('title', 'friendlevels'));
	}

	public function getFriendLevel()
	{
		$friendlevel = FriendLevel::where('id', '=', Input::get('id'))->firstOrFail();
		return Response::json($friendlevel);
	}

	public function postFriendLevel()
	{
		$input = Input::all();

		$rules = array(
				'name'				=> 'required|max:255',
				// 'rate'				=> 'required|numeric|min:0',
				'rate_percent'		=> 'required|numeric|min:0',
				'points_needed'		=> 'required|numeric|min:0',
		);

		
		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			if(Input::get('id'))
				$friendlevel = FriendLevel::where('id', '=', Input::get('id'))->firstOrFail();
			else
				$friendlevel = new FriendLevel;
			$friendlevel->name 				= Input::get('name');
			// $friendlevel->rate 				= Input::get('rate');
			$friendlevel->rate_percent 		= Input::get('rate_percent');
			$friendlevel->points_needed 	= Input::get('points_needed');
			$friendlevel->save();

			if(!$friendlevel->id){
			    App::abort(500, 'Some Error');
			}

			$return['flash']['type'] 	=	true;
			$return['flash']['message'] = "Successfully saved $friendlevel->name!";
			$return['data'] = $friendlevel;
		} else {
			$errors = $validation->messages();
			$returnMessage = "";

			foreach ($errors->all('<li>:message</li>') as $error) {
				$returnMessage .= $error;
            }

			$return['flash']['type'] 	= false;
			$return['flash']['message'] = $returnMessage;
		}

		return Response::json($return);
	}

	public function deleteFriendLevel()
	{
		$friendlevel = FriendLevel::findOrFail( Input::get('id') );
		$friendlevel->delete();

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully deleted!";
		return Response::json($return);
	}


	public function practiceAreas()
	{
		$title = 'Practice Areas';

		$practiceareas = PracticeArea::with('form')->get();
		$forms = CustomForm::all();

		return View::make('admin.practiceareas', compact('title', 'practiceareas', 'forms'));
	}

	public function getPracticeArea()
	{
		$practicearea = PracticeArea::where('id', '=', Input::get('id'))->with('form')->firstOrFail();
		return Response::json($practicearea);
	}

	public function postPracticeArea()
	{
		$input = Input::all();

		$rules = array(
				'name' 			=> 	'required|max:255',
				'rate'			=> 'required|numeric|min:0',
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			if(Input::get('id'))
				$practicearea = PracticeArea::findOrFail(Input::get('id'));
			else
				$practicearea = new PracticeArea;
			$practicearea->name 	= Input::get('name');
			$practicearea->form_id 	= Input::get('form_id');
			$practicearea->rate 	= Input::get('rate');
			$practicearea->save();

			if(!$practicearea->id){
			    App::abort(500, 'Some Error');
			}

			$customform = CustomForm::find(Input::get('form_id'));

			$return['flash']['type'] 		= true;
			$return['flash']['message']		= "Successfully saved $practicearea->name!";
			$return['data']["practicearea"] = $practicearea;
			$return['data']["form"] 		= $customform;
		} else {
			$errors = $validation->messages();
			$returnMessage = "";

			foreach ($errors->all('<li>:message</li>') as $error) {
				$returnMessage .= $error;
            }

			$return['flash']['type'] 	= false;
			$return['flash']['message'] = $returnMessage;
		}

		return Response::json($return);
	}

	public function deletePracticeArea()
	{
		$practicearea = PracticeArea::findOrFail( Input::get('id') );
		LawyerPracticeArea::where('practice_area_id', '=', $practicearea->id)->delete();
		$practicearea->delete();

		$return['flash']['type'] =	true;
		$return['flash']['message'] = "Successfully deleted!";
		return Response::json($return);
	}

	public function forms()
	{
		$title = 'Forms';

		$forms = CustomForm::all();

		return View::make('admin.forms', compact('title', 'forms'));
	}

	public function getForm($form_id = null)
	{
		if(Request::ajax()) {
	        $form = CustomForm::with('questions')->where('id', '=', Input::get('id'))->get()->first();
	        
			return Response::json($form);
	    } else {
			$title = 'Edit Form';
			$form = CustomForm::with('questions')->where('id', '=', $form_id)->get()->first();

			return View::make('admin.form_edit', compact('title', 'form'));
	    }
	}

	public function postForm($form_id = null)
	{
		if(Input::get('submit') == 'save') {
			$form = Input::all();

			$rules = array(
				'form_name' 			=> 'required|unique:forms'. ($form_id ? ($form_id ? ",name,$form_id" : '') : ",name"),
				'title' 				=> 'max:255',
			);

			$validation = Validator::make($form, $rules);

			if($validation->passes()) {
				if($form_id) {
					$customForm = CustomForm::find($form_id);
					$customForm->name 			= $form['form_name'];
					$customForm->title 			= $form['title'];
					$customForm->description 	= $form['description'];
					$customForm->save();

					FormQuestion::where('form_id', '=', $form_id)->delete();
					$questions = $form["customform"];
					foreach($questions["type"] as $index => $value) {
						$formQuestion 			= new FormQuestion;
						$formQuestion->form_id 	= $form_id;
						$formQuestion->type 	= $questions["type"][$index];
						$formQuestion->label 	= $questions["label"][$index];
						$formQuestion->options 	= $questions["option"][$index];
						$formQuestion->save();
					}
				} else {
					$customForm = new CustomForm;
					$customForm->name 			= $form['form_name'];
					$customForm->title 			= $form['title'];
					$customForm->description 	= $form['description'];
					$customForm->save();

					$questions = $form["customform"];
					foreach($questions["type"] as $index => $value) {
						$formQuestion 			= new FormQuestion;
						$formQuestion->form_id 	= $customForm->id;
						$formQuestion->type 	= $questions["type"][$index];
						$formQuestion->label 	= $questions["label"][$index];
						$formQuestion->options 	= $questions["option"][$index];
						$formQuestion->save();
					}
				}

				return Redirect::route('getForm', [$customForm->id])
					->with('flash', ['message'	=>	'Saved. You can use it now on <a href="'. URL::route('practiceareas') .'">here.</a>', 
									'type'		=>	'success']);
			} else {
				return Redirect::back()
					->withInput()
					->withErrors($validation)
					->with('flash_error', 'Validation Errors!');
			}
		} else if(Input::get('submit') == 'delete') {
			if($form_id == 1) {
				return Redirect::route('forms')
					->with('flash', ['message'	=>	'Unable to delete (Default) Form!', 
									'type'		=>	'danger']);
			} else {
				FormQuestion::where('form_id', '=', $form_id)->delete();
				CustomForm::findOrFail( $form_id )->delete();
				PracticeArea::where('form_id', '=', $form_id)->update(['form_id' => 1]);

				return Redirect::route('forms')
						->with('flash', ['message'	=>	'Successfully deleted!', 
										'type'		=>	'success']);
			}
		}
	}

	public function deleteForm()
	{
		if(Input::get('id') == 1) {
			$return['flash']['type'] =	false;
			$return['flash']['message'] = "Unable to delete (Default) FOrm!";
		} else {
			FormQuestion::where('form_id', '=', Input::get('id'))->delete();
			CustomForm::findOrFail( Input::get('id') )->delete();
			PracticeArea::where('form_id', '=', Input::get('id') )->update(['form_id' => 1]);

			$return['flash']['type'] =	true;
			$return['flash']['message'] = "Successfully deleted!";
		}
		
		return Response::json($return);
	}

	public function getSetting()
	{
		$title = "Settings";
		return View::make('admin.setting', compact('title'));
	}

	public function postSetting()
	{
		$input = Input::all();

		if($input['submit'] == 'profile') {
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
				$user->save();

				return Redirect::back()
					->with('flash', ['message'	=>	"<strong>Successfully saved!</strong>", 
									'type'		=>	'success'])
					->with('validated_profile_form', true);
			} else {
				return Redirect::back()
					->withInput()
					->withErrors($validation)
					->with('validated_profile_form', true);
			}
		} else {
			Validator::extend('checkOld', function($attribute, $value, $parameters)
			{
				$hashedPassword = Auth::user()->password;
				return Hash::check($value, $hashedPassword);
			}, 'Current password is not correct.');

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
									'type'		=>	'success'])
					->with('validated_password_form', true);
			} else {
				return Redirect::back()
					->withInput()
					->withErrors($validation)
					->with('validated_password_form', true);
			}
		}
	}
}