<?php

class Helper{
    public static function setActive($route){
        return (Route::currentRouteName() == $route) ? "active" : '';
    }

    public static function fraction($numerator, $denominator) {
    	return $numerator . '/'. $denominator;
    }

    public static function percentage($numerator, $denominator) {
    	return number_format( ($numerator/$denominator) * 100, 2 ) . '%';
    }

    public static function getAchievementPoints($achievement_id, $num, $den) {
    	$achievement_points = Achievement::find($achievement_id)->points;
    	return (($num/$den) == 0) ? 0 : $achievement_points;
    }

    public static function updateFriendPoints($customer_id) {
    	$customer = User::find($customer_id);
    	$customer_profile = CustomerProfile::where('user_id', '=', $customer_id)->get()->first();

    	$points = 0;

		$personal_points = 0;
		$personal_numberOfTextfield = 5;
		if(!empty($customer->first_name)) 	$personal_points++;
		if(!empty($customer->last_name)) 	$personal_points++;
		if(!empty($customer->birthdate)) 	$personal_points++;
		if(!empty($customer->gender)) 		$personal_points++;
		if(!empty($customer->ethnicity)) 	$personal_points++;
		$points += helper::getAchievementPoints(7, $personal_points, $personal_numberOfTextfield);

		$contact_points = 0;
		$contact_numberOfTextfield = 7;
		if(!empty($customer->home_number)) 		$contact_points++;
		if(!empty($customer->mobile_number)) 	$contact_points++;
		if(!empty($customer->email)) 			$contact_points++;
		if(!empty($customer->street)) 			$contact_points++;
		if(!empty($customer->city)) 			$contact_points++;
		if(!empty($customer->state_id)) 		$contact_points++;
		if(!empty($customer->zip)) 				$contact_points++;
		$points += helper::getAchievementPoints(8, $contact_points, $contact_numberOfTextfield);

		$work_points = 0;
		$work_numberOfTextfield = 5;
		if(!empty($customer_profile->work_employed)) 		$work_points++;
		if(!empty($customer_profile->work_retired)) 		$work_points++;
		if(!empty($customer_profile->work_position)) 		$work_points++;
		if(!empty($customer_profile->work_salary)) 			$work_points++;
		if(!empty($customer_profile->work_income)) 			$work_points++;
		$points += helper::getAchievementPoints(9, $work_points, $work_numberOfTextfield);

		$trans_points = 0;
		$trans_numberOfTextfield = 4;
		if(!empty($customer_profile->trans_own)) 			$trans_points++;
		if(!empty($customer_profile->trans_type)) 			$trans_points++;
		if(!empty($customer_profile->trans_insurance)) 		$trans_points++;
		if(strlen($customer_profile->trans_tickets) > 0) 	$trans_points++;
		$points += helper::getAchievementPoints(10, $trans_points, $trans_numberOfTextfield);

		$edu_points = 0;
		$edu_numberOfTextfield = 5;
		if(!empty($customer_profile->edu_hs_name)) 			$edu_points++;
		if(!empty($customer_profile->edu_hs_gd)) 			$edu_points++;
		if(!empty($customer_profile->edu_coll_name)) 		$edu_points++;
		if(!empty($customer_profile->edu_coll_degree)) 		$edu_points++;
		if(!empty($customer_profile->edu_coll_gd)) 			$edu_points++;
		$points += helper::getAchievementPoints(12, $edu_points, $edu_numberOfTextfield);

		$crim_points = 0;
		$crim_numberOfTextfield = 1;
		if(!empty($customer_profile->crim_arrests)) 			$crim_points++;
		$points += helper::getAchievementPoints(11, $crim_points, $crim_numberOfTextfield);

		$sharelinks = ShareLink::where('user_id', '=', $customer_id)->get();
		foreach ($sharelinks as $sharelink) {
			$points += $sharelink->points_reward;
		}

		$customer_profile->friend_points = $points;
		$customer_profile->save();

		return $points;
    }

    public static function formatDateWithStyle($date) {
    	return date( "M d, Y h:i:s A", strtotime($date));
    }
}