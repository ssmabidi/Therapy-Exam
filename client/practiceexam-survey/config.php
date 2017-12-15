<?php
// constants
$app_version = '2.2';

// Please note: if using a DB Connector file for a job we want to base that file off of this one.

// That is, we want to:
// a) copy and paste this file to the location we define in the <DB Connector File> field in the RackForms editor.
// b) modify this file to match the properties of the database we wish to connect to.
// c) test the job to make sure the values used (user name, password, etc), are correct. 

// Please note the DB_TYPE field's accepted values are: mysql|mysqli|mssql where mysql means PDO.

// Change to 1 to see debug info if you run into problems executing your query 
// (may need to look at html page source to see error).
if(!isset($debug)) { $debug = 0; }

$db_type = 'mysqli';  // mysql = PDO :: mysqli = MySQLi :: mssql = SQL Server
$db_host = 'localhost';
$mysql_socket = '/var/lib/mysql/mysql.sock'; // E.G. /tmp/mysql.sock or /var/run/mysqld/mysqld.sock
$mysql_port = '3306'; // E.G. 3306 || This can be blank in most cases
$dbdsn = '';
$use_procedures = '0'; // 0 or 1
$db_user = 'afonseca_rf';
$db_pass = '(.)G7({*w_DU';
$db_catalog = 'afonseca_rackforms';

// need to change your timezone? http://us2.php.net/timezones
if(!defined('TIMEZONE')) { define('TIMEZONE', 'America/Los_Angeles'); }

// Build 640 - Compatibility Change
if(function_exists('date_default_timezone_set')) { 
	date_default_timezone_set(TIMEZONE); 
} else {
	ini_set('date.timezone', TIMEZONE);
}

// Build 624 - Set Directory Write Permission Level - Not Needed On Windows
if(!defined('DIRECTORY_MOD')) { define('DIRECTORY_MOD', 0755); } // Octal based UNIX permission level e.g. 0755, 0777

// Build 624 - Set File Write Permission Level - Not Needed On Windows
if(!defined('FILE_MOD')) { define('FILE_MOD', 0644); } // Octal based UNIX permission level e.g. 0644, 0664

//
// Log Database Errors.
//

// If enabled (true), logs saved to the job folder
// in a dfile called: db_error_log.log
// and/or optionally emails the address in
// RF_LOG_DATABASE_ERRORS_MAIL.

if(!defined('RF_LOG_DATABASE_ERRORS')) { define ('RF_LOG_DATABASE_ERRORS', true); }
if(!defined('RF_LOG_DATABASE_ERRORS_EMAIL')) { define ('RF_LOG_DATABASE_ERRORS_EMAIL', 'alberto@therapyexamprep.com'); }
?>
