<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class MessageHandler
{
	private static $SMS_USERNAME = "5M5ZS551";
	private static $SMS_PASSWORD = "5M5ZS5";
	private static $SMS_SENDER = "iJobar";
	private static $SMS_LANGUAGE_EN = 1;
	private static $SMS_LANGUAGE_AR = 2;
	
	public static function generateRandomString($length = 4) 
	{
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	public static function sendSMS_old($mobile, $message, $language)
	{
		$post = [
			'Username' => MessageHandler::$SMS_USERNAME,
			'password' => MessageHandler::$SMS_PASSWORD,
			'language'   => ($language == "en" ? MessageHandler::$SMS_LANGUAGE_EN : MessageHandler::$SMS_LANGUAGE_AR),
			'sender' => MessageHandler::$SMS_SENDER,
			'Mobile' => str_replace_first("00", "", $mobile),
			'message' => $message
		];
		
		Log::info("sending sms " . json_encode($post));
		
		$ch = curl_init();
		
		curl_setopt( $ch,CURLOPT_URL, 'https://www.smsmisr.com/api/send/?' );
		curl_setopt( $ch,CURLOPT_POST, true );
		//curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, $post );
		
		$result = curl_exec($ch );
		curl_close( $ch );
		
		Log::info("sending sms result: " . $result);
	}
	
	public static function sendSMS($mobile, $message_txt, $language)
	{
		$message = new \stdClass();
		
		$message->from = "iJobar";
		$message->to = $mobile;
		$message->body = $message_txt;
		
		$messages = array();
		//array_push($messages, $message);
		$messages['messages'] = array($message);
		
		Log::info("sending sms " . json_encode($messages));
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://rest.clicksend.com/v3/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		
		curl_setopt($ch, CURLOPT_POST, TRUE);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages));
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"Authorization: Basic TW9oYW1lZFNhbGFoOkQ0MkRCQjdDLTA5ODAtNjhDNi04OTkxLTUyOUJFREVENUE2MA=="
		));
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		Log::info("sending sms result: " . json_encode($response));
	}
}
