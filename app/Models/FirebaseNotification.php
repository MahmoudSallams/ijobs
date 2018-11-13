<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class FirebaseNotification
{
	private static $API_ACCESS_KEY = 'AAAAzzdX6w8:APA91bGNG2sqFQxn1B1axGqVlks4tJokoIGQEMN-KMEwhFkIXrDmJoAMgeSu0Z8I5xI-U-er-Ha9mbHTIkc-5V1J4JMXcCMvEA390sWAn6Hkjwe30tI5Dl5IIXJ-gvrAB1m6wXWUa_RY';
	public static function sendNotification($profile_id, $token_id, $token, $message, $title, $action_type, $action_value)
	{
		#API access key from Google API's Console
		//define( 'API_ACCESS_KEY', 'AAAAzzdX6w8:APA91bGNG2sqFQxn1B1axGqVlks4tJokoIGQEMN-KMEwhFkIXrDmJoAMgeSu0Z8I5xI-U-er-Ha9mbHTIkc-5V1J4JMXcCMvEA390sWAn6Hkjwe30tI5Dl5IIXJ-gvrAB1m6wXWUa_RY' );
		
		//$registrationIds = $_GET['id'];
		#prep the bundle
		$msg = array
		(
				'body' 	=> $message,
				//'title'	=> $title,
				//'icon'	=> 'myicon',/*Default Icon*/
				'sound' => 'Enabled'/*Default sound*/,
				'action_type' => $action_type,
				'action_value' => $action_value,
		);
		
		$fields = array
		(
				'registration_ids' => array($token),
				//'notification' => $msg,
				'data' => $msg
		);
		
		$headers = array
		(
				'Authorization: key=' . FirebaseNotification::$API_ACCESS_KEY,
				'Content-Type: application/json'
		);
		Log::info("sending notification");
		Log::info(json_encode( $fields ));
		#Send Reponse To FireBase Server
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		#Echo Result Of FireBase Server
		//echo $result;
		
		$resultJo = json_decode($result);
		
		$n = new Notification();
		$n->profile_id = $profile_id;
		$n->token_id = $token_id;
		$n->body = $message;
		$n->action_type = $action_type;
		$n->action_value = json_encode($action_value);
		$n->success = $resultJo->success;
		$n->sending_result = json_encode($resultJo->results);
		
		$n->save();
	}
}

?>