<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserContact extends Model
{
	use SoftDeletes;
	
	public $table = 'user_contacts';
	
	protected $dates = ['deleted_at'];
	
	public $fillable = [
			'profile_id',
			'user_id'
	];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
			'profile_id' => 'integer',
			'user_id' => 'integer'
	];
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
			
	];
}
