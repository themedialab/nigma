<?php

	define( 'TML_DB_TIMEZONE', isset($_COOKIE['dbTimeZone']) ? $_COOKIE['dbTimeZone'] : '+00:00' );

	class localConfig{

		const DB_CONNECTION_STRING = 'mysql:host=localhost;dbname=nigma';
		const DB_EMULATE_PREPARE = true;
		const DB_USERNAME = 'root';
		const DB_PASSWORD = 'hadita';
		const DB_CHARSET = 'utf8';
		const DB_PARAM_LOGGIN = null;
		const DB_PROFILING = null;
		const DB_TIMEZONE = \TML_DB_TIMEZONE;
		const DB_INIT_SQL = serialize ( array(
           "SET SESSION time_zone = '". \TML_DB_TIMEZONE ."'",
		) );
	}

?>