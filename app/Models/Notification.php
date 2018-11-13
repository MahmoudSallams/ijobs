<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
	use SoftDeletes;
	
	public $table = 'notifications';
	
	protected $dates = ['deleted_at'];
	
	public $fillable = [
			'token_id',
			'body',
			'action_type',
			'action_value',
			'success',
			'sending_result'
	];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
			'token_id' => 'integer',
			'body' => 'string',
			'action_type' => 'integer',
			'action_value' => 'string',
			'success' => 'integer',
			'sending_result' => 'string'
	];
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
			
	];
}
