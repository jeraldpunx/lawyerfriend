<?php


$uses = 'HomeController';
if(Auth::check()) {
	if(Auth::user()->role == 'admin') {
		$uses = 'AdminController';
	} else if(Auth::user()->role == 'lawyer') {
		$uses = 'LawyersController';
	} else if(Auth::user()->role == 'customer') {
		$uses = 'UsersController';
	}
}

Route::get('/', ['as'=>'home', 'uses'=>$uses.'@index']);
Route::get('about', ['as'=>'about', 'uses'=>'HomeController@about']);
Route::get('termsandconditions', ['as'=>'termsandconditions', 'uses'=>'HomeController@termsandconditions']);
Route::get('blogs/{blog_id?}', ['as'=>'blogs', 'uses'=>'HomeController@blogs']);

Route::get('getCityByState', ['as'=>'getCityByState', 'uses'=>'HomeController@getCityByState']);

Route::group(array('before'=>'guest'), function(){
	Route::get('login', 							['as'=>'login', 			'uses'=>'HomeController@getLogin']);
	Route::post('login', 							['as'=>'login', 			'uses'=>'HomeController@postLogin']);
	Route::get('register', 							['as'=>'register', 			'uses'=>'HomeController@getRegister']);
	Route::get('register/user', 					['as'=>'register_user', 	'uses'=>'HomeController@getRegisterUser']);
	Route::get('register/lawyer', 					['as'=>'register_lawyer', 	'uses'=>'HomeController@getRegisterLawyer']);
	Route::post('register/user', 					['as'=>'register_user', 	'uses'=>'HomeController@postRegisterUser']);
	Route::post('register/lawyer', 					['as'=>'register_lawyer', 	'uses'=>'HomeController@postRegisterLawyer']);
	Route::get('registration/verify/{confirmation}', ['as'=>'verify', 			'uses'=>'HomeController@verify']);
	Route::get('password/email', 					['as'=>'remind', 			'uses'=>'HomeController@getRemind']);
	Route::post('password/email', 					['as'=>'remind', 			'uses'=>'HomeController@postRemind']);
	Route::get('password/reset/{token}', 			['as'=>'reset', 			'uses'=>'HomeController@getReset']);
	Route::post('password/reset/{token}', 			['as'=>'reset', 			'uses'=>'HomeController@postReset']);
});

