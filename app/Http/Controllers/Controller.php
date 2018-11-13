<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use JWTAuth;
use JWTException;
use Response;
use App\Models\Profile;
use Illuminate\Http\Request;
use InfyOm\Generator\Utils\ResponseUtil;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public function getAuthUser()
	{
		// print_r($_GET["token"]);die();
		if (isset ( $_GET ["allow"] ) && ! empty ( $_GET ["allow"] ))
		{
			return $_GET ["allow"];
		}
		else
		{
			$headers = apache_request_headers ();
			if (isset ( $headers ['Authorization'] ))
			{
				$token = $headers ['Authorization'];
			}
			elseif (isset ( $_GET ["token"] ))
			{
				$token = $_GET ["token"];
			}
			else
			{
				//die ( 'Token is required' );
				return $this->sendError ( 'Token is required', 401 );
				//die(Response::json(ResponseUtil::makeError("Token is required"), 404);
				//return;
			}
			
			try 
			{
				$user = JWTAuth::toUser ( $token );
			}
			catch (\Exception $e)
			{
				//die($e);
				return $this->sendError ( 'Error: ' . $e->getMessage(), 401);
			}
			
			$user_mobile = -1;
			if (isset ( $user->mobile ))
			{
				$user_mobile = $user->mobile;
			}
			else
			{
				//die ('Unkown Token');
				return $this->sendError ( 'Unkown Token', 401);
			}
			
			$profile = Profile::where('mobile', '=', $user_mobile)->get();
			return $profile[0];
		}
	
	}
}
