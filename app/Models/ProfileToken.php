<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileToken extends Model
{
	use SoftDeletes;
	
	public static $TYPE_ANDROID = 1;
	public static $TYPE_IPHONE = 2;
	
	public static $ACTION_TYPE_NONE = 0;
	public static $ACTION_TYPE_OPEN_JOB = 1;
	public static $ACTION_TYPE_OPEN_GROUP = 2;
	
	public $table = 'profile_token';
	
	protected $dates = ['deleted_at'];
	
	public $fillable = [
			'profile_id',
			'token',
			'device_type',
			'is_active'
	];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
			'profile_id' => 'integer',
			'token' => 'string',
			'device_type' => 'integer',
			'is_active' => 'boolean'
	];
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
			
	];
	
	public static function sendNotification($id, $message, $action_type, $action_value, $title = null)
	{
		$ptList = ProfileToken::where('profile_id', '=', $id)->where('is_active', '=', true)->where('device_type', '=', ProfileToken::$TYPE_ANDROID)->get();
		if($ptList != null && count($ptList) > 0)
		{
			$pt = $ptList[0];
			FirebaseNotification::sendNotification($id, $pt->id, $pt->token, $message, $title, $action_type, $action_value);
		}
	}
}
