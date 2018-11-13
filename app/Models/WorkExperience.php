<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkExperience extends Model
{
	use SoftDeletes;
	
	public $table = 'work_experience';
	
	protected $dates = ['deleted_at'];
	
	public $fillable = [
			'profile_id',
			'title',
			'company_name',
			'from',
			'to'
	];
	
	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
			'profile_id' => 'integer',
			'title' => 'string',
			'company_name' => 'string'
	];
	
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
			
	];
}
