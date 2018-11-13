<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTransaction extends Model
{
	use SoftDeletes;
	
	public static $STATUS_NEW = 1;
	public static $STATUS_SEEN = 2;
	public static $STATUS_WAITING = 3;
	public static $STATUS_HIDDEN = 4;
	
	public $table = 'job_transaction';
	
	
	protected $dates = ['deleted_at'];
	
	
	public $fillable = [
			'job_id',
			'profile_id',
			'status'
	];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
			'job_id' => 'integer',
			'profile_id' => 'integer',
			'status' => 'integer'
	];
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
			
	];
}
