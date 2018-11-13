<?php
return [
	'stack' => [
		'driver' => 'stack',
		'channels' => ['single', 'slack'],
	],
	'syslog' => [
		'driver' => 'syslog',
		'level' => 'debug',
	],
]
?>