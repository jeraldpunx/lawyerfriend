<?php

class CustomerProfile extends \Eloquent {
	protected $fillable = [];
	protected $table = 'customer_profiles';
	public $timestamps = false;

	public static function getFriendLevel($user_id)
	{
		$friend_level = DB::SELECT(DB::RAW("SELECT s.*
											FROM customer_profiles t
											INNER JOIN friend_levels s ON(t.friend_points >= s.points_needed)
											WHERE s.points_needed = (SELECT max(f.points_needed)
																	FROM friend_levels f
																	WHERE t.friend_points >= f.points_needed)
											AND t.user_id = $user_id"));

		return $friend_level[0];

	}

	public static function getFriendLevelPoints($user_id)
	{
		$status = DB::SELECT(DB::RAW("
			SELECT  (SELECT max(fl.points_needed) FROM friend_levels fl WHERE fl.points_needed <= cp.friend_points) AS prev_level, 
					(SELECT min(fl.points_needed) FROM friend_levels fl WHERE fl.points_needed > cp.friend_points) AS next_level, 
					(SELECT min(fl.points_needed) FROM friend_levels fl WHERE fl.points_needed > cp.friend_points) - cp.friend_points AS next_level_needed, cp.friend_points FROM customer_profiles cp WHERE cp.user_id = $user_id"));

		return $status[0];
	}

	public static function getCurrentRateByCity($rate, $user_id)
	{
		$rate_percent = (User::find($user_id)->city_id) ? City::find(User::find($user_id)->city_id)->rate_percent : 1;
		$city_percent = number_format($rate_percent,2);
		$current_rate = $rate * $city_percent;
		
		return $current_rate;
	}

	public static function getCurrentRateByCityAndPracticeArea($practice_area_id, $city_id, $friend_level_percent)
	{
		$city_rate_percent = (City::find($city_id)->rate_percent) ? City::find($city_id)->rate_percent : 1;
		$city_rate_percent = number_format($city_rate_percent,2);

		$friend_level_rate_percent = number_format($friend_level_percent,2);

		$practice_area_rate = (PracticeArea::find($practice_area_id)->rate) ? PracticeArea::find($practice_area_id)->rate : 1;
		

		$current_rate = $practice_area_rate * $friend_level_rate_percent * $city_rate_percent;
		
		return $current_rate;
	}
}