Route::group(array('before'=>'auth'), function() {
	$uses = 'HomeController';
	if(Auth::check()) {
		if(Auth::user()->role == 'admin') {
			$uses = 'AdminController';
		} else if(Auth::user()->role == 'lawyer') {
			$uses = 'LawyersController';
		} else if(Auth::user()->role == 'customer') {
			$uses = 'UsersController';
		}
	}

	Route::get('overview', 							['as'=>'overview', 					'uses'=>$uses.'@overview']);
	Route::get('profile/personal', 					['as'=>'profile/personal', 			'uses'=>$uses.'@getProfilePersonal']);
	Route::post('profile/personal', 				['as'=>'profile/personal', 			'uses'=>$uses.'@postProfilePersonal']);
	Route::get('setting', 							['as'=>'setting', 					'uses'=>$uses.'@getSetting']);
	Route::post('setting', 							['as'=>'setting', 					'uses'=>$uses.'@postSetting']);


	Route::get('mail/{token?}',						['as'=>'mail', 						'uses'=>'HomeController@mail']);
	Route::post('mail/{token?}',					['as'=>'mail', 						'uses'=>'HomeController@sendMail']);
	Route::get('logout', 							['as'=>'logout', 					'uses'=>'HomeController@postLogout']);

	Route::group(array('before'=>'user'), function(){
		Route::get('find-a-lawyer', 				['as'=>'find-a-lawyer', 		'uses'=>'UsersController@findLawyer']);
		Route::post('find-a-lawyer-first', 			['as'=>'find-a-lawyer-first', 	'uses'=>'UsersController@findLawyerFirst']);
		Route::post('find-a-lawyer-second',			['as'=>'find-a-lawyer-second', 	'uses'=>'UsersController@findLawyerSecond']);
		Route::post('find-a-lawyer-third',			['as'=>'find-a-lawyer-third', 	'uses'=>'UsersController@findLawyerThird']);

		Route::get('lawyer/{lawyer_id}', 			['as'=>'lawyerView', 			'uses'=>'UsersController@lawyerView']);
		Route::post('lawyer/{lawyer_id}', 			['as'=>'sendRequest', 			'uses'=>'UsersController@sendRequest']);
		
		Route::get('profile/contact', 				['as'=>'profile/contact', 		'uses'=>'UsersController@getProfileContact']);
		Route::post('profile/contact', 				['as'=>'profile/contact', 		'uses'=>'UsersController@postProfileContact']);
		Route::get('profile/work', 					['as'=>'profile/work', 			'uses'=>'UsersController@getProfileWork']);
		Route::post('profile/work', 				['as'=>'profile/work', 			'uses'=>'UsersController@postProfileWork']);
		Route::get('profile/transportation', 		['as'=>'profile/transportation', 'uses'=>'UsersController@getProfileTransportation']);
		Route::post('profile/transportation', 		['as'=>'profile/transportation', 'uses'=>'UsersController@postProfileTransportation']);
		Route::get('profile/education', 			['as'=>'profile/education', 	'uses'=>'UsersController@getProfileEducation']);
		Route::post('profile/education', 			['as'=>'profile/education', 	'uses'=>'UsersController@postProfileEducation']);
		Route::get('profile/criminal', 				['as'=>'profile/criminal', 		'uses'=>'UsersController@getProfileCriminal']);
		Route::post('profile/criminal', 			['as'=>'profile/criminal', 		'uses'=>'UsersController@postProfileCriminal']);
	});

	Route::group(array('before'=>'lawyer'), function(){
		Route::post('profile/personal/image', 	['as'=>'profile/personal/image',	'uses'=>'LawyersController@postProfilePersonalImage']);
		Route::get('profile/practice', 			['as'=>'profile/practice', 			'uses'=>'LawyersController@getProfilePractice']);
		Route::post('profile/practice', 		['as'=>'profile/practice', 			'uses'=>'LawyersController@postProfilePractice']);
		Route::get('contacts', 					['as'=>'contacts', 					'uses'=>'LawyersController@contacts']);
		Route::get('getContact', 				['as'=>'getContact', 				'uses'=>'LawyersController@getContact']);
		Route::post('deleteContact', 			['as'=>'deleteContact', 			'uses'=>'LawyersController@deleteContact']);
		Route::get('setting/credits', 			['as'=>'getAddCredits', 			'uses'=>'LawyersController@getAddCredits']);
		Route::post('setting/credits', 			['as'=>'postAddCredits', 			'uses'=>'PaypalController@postAddCredits']);
		Route::get('setting/credits/status', 	['as'=>'getAddCreditsStatus', 		'uses'=>'PaypalController@getAddCreditsStatus']);
		Route::get('setting/change-password', 	['as'=>'getChangePassword', 		'uses'=>'LawyersController@getChangePassword']);
		Route::post('setting/change-password', 	['as'=>'postChangePassword', 		'uses'=>'LawyersController@postChangePassword']);
	});

	Route::group(array('admin'=>'admin'), function(){
		Route::get('users/{section?}', 				['as'=>'users', 			'uses'=>'AdminController@users']);
		Route::get('user/{user_id?}', 				['as'=>'user', 				'uses'=>'AdminController@user']);
		Route::get('getUser', 						['as'=>'getUser', 			'uses'=>'AdminController@getUser']);
		Route::post('postNewUser', 					['as'=>'postNewUser', 		'uses'=>'AdminController@postNewUser']);
		Route::post('postEditUser', 				['as'=>'postEditUser', 		'uses'=>'AdminController@postEditUser']);
		Route::post('deleteUser', 					['as'=>'deleteUser', 		'uses'=>'AdminController@deleteUser']);
		Route::post('postLawyerApprove', 			['as'=>'postLawyerApprove', 'uses'=>'AdminController@postLawyerApprove']);
		Route::post('postLawyerDecline', 			['as'=>'postLawyerDecline', 'uses'=>'AdminController@postLawyerDecline']);
		Route::post('customer/personal/{user_id}',	['as'=>'customer/personal', 'uses'=>'AdminController@postCustomerPersonal']);
		Route::post('customer/contact/{user_id}',	['as'=>'customer/contact', 	'uses'=>'AdminController@postCustomerContact']);
		Route::post('customer/work/{user_id}',		['as'=>'customer/work', 	'uses'=>'AdminController@postCustomerWork']);
		Route::post('customer/education/{user_id}',	['as'=>'customer/education','uses'=>'AdminController@postCustomerEducation']);
		Route::post('customer/criminal/{user_id}',	['as'=>'customer/criminal', 'uses'=>'AdminController@postCustomerCriminal']);
		Route::post('customer/transportation/{user_id}', ['as'=>'customer/transportation', 'uses'=>'AdminController@postCustomerTransportation']);
		Route::post('customer/password/{user_id}',	['as'=>'customer/password', 'uses'=>'AdminController@postCustomerPassword']);

		Route::post('lawyer/personal/image/{user_id}', 	['as'=>'lawyer/personal/image',	'uses'=>'AdminController@postLawyerPersonalImage']);
		Route::post('lawyer/personal/{user_id}',	['as'=>'lawyer/personal', 	'uses'=>'AdminController@postLawyerPersonal']);
		Route::post('lawyer/practice/{user_id}',	['as'=>'lawyer/practice', 	'uses'=>'AdminController@postLawyerPractice']);
		Route::post('lawyer/password/{user_id}',	['as'=>'lawyer/password', 	'uses'=>'AdminController@postLawyerPassword']);

		Route::get('send-credits', 					['as'=>'lawyers', 			'uses'=>'AdminController@lawyers']);
		Route::post('postSendCredits', 				['as'=>'postSendCredits', 	'uses'=>'AdminController@postSendCredits']);
		Route::get('getLawyerTransHistory', 		['as'=>'getLawyerTransHistory', 'uses'=>'AdminController@getLawyerTransHistory']);
		

		Route::get('states', 						['as'=>'states', 				'uses'=>'AdminController@states']);
		Route::get('getState', 						['as'=>'getState', 				'uses'=>'AdminController@getState']);
		Route::post('postState', 					['as'=>'postState', 			'uses'=>'AdminController@postState']);
		Route::post('deleteState', 					['as'=>'deleteState', 			'uses'=>'AdminController@deleteState']);

		Route::get('cities/{state_id?}', 			['as'=>'cities', 				'uses'=>'AdminController@cities']);
		Route::get('getCity', 						['as'=>'getCity', 				'uses'=>'AdminController@getCity']);
		Route::post('postCity/{state_id?}', 		['as'=>'postCity', 				'uses'=>'AdminController@postCity']);
		Route::post('deleteCity', 					['as'=>'deleteCity', 			'uses'=>'AdminController@deleteCity']);

		Route::get('bloglists', 					['as'=>'bloglists', 			'uses'=>'AdminController@bloglists']);
		Route::get('bloglist/{blog_id?}', 			['as'=>'getBlog', 				'uses'=>'AdminController@getBlog']);
		Route::post('bloglist/{blog_id?}', 			['as'=>'postBlog', 				'uses'=>'AdminController@postBlog']);
		Route::post('deleteBlog', 					['as'=>'deleteBlog', 			'uses'=>'AdminController@deleteBlog']);

		Route::get('friendlevels', 					['as'=>'friendlevels', 				'uses'=>'AdminController@friendlevels']);
		Route::get('getFriendLevel', 				['as'=>'getFriendLevel', 			'uses'=>'AdminController@getFriendLevel']);
		Route::post('postFriendLevel', 				['as'=>'postFriendLevel', 			'uses'=>'AdminController@postFriendLevel']);
		Route::post('deleteFriendLevel', 			['as'=>'deleteFriendLevel', 		'uses'=>'AdminController@deleteFriendLevel']);

		Route::get('practiceareas', 				['as'=>'practiceareas', 			'uses'=>'AdminController@practiceAreas']);
		Route::get('getPracticeArea', 				['as'=>'getPracticeArea', 			'uses'=>'AdminController@getPracticeArea']);
		Route::post('postPracticeArea', 			['as'=>'postPracticeArea', 			'uses'=>'AdminController@postPracticeArea']);
		Route::post('deletePracticeArea', 			['as'=>'deletePracticeArea', 		'uses'=>'AdminController@deletePracticeArea']);
		
		Route::get('forms', 						['as'=>'forms', 					'uses'=>'AdminController@forms']);
		Route::get('form/{form_id?}', 				['as'=>'getForm', 					'uses'=>'AdminController@getForm']);
		Route::post('form/{form_id?}', 				['as'=>'postForm', 					'uses'=>'AdminController@postForm']);
		Route::post('deleteForm', 					['as'=>'deleteForm', 				'uses'=>'AdminController@deleteForm']);
	});
});

Route::get('test', function() {
	// $state_id = 52;
	// $city_id = 29739;
	// // 29740

	// $state = State::findOrFail( Input::get('id') );
	// $cities = City::select('id')->where('state_id', '=', $state->id);

	// User::where('state_id', '=', $state->id)->update(['state_id' => null]);
	// User::whereIn('city_id', $cities->get()->toArray())->update(['city_id' => null]);
	// LawyerPracticeArea::where('state_id', '=', $state->id)->update(['state_id' => null]);
	// LawyerPracticeArea::whereIn('city_id', $cities->get()->toArray())->update(['city_id' => null]);

	// $cities->delete();
	// $state->delete();
	// return $users;

	// User::where('state_id', '=', Input::get('id'))
	// User::where('')

	// User::where('state_id', '=', $state_id)->update(['state_id' => null]);
});