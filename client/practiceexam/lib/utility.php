<?php


// set existing values

/**
 * Save A Specific Persiststant Entry Value.
 * Used for specific items, such as individual calendar fields.
 *
 * Example:
 * savePersistantValue($persistance, \$db_key, 'date-{$post_session_element}-2', \$date_{$post_session_element}_2);
 *
 * @since 689
 *       
 * @param int $db_key        	
 * @param string $field_name        	
 * @param string $field_value        	
 */
function savePersistantValue($persistance, $db_key, $field_name, $field_value) {
	if ($persistance == 0) {
		return;
	}
	
	$db_catalog = '';
	@include 'Database.php';
	$dbh = new Database ();
	
	if (is_array ( $field_value )) {
		$value = '';
		foreach ( $field_value as $idx => $v ) {
			if ($idx != 0) {
				$value .= '|';
			}
			$value .= $v;
		}
	} else {
		$value = $field_value;
	}
	
	$timestamp = time ();
	$remote_ip = $_SERVER ['REMOTE_ADDR'];
	
	$sql = "SELECT COUNT(id) AS ct FROM fb_jobs WHERE id = ?;";
	$params = array (
			( int ) $db_key 
	);
	$job_row_count = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
	
	if ($job_row_count == 0) {
		return;
	}
	
	// insert row data
	$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, entry_value, entry_key, remote_ip) VALUES (?,?,?,?,?,?);";
	$params = array (
			( int ) $db_key,
			( int ) $timestamp,
			( string ) $field_name,
			( string ) $value,
			( string ) $_SESSION['entry_key_practiceexam'],
			( string ) $remote_ip 
	);
	$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
}

/**
 * Saves The Majority Of Our Persistance Values.
 * Called in PHPElement Process Page Before Redirects and After All Processing.
 * 
 * Build 842 - Added $resume_mode and $on_success
 *
 *
 * @since 689
 * @param int $persistance        	
 * @param int $db_key        	
 */
function savePersistantValues($persistance, $db_key, $resume_mode, $on_success) {
	
	if ($persistance == 0) {
		return;
	}
	
	$debug = 0; // set to 1 if errors occur.
	
	$db_catalog = '';
	@include 'Database.php';
	$dbh = new Database ();
	
	$timestamp = time ();
	$remote_ip = $_SERVER ['REMOTE_ADDR'];
	
	// Build 714 - Switch for IP vs. SESSION based.
	switch ($persistance) {
		case 1 :
			$sql = "SELECT name FROM fb_job_entries_tmp WHERE job_id = ? AND remote_ip = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $remote_ip 
			);
			break;
		case 2 :
			$sql = "SELECT name FROM fb_job_entries_tmp WHERE job_id = ? AND fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $_SESSION ['fb_entry_id'] 
			);
			break;
		case 3 :
			$sql = "SELECT name FROM fb_job_entries_tmp WHERE job_id = ? AND fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $_SESSION ['fb_entry_id_auto']
			);
			break;
	}
	
	$rows = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
	
	$keys = array ();
	
	// Build 695 - Prevent First Insert From Triggering foreach() Notice.
	if (is_array ( $rows )) {
		foreach ( $rows as $row ) {
			$keys [] = $row ['name'];
		}
	}
	
	// Build 694 - Save Page Access Data
	$pages = "";
	$pages = implode ( '|', array_keys ( $_SESSION ['pages'] ) );
	
	$key = "FB_PAGE_ACCESS";
	
	switch (( int ) $persistance) {
		
		case 1 :
			if (in_array ( 'FB_PAGE_ACCESS', $keys )) {
				// UPDATE
				$sql = "UPDATE fb_job_entries_tmp SET entry_value = ? WHERE name = ? AND job_id = ? AND remote_ip = ?;";
				$params = array (
						( string ) $pages,
						( string ) $key,
						( int ) $db_key,
						( string ) $remote_ip 
				);
				$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
			} else {
				// INSERT
				$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, entry_value, entry_key, remote_ip) VALUES (?,?,?,?,?,?);";
				$params = array (
						( int ) $db_key,
						( int ) $timestamp,
						( string ) $key,
						( string ) $pages,
						( string ) $_SESSION['entry_key_practiceexam'],
						( string ) $remote_ip 
				);
				$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
			}
			break;
		
		case 2 :
			if (! isset ( $_SESSION ['fb_entry_id'] )) {
				return;
			}
			if (in_array ( 'FB_PAGE_ACCESS', $keys )) {
				
				// UPDATE
				$sql = "UPDATE fb_job_entries_tmp SET entry_value = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
				$params = array (
						( string ) $pages,
						( string ) $key,
						( int ) $db_key,
						( string ) $_SESSION ['fb_entry_id'] 
				);
				
				$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
			} else {
				
				// INSERT
				$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, entry_value, entry_key, remote_ip, fb_entry_id) VALUES (?,?,?,?,?,?,?);";
				
				$params = array (
						( int ) $db_key,
						( int ) $timestamp,
						( string ) $key,
						( string ) $pages,
						( string ) $_SESSION['entry_key_practiceexam'],
						( string ) $remote_ip,
						( string ) $_SESSION ['fb_entry_id'] 
				);
				
				$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
			}
			break;
			
		// Build 836 - Same as Type 2, though we use fb_entry_id_auto instead of fb_entry_id
		case 3 :
			if (! isset ( $_SESSION ['fb_entry_id_auto'] )) {
				return;
			}
			if (in_array ( 'FB_PAGE_ACCESS', $keys )) {
		
				// UPDATE
				$sql = "UPDATE fb_job_entries_tmp SET entry_value = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
				$params = array (
						( string ) $pages,
						( string ) $key,
						( int ) $db_key,
						( string ) $_SESSION ['fb_entry_id_auto']
				);
		
				$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
			} else {
		
				// INSERT
				$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, entry_value, entry_key, remote_ip, fb_entry_id) VALUES (?,?,?,?,?,?,?);";
		
				$params = array (
						( int ) $db_key,
						( int ) $timestamp,
						( string ) $key,
						( string ) $pages,
						( string ) $_SESSION['entry_key_practiceexam'],
						( string ) $remote_ip,
						( string ) $_SESSION ['fb_entry_id_auto']
				);
		
				$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
			}
			break;
			
	} // page access
	
	
	// Build 882 - Collect file upload items.
	
	$file_upload_items = array();
	
	$triggers = array('_mime', '_name', '_size');
	
	foreach ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] as $key => $value ) {
		
		foreach($triggers as $t){
			
			if(isset($_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key])) {
				
				if(substr_count($key, $t) == 1) {
				
					// get original name
					$original_name_parts = explode($t, $key);
				
					$original_name = $original_name_parts[0];
				
					$file_upload_items[] = $original_name;
				
				}
				
			}
	
		}
		
	}
	
	
	
	
	foreach ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] as $key => $value ) {
		
		// Build 695 - Prevent fb_login StdObj from being saved.
		if (is_object ( $value )) {
			continue;
		}
		
		if (is_array ( $value )) {
			$field_value = '';
			foreach ( $value as $idx => $v ) {
				// all checkbox (array) items must have the splitter for re-population.
				if(!is_array($v)) // Prevent Buckets From Processing
					$field_value .= $v . '|';
			}
		} else {
			$field_value = $value;
		}
		
		
		// Build 882 - Is this file upload data?
		
		$is_file_upload_data = false;
		
		if(in_array($key, $file_upload_items)){
			$is_file_upload_data = true;
		}
		
		// Do not individually process these fields.
		
		foreach($file_upload_items as $fi) {
			
			if($key == $fi . '_name'){ continue; }
			if($key == $fi . '_mime'){ continue; }
			if($key == $fi . '_size'){ continue; }
			
		}
		
		
		// Build 883 - Is this signature data?
		
		$is_sig_data = false;
		
		if ($key == 'signatures') {
			
			$is_sig_data = true;
			
		}
		
		
		// loop over each item
		switch (( int ) $persistance) {
			case 1 :
				if (in_array ( $key, $keys )) {
					
					// UPDATE
					
					if($is_file_upload_data && $field_value != null) {
						
						$sql = "UPDATE fb_job_entries_tmp SET entry_value = ?, file_data = ?, file_mime = ?, file_name = ? WHERE name = ? AND job_id = ? AND remote_ip = ?;";
						$params = array (
								'', // entry_value
								( string ) $field_value, // file data
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_mime"], // file_mime
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_name"], // file_name
								( string ) $key,
								( int ) $db_key,
								( string ) $remote_ip
						);
						
					} elseif ($is_sig_data) {
						
						$serialized_data = serialize($value);
						
						$sql = "UPDATE fb_job_entries_tmp SET sig_data = ?, remote_ip = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
						$params = array (
								( string ) $serialized_data,
								( string ) $remote_ip,
								( string ) $key,
								( int ) $db_key,
								( string ) $_SESSION ['fb_entry_id_auto']
						);
						
					} else {
						
						$sql = "UPDATE fb_job_entries_tmp SET entry_value = ? WHERE name = ? AND job_id = ? AND remote_ip = ?;";
						
						$params = array (
								( string ) $field_value,
								( string ) $key,
								( int ) $db_key,
								( string ) $remote_ip
						);
						
					}

					$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
				
				} else {
					
					// INSERT
					
					if($is_file_upload_data && $field_value != null) {
						
						$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, entry_value, file_data, file_mime, file_name, entry_key, remote_ip) VALUES (?,?,?,?,?,?,?,?,?,?);";
							
						$params = array (
								( int ) $db_key,
								( int ) $timestamp,
								( string ) $key, // name
								( string ) $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key], // label
								( string ) '', // entry value
								$field_value, // file data
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_mime"], // file_mime
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_name"], // file_name
								( string ) $_SESSION['entry_key_practiceexam'], // entry_key
								( string ) $remote_ip, // remote_ip
						);
						
					} elseif ($is_sig_data) {
						
						$serialized_data = serialize($value);
						
						$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, sig_data, entry_key, remote_ip) VALUES (?,?,?,?,?,?,?);";
						$params = array (
								( int ) $db_key,
								( int ) $timestamp,
								( string ) 'signatures',
								( string ) 'signatures',
								( string ) $serialized_data,
								( string ) $_SESSION['entry_key_practiceexam'],
								( string ) $remote_ip
						);
						
					} else {
						
						$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, entry_value, entry_key, remote_ip) VALUES (?,?,?,?,?,?,?);";
						$params = array (
								( int ) $db_key,
								( int ) $timestamp,
								( string ) $key,
								( string ) $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key],
								( string ) $field_value,
								( string ) $_SESSION['entry_key_practiceexam'],
								( string ) $remote_ip
						);
						
					}
					
					$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
				}
				break;
				
			case 2 :
				if (! isset ( $_SESSION ['fb_entry_id'] )) {
					return;
				}
				if (in_array ( $key, $keys )) {
					
					// UPDATE
					
					if($is_file_upload_data && $field_value != null) {
						
						$sql = "UPDATE fb_job_entries_tmp SET entry_value = ?, file_data = ?, file_mime = ?, file_name = ?, remote_ip = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
						$params = array (
								'', // entry_value
								( string ) $field_value, // file data
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_mime"], // file_mime
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_name"], // file_name
								( string ) $remote_ip,
								( string ) $key,
								( int ) $db_key,
								( string ) $_SESSION ['fb_entry_id']
						);
						
					} elseif ($is_sig_data) {
					
						$sql = "UPDATE fb_job_entries_tmp SET sig_data = ? WHERE name = ? AND job_id = ? AND remote_ip = ?;";
					
						$params = array (
								( string ) json_encode($value),
								( string ) $key,
								( int ) $db_key,
								( string ) $remote_ip
						);
						
					} else {
						
						$sql = "UPDATE fb_job_entries_tmp SET entry_value = ?, remote_ip = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
						$params = array (
								( string ) $field_value,
								( string ) $remote_ip,
								( string ) $key,
								( int ) $db_key,
								( string ) $_SESSION ['fb_entry_id']
						);
						
					}
					
					
					$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
				
				} else {
					
					// INSERT - Build 715 - Fixed Parameter Count
					
					if($is_file_upload_data && $field_value != null) {
						
						// INSERT - File Data
						$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, entry_value, file_data, file_mime, file_name, entry_key, remote_ip, fb_entry_id) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
							
						$params = array (
								( int ) $db_key,
								( int ) $timestamp,
								( string ) $key, // name
								( string ) $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key], // label
								( string ) '', // entry value
								$field_value, // file data
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_mime"], // file_mime
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_name"], // file_name
								( string ) $_SESSION['entry_key_practiceexam'], // entry_key
								( string ) $remote_ip, // remote_ip
								( string ) $_SESSION ['fb_entry_id'] // fb_entry_id
						);
						
					} elseif ($is_sig_data) {
					
						$serialized_data = serialize($value);
						
						$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, sig_data, entry_key, remote_ip) VALUES (?,?,?,?,?,?,?);";
						$params = array (
								( int ) $db_key,
								( int ) $timestamp,
								( string ) 'signatures',
								( string ) 'signatures',
								( string ) $serialized_data,
								( string ) $_SESSION['entry_key_practiceexam'],
								( string ) $remote_ip
						);
						
					} else {
						
						$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, entry_value, entry_key, remote_ip, fb_entry_id) VALUES (?,?,?,?,?,?,?,?);";
							
						$params = array (
								( int ) $db_key,
								( int ) $timestamp,
								( string ) $key,
								( string ) $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key],
								( string ) $field_value,
								( string ) $_SESSION['entry_key_practiceexam'],
								( string ) $remote_ip,
								( string ) $_SESSION ['fb_entry_id']
						);
						
					}
					
					
					$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
				}
				break;
				
			case 3 :
				
				if (! isset ( $_SESSION ['fb_entry_id_auto'] )) {
					return;
				}
				
				if (in_array ( $key, $keys )) {
						
					// UPDATE
					
					if($is_file_upload_data && $field_value != null) {
					
						$sql = "UPDATE fb_job_entries_tmp SET entry_value = ?, file_data = ?, file_mime = ?, file_name = ?, remote_ip = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
						$params = array (
								'', // entry_value
								( string ) $field_value, // file data
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_mime"], // file_mime
								( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_name"], // file_name
								( string ) $remote_ip,
								( string ) $key,
								( int ) $db_key,
								( string ) $_SESSION ['fb_entry_id_auto']
						);
					
					} else {
						
						$sql = "UPDATE fb_job_entries_tmp SET entry_value = ?, remote_ip = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
						$params = array (
								( string ) $field_value,
								( string ) $remote_ip,
								( string ) $key,
								( int ) $db_key,
								( string ) $_SESSION ['fb_entry_id_auto']
						);
						
					}
					
					if ($is_sig_data) {
							
						$serialized_data = serialize($value);
						
						$sql = "UPDATE fb_job_entries_tmp SET sig_data = ?, remote_ip = ? WHERE name = ? AND job_id = ? AND fb_entry_id = ?;";
						$params = array (
								( string ) $serialized_data,
								( string ) $remote_ip,
								( string ) $key,
								( int ) $db_key,
								( string ) $_SESSION ['fb_entry_id_auto']
						);
					
					}
						
					$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
				
				} else {
						
					if(isset($_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key])){
						
						if($is_file_upload_data && $field_value != null) {
								
							// INSERT - File Data
							$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, entry_value, file_data, file_mime, file_name, entry_key, remote_ip, fb_entry_id) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
								
							$params = array (
									( int ) $db_key,
									( int ) $timestamp,
									( string ) $key, // name
									( string ) $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key], // label
									( string ) '', // entry value
									$field_value, // file data
									( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_mime"], // file_mime
									( string ) $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$key}_name"], // file_name
									( string ) $_SESSION['entry_key_practiceexam'], // entry_key
									( string ) $remote_ip, // remote_ip
									( string ) $_SESSION ['fb_entry_id_auto'] // fb_entry_id
							);
								
						} else {
							
							// INSERT - Non-file data.
							$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, entry_value, entry_key, remote_ip, fb_entry_id) VALUES (?,?,?,?,?,?,?,?);";
							
							$params = array (
									( int ) $db_key,
									( int ) $timestamp,
									( string ) $key,
									( string ) $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] [$key],
									( string ) $field_value,
									( string ) $_SESSION['entry_key_practiceexam'],
									( string ) $remote_ip,
									( string ) $_SESSION ['fb_entry_id_auto']
							);
							
						}
						
						
					}

					if ($is_sig_data) {
					
						$serialized_data = serialize($value);
						
						$sql = "INSERT INTO fb_job_entries_tmp(job_id, ts, name, label, sig_data, entry_key, remote_ip, fb_entry_id) VALUES (?,?,?,?,?,?,?,?);";
						$params = array (
								( int ) $db_key,
								( int ) $timestamp,
								( string ) 'signatures',
								( string ) 'signatures',
								( string ) $serialized_data,
								( string ) $_SESSION['entry_key_practiceexam'],
								( string ) $remote_ip,
								( string ) $_SESSION ['fb_entry_id_auto'] // fb_entry_id
						);
						
					}
					
					$dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, 1 );
				}
				break;
				
		} // switch
	}
	
	// Build 836 - Save Prompt For User.
	
	if(isset($_POST['rf_save_and_return']) && $_POST['rf_save_and_return'] == "true" && isset ( $_SESSION ['fb_entry_id_auto'] )){
		
		// Build 854
		$persistancelanguage = 0;
		
		if(isset($_SESSION["{$_SESSION['entry_key_practiceexam']}"]['SAVE_AND_RESUME_LANGUAGE'])){
			$persistancelanguage = (int)$_SESSION["{$_SESSION['entry_key_practiceexam']}"]['SAVE_AND_RESUME_LANGUAGE'];
		}
		
		switch($persistancelanguage){
			case '0' : // English
				$loc_title = "Save And Return";
				$loc_instructions = "Please visit this address to resume";
				$loc_back_button = "Press the <strong>Back</strong> button on your browser to continue working on the form.";
				$loc_email_link = "Email the link to me";
				$loc_email_placeholder = "Email Address";
				$loc_submit = "Send Email Reminder";
				break;
			case '1' : // Dutch
				$loc_title = "Opslaan en later bewerken";
				$loc_instructions = "U kunt dit adres bezoeken om later verder te gaan";
				$loc_back_button = "Druk op de knop Terug in uw browser om het het formulier verder in te vullen.";
				$loc_email_link = "Stuur naar e-mailadres";
				$loc_email_placeholder = "e-Mailadres";
				$loc_submit = "Voorleggen";
				break;
			case 2 : // German
				$loc_title = "Zu speichern und später";
				$loc_instructions = "Bitte besuchen Sie diese Adresse , um fortzufahren";
				$loc_back_button = "Drücken Sie die Zurück -Taste auf Ihrem Browser, um weiter zu arbeiten auf dem Formular.";
				$loc_email_link = "Mailen Sie den Link mich";
				$loc_email_placeholder = "E-Mail-Addresse";
				$loc_submit = "Einreichen";
				break;
			case 3 : // Italian
				$loc_title = "Salvare e tornare più tardi";
				$loc_instructions = "Si prega di visitare questo indirizzo per riprendere";
				$loc_back_button = "Premere il pulsante Indietro del browser per continuare a lavorare sul modulo .";
				$loc_email_link = "Invia il link a me";
				$loc_email_placeholder = "Indirizzo E-Mail";
				$loc_submit = "Presentare";
				break;
			case 4 : // Spanish
				$loc_title = "Guardar y volver más tarde";
				$loc_instructions = "Por favor, visite esta dirección para reanudar";
				$loc_back_button = "Pulse el botón Atrás de su navegador para continuar trabajando en el formulario.";
				$loc_email_link = "Enviar el enlace a mí";
				$loc_email_placeholder = "Dirección De Correo Electrónico";
				$loc_submit = "Presentar";
				break;
					
		}
		
		
		
		$address = curPageURL();
		
		// Remove _process
		
		$address = str_replace('_process', '', $address);
		
		$post_address = $_SERVER['PHP_SELF'];
		
		$resume_mode_url = "LastVisited";
		
		// Build 842 - Use next page address if set
		if(isset($resume_mode) && (int)$resume_mode == 1){
			
			$current_page_name = basename($_SERVER['PHP_SELF']);
			
			// Replace with $on_success value.
			$address = str_replace($current_page_name, $on_success, curPageURL());
			
			$resume_mode_url = "NextInSequence";
			
		}
		
		// Build 838 - Email Link Support.
		
		$allow_email = 1; // Future Option Support / Easy Toggle
		
		$email_code = "";
		
		if($allow_email){
				
			// Set session security / limit element
				
			if(!isset($_SESSION["{$_SESSION['entry_key_practiceexam']}"]['SAVE_AND_RESUME_EMAIL_LINK']))
				$_SESSION["{$_SESSION['entry_key_practiceexam']}"]['SAVE_AND_RESUME_EMAIL_LINK'] = 0;
			
				
			$email_code = <<<EOF
<form action="{$post_address}" method="post" enctype="application/x-www-form-urlencoded" name="email_link" id="email_link" target="_self">
		
	<input type="hidden" name="address" id="address" value="{$address}?RID={$_SESSION ['fb_entry_id_auto']}&ResumeMode={$resume_mode_url}">
	<input type="hidden" name="rf_save_and_return" id="rf_save_and_return" value="true">
			
	<div style="clear:both; margin-bottom:3px;">
		<label for="email">{$loc_email_link}:
			<input type="email" name="email" id="email" placeholder="{$loc_email_placeholder}" >
		</label>
	</div>
		
	<div style="clear:both; width:200px;">
		<input type="button" name="submit" value="{$loc_submit}" onclick="submit_email();">
	</div>

	<div id="email-status" style="clear:both;">
		<span id="form-error"></span>
		<span id="form-status"></span>
	</div>
			
</form>
		
EOF;
				
			// Do not allow multiple submissions over a set limit
			
			if(isset($_SESSION["{$_SESSION['entry_key_practiceexam']}"]['SAVE_AND_RESUME_EMAIL_LINK']) && $_SESSION["{$_SESSION['entry_key_practiceexam']}"]['SAVE_AND_RESUME_EMAIL_LINK'] >= 3){
				
				$email_code = "";
		
			}
			
		
		} // $allow_email

		
		$html = <<<EOF
<!DOCTYPE html> 
 
<html> 

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>{$loc_title}</title>
		
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		
		<script type="text/javascript">
		
			function submit_email(){
				
				$.ajax({
			      type: "POST",
			       url: "./lib/email_save_return_link.php",
			       data: { email: $('#email').val(), address: $('#address').val(), rf_save_and_return: $('#rf_save_and_return').val() }
			      })
			    .done(function( msg ) {
			    
			    	$('#form-status').html("");
			    	$('#form-error').html("");
			    	
			    	$('#email').val("");
			    
			      if(msg.EMAIL_STATUS != "")
			      	$('#form-status').html(msg.EMAIL_STATUS);
			      	
			      if(msg.EMAIL_ERROR != "")
			      	$('#form-error').html(msg.EMAIL_ERROR);
			      	
			    }).fail(function(msg) {
			    
			    	$('#form-status').html("");
			    	$('#form-error').html("");
				    
				    if(msg.EMAIL_STATUS != "")
			      		$('#form-status').html(msg.EMAIL_STATUS);
			      		
			      	if(msg.EMAIL_ERROR != "")
			      		$('#form-error').html(msg.EMAIL_ERROR);
			      		
				});
				    
			}
		
		</script>
				
		<style type="text/css">
				
		*{
		   margin:0;
		   padding:0;
		}
		
		body { color: #535353; background-color:#f4f4f4; font-family:Arial; }
		
		p { margin-bottom: 9px; }
		
		h1 {
		  color: #95e000;
		  font-size: 24pt;
		  font-weight: bold;
		  text-transform: uppercase;
		}
		
		div.Absolute-Center {
		  	background: none repeat scroll 0 0 #fafafa;
			top: 50px;
			height: 315px;
			left: 0;
			margin: auto;
			position: absolute;
			right: 0;
			width: 100%;
		}
		
		#inner {
			padding:15px;
		
		}
		
		input {
		  border: 1px solid #dedede;
		  color: #585549;
		  font-size: 12pt;
		  height: 25px;
		  padding: 3px;
		  width: 99%;
		}
		
		input[type="button"] {
		  background-color: #95e000;
		  border: 1px solid #dedede;
		  color: white;
		  cursor: pointer;
		  font-size: 12pt;
		  height: 30px;
		  padding: 3px;
		  width: 99%;
		}
		
		#email-status {
			padding:5px;
			margin-top:10px;
			border-top:1px dotted #c2c2c2;
		}
		
		#form-error { color:red; }
		#form-status { color: #95e000; }
				
		</style>
	</head>
				
<body>
				
	<div class="Absolute-Center">
	
		<div id="inner">

			<h1>{$loc_title}</h1>
					
			<p>{$loc_instructions}:</p>
					
			<p><input type="text" value="{$address}?RID={$_SESSION ['fb_entry_id_auto']}&ResumeMode={$resume_mode_url}" onclick="this.select();"></p>
					
			<div style="clear:both;">
				{$email_code}
			</div>

			<br/><br/>
					
			<p>{$loc_back_button}</p>
						
			<div style="clear:both;"></div>
					
		</div>

	</div>
				
</body>
</html>
		
EOF;
		
		
		echo $html;
		
		die;
			
	}
	
}

/**
 * Load Persistant Values On Page init.
 *
 * * Build 694 - Added Page Name Parameter
 *
 * @param int $persistance        	
 * @param int $db_key        	
 * @param string $page_name        	
 */
function loadPersistantValues($persistance, $db_key, $page_name) {
	if ($persistance == 0) {
		return;
	}
	
	// Build 694 - Allow this page to save strait away so that a user can access the last page they were on.
	$_SESSION ['pages'] ["{$page_name}"] = 'pass';
	
	$db_catalog = '';
	@include 'Database.php';
	$dbh = new Database ();
	
	$remote_ip = $_SERVER ['REMOTE_ADDR'];
	
	// does this job exist?
	$sql = "SELECT COUNT(id) AS ct FROM fb_jobs WHERE id = ?;";
	$params = array (
			( int ) $db_key 
	);
	$job_row_count = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
	
	if ($job_row_count [0] ['ct'] == 0) {
		return;
	}
	
	// if yes, do we have existing values?
	
	switch (( int ) $persistance) {
		case 1 :
			$sql = "SELECT COUNT(id) AS ct FROM fb_job_entries_tmp WHERE job_id = ? AND remote_ip = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $remote_ip 
			);
			$entry_row_count = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
			
			if ($entry_row_count [0] ['ct'] == 0) {
				return;
			}
			
			$sql = "SELECT * FROM fb_job_entries_tmp WHERE job_id = ? AND remote_ip = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $remote_ip 
			);
			$entry_rows = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
			break;
		
		case 2 :
			if (! isset ( $_SESSION ['fb_entry_id'] )) {
				return;
			}
			
			// sql lookup
			$sql = "SELECT COUNT(id) AS ct FROM fb_job_entries_tmp WHERE job_id = ? AND fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $_SESSION ['fb_entry_id'] 
			);
			$entry_row_count = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
			
			if ($entry_row_count [0] ['ct'] == 0) {
				return;
			}
			
			$sql = "SELECT * FROM fb_job_entries_tmp WHERE job_id = ? AND fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $_SESSION ['fb_entry_id'] 
			);
			$entry_rows = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
			break;

		// Build 836
		case 3 :
			if (! isset ( $_SESSION ['fb_entry_id_auto'] )) {
				return;
			}
				
			// sql lookup
			$sql = "SELECT COUNT(id) AS ct FROM fb_job_entries_tmp WHERE job_id = ? AND fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $_SESSION ['fb_entry_id_auto']
			);
			$entry_row_count = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
				
			if ($entry_row_count [0] ['ct'] == 0) {
				return;
			}
				
			$sql = "SELECT * FROM fb_job_entries_tmp WHERE job_id = ? AND fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $_SESSION ['fb_entry_id_auto']
			);
			$entry_rows = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 0 );
			break;
	}
	
	// loop and populate our SESSION elements
	if ($entry_rows != false) {
		
		$entry_key = $entry_rows [0] ['entry_key'];
		
		foreach ( $entry_rows as $row ) {
			
			// Build 694 - Page Access Logic
			if ($row ['name'] == 'FB_PAGE_ACCESS') {
				$pages = explode ( "|", $row ['entry_value'] );
				foreach ( $pages as $p ) {
					// populate pages array
					$_SESSION ["pages"] ["{$p}"] = "pass";
					$_SESSION['pages-passed']["{$_SESSION['entry_key_practiceexam']}"]["{$p}"] = 'pass'; // Build 877
				}
			} elseif ($row ['name'] == 'signatures') {
				// Restore signature data.
				$_SESSION['qs']["{$_SESSION['entry_key_practiceexam']}"]["signatures"] = unserialize($row['sig_data']);
			} else {
				if (substr_count ( $row ['entry_value'], "|" ) != 0) {
					$entry_value = explode ( "|", $row ['entry_value'] );
					$tmp = array ();
					foreach ( $entry_value as $val ) {
						if ($val != "") {
							$tmp [] = $val;
						}
					}
					$_SESSION ["{$row['name']}"] = $tmp;
					
					// Build 694 - Load Extended Values
					$_SESSION ['qs'] ["{$entry_key}"] ["{$row['name']}"] = $row ['entry_value'];
					$_SESSION ['qs-label'] ["{$entry_key}"] ["{$row['label']}"] = $row ['entry_value'];
					$_SESSION ['qs-label'] ["{$entry_key}"] ["{$row['name']}"] = $row ['label'];
					$_SESSION ['qs-entities'] ["{$entry_key}"] ["{$row['name']}"] = $row ['label'];
				} else {
					$_SESSION ["{$row['name']}"] = $row ['entry_value'];
					
					// Build 694 - Load Extended Values
					$_SESSION ['qs'] ["{$entry_key}"] ["{$row['name']}"] = $row ['entry_value'];
					$_SESSION ['qs-label'] ["{$entry_key}"] ["{$row['label']}"] = $row ['entry_value'];
					$_SESSION ['qs-label'] ["{$entry_key}"] ["{$row['name']}"] = $row ['label'];
					$_SESSION ['qs-entities'] ["{$entry_key}"] ["{$row['name']}"] = $row ['label'];
				}
			}
		}
		
		// populate entry_key
		$_SESSION ['entry_key'] = $entry_key;
	}
}

/**
 * Clear Persistance Values - Always Called From Confirmation Page, and Always Executes.
 *
 * Build 690 - Made return_true = 1
 *
 * @param int $db_key        	
 */
function clearPersistantValues($persistance, $db_key) {
	
	// Build 690 - Allow this to be passed over.
	if ($persistance == 0) {
		return;
	}
	
	$db_catalog = '';
	@include 'Database.php';
	$dbh = new Database ();
	
	$remote_ip = $_SERVER ['REMOTE_ADDR'];
	
	switch (( int ) $persistance) {
		
		case 1 :
			
			$sql = "DELETE FROM fb_job_entries_tmp WHERE job_id = ? AND remote_ip = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $remote_ip
			);
			$delete = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
			
			break;
			
		case 2 :
			
			if (! isset ( $_SESSION ['fb_entry_id'] )) {
				return;
			}
			
			// fb_entry_id based. We still delete for IP though, as we could still have a $_SESSION['fb_entry_id'] set.
			$sql = "DELETE FROM fb_job_entries_tmp WHERE job_id = ? AND remote_ip = ? OR fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $remote_ip,
					( string ) $_SESSION ['fb_entry_id']
			);
			$delete = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
			
			break;
			
		case 3 :
			
			if (! isset ( $_SESSION ['fb_entry_id_auto'] )) {
				return;
			}
			
			// fb_entry_id based. We still delete for IP though, as we could still have a $_SESSION['fb_entry_id'] set.
			$sql = "DELETE FROM fb_job_entries_tmp WHERE job_id = ? AND remote_ip = ? OR fb_entry_id = ?;";
			$params = array (
					( int ) $db_key,
					( string ) $remote_ip,
					( string ) $_SESSION ['fb_entry_id_auto']
			);
			$delete = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
			
			break;
		
	}

}

/**
 * Create a table format for datagrid items.
 *
 *
 * MODES:
 * 0: // No Table, No HTML.
 * 1: // Table Format, No HTML.
 * 2: // HTML, No Table.
 * 3: // DIV based Table.
 * 4: // Outlook/PDF, TABLE based Format.
 *
 *
 * @param array $gridname        	
 * @param SimpleXMLElement $data        	
 * @param int $mode        	
 */
function create_datagrid_email_table($grid_data_raw, $data, $label) {
	
	// first we create our data structure holders
	$grid_data = array ();
	$grid_data_formatted = '';
	
	// now we iterate over the gridnames, as we can have more than one.
	foreach ( $grid_data_raw as $grid ) {
		
		// this comes in as pipe delimited, the name and the label display mode.
		$grid_data_exploded = explode ( '|', $grid );
		
		$grid_name = $grid_data_exploded [0];
		$grid_labelmode = $grid_data_exploded [1];
		
		// setup our data structures
		$row = array ();
		
		// iterate over the dataset, pluck out matching items
		foreach ( $data as $key => $value ) {
			
			// Build 827 - We now skip any rows with _is_DataGrid Specified.
			if(substr_count($key, '_is_DataGrid') != 0)
				continue;
			
			// match the name, will be the $grid plus an underscore (grid_0_0, grid_0_1, grid_1_0, grid_1_1, etc)
			if (substr_count ( $key, $grid_name . '_' ) != 0) {
				
				$index = explode ( '_', str_replace ( $grid_name, '', $key ) );
				$row_index = $index [1];
				$column_index = $index [2];
				
				if ($row_index != '' && $column_index != '') {
					
					// Build 890 - Convert Arrays Into String Values.
					if(is_array($value)) {
						$value = implode(',', $value);
					}
					
					$row [$row_index] [$column_index] = $value;
					
				}
			}
		}
		
		$grid_data ["{$grid_name}"] ['data'] = $row;
		$grid_data ["{$grid_name}"] ['label_mode'] = $grid_labelmode;
	}
	
	// now that we have a data structure, process it used the proper formatting, one for each mode, we select which one later.
	
	// label_mode(s)
	// 0 = No Label
	// 1 = Row Only
	// 2 = Column Only
	// 3 = Row & Column (-)
	// 4 = Row & Column (|)
	
	// 0: No Table, No HTML.
	$mode_0 = '';
	
	foreach ( $grid_data as $g_name => $g_value ) {
		
		// create top row for column labels, if needed.
		if ($g_value ['label_mode'] == 2 || $g_value ['label_mode'] == 3 || $g_value ['label_mode'] == 4) {
			
			// we need an extra column in this upper right slot to accomodate row labels later on.
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
				$mode_0 .= "\t";
			}
			
			// now create each column header
			foreach ( $g_value ['data'] [0] as $index => $c ) {
				
				$label_raw = $label ["{$grid_name}_0_{$index}"];
				
				// only append the t if we're on the second iteration.
				if ($index != 0)
					$mode_0 .= "\t";
					
					// we now have a label that can be formatted in a few different ways...
				switch ($g_value ['label_mode']) {
					case 2 : // the column name only
						$mode_0 .= trim ( $label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $label_raw );
						$mode_0 .= trim ( $t [1] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $label_raw );
						$mode_0 .= trim ( $t [1] );
						break;
				}
			}
			
			// done creating the header, create a new line break.
			$mode_0 .= "\r\n";
		}
		
		// now start creating our data rows
		foreach ( $g_value ['data'] as $index => $r ) {
			
			// first check is if we print row labels
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
				
				$row_label_raw = $label ["{$grid_name}_{$index}_0"]; // note we "hard code" to this first item, as we don't need others.
				
				switch ($g_value ['label_mode']) {
					case 2 : // the row name only
						$mode_0 .= trim ( $row_label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $row_label_raw );
						$mode_0 .= trim ( $t [0] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $row_label_raw );
						$mode_0 .= trim ( $t [0] );
						break;
				}
				
				// prep for row data
				$mode_0 .= "\t";
			}
			
			// print columns
			foreach ( $r as $idx => $row ) {
				
				if ($idx != 0)
					$mode_0 .= "\t";
				
				$mode_0 .= $row;
			}
			
			// new row
			$mode_0 .= "\r\n";
		}
	}
	
	$grid_data_formatted ["{$grid_name}"] [0] = $mode_0;
	
	// 1: Table Format, No HTML.
	
	// 2: HTML, No Table.
	
	// 3: DIV based Table.
	$mode_3 = '<div class="datagrid-wrapper">';
	
	foreach ( $grid_data as $g_name => $g_value ) {
		
		// create top row for column labels, if needed.
		if ($g_value ['label_mode'] == 2 || $g_value ['label_mode'] == 3 || $g_value ['label_mode'] == 4) {
			
			// we need an extra column in this upper right slot to accomodate row labels later on.
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
				$mode_3 .= "<div class=\"datagrid-label-row\" style=\"float:left;\">&nbsp;</div>";
			}
			
			// now create each column header
			foreach ( $g_value ['data'] [0] as $index => $c ) {
				
				$label_raw = $label ["{$grid_name}_0_{$index}"];
				
				$mode_3 .= "<div class=\"datagrid-label-header\" style=\"float:left;\">";
				
				// we now have a label that can be formatted in a few different ways...
				switch ($g_value ['label_mode']) {
					case 2 : // the column name only
						$mode_3 .= trim ( $label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $label_raw );
						$mode_3 .= trim ( $t [1] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $label_raw );
						$mode_3 .= trim ( $t [1] );
						break;
				}
				
				$mode_3 .= "</div>";
			}
			
			// done creating the header, create a new line break.
			$mode_3 .= "<div class=\"datagrid-clear\" style=\"clear:both; height:2px;\"></div>";
		}
		
		// now start creating our data rows
		foreach ( $g_value ['data'] as $index => $r ) {
			
			// first check is if we print row labels
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
				
				$row_label_raw = $label ["{$grid_name}_{$index}_0"]; // note we "hard code" to this first item, as we don't need others.
				
				$mode_3 .= "<div class=\"datagrid-label-row\" style=\"float:left;\">";
				
				switch ($g_value ['label_mode']) {
					case 2 : // the row name only
						$mode_3 .= trim ( $row_label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $row_label_raw );
						$mode_3 .= trim ( $t [0] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $row_label_raw );
						$mode_3 .= trim ( $t [0] );
						break;
				}
				
				// close column
				$mode_3 .= "</div>";
			}
			
			// print columns
			foreach ( $r as $idx => $row ) {
				
				$mode_3 .= "<div class=\"datagrid-row-item\" style=\"float:left;\">";
				
				$mode_3 .= $row;
				
				$mode_3 .= "</div>";
			}
			
			// new row
			$mode_3 .= "<div class=\"datagrid-clear\" style=\"clear:both; height:2px;\"></div>";
		}
		
		$mode_3 .= "</div>";
	}
	
	$grid_data_formatted ["{$grid_name}"] [3] = $mode_3;
	
	// 4: Outlook, TABLE based Format.
	$mode_4 = '<table class="datagrid-wrapper" width="100%" border="0" cellpadding="3">';
	
	foreach ( $grid_data as $g_name => $g_value ) {
		
		// create top row for column labels, if needed.
		if ($g_value ['label_mode'] == 2 || $g_value ['label_mode'] == 3 || $g_value ['label_mode'] == 4) {
			
			$mode_4 .= "<tr>";
			
			// we need an extra column in this upper right slot to accomodate row labels later on.
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
				$mode_4 .= "<td class=\"datagrid-label-row\">&nbsp;</td>";
			}
			
			// now create each column header
			foreach ( $g_value ['data'] [0] as $index => $c ) {
				
				$label_raw = $label ["{$grid_name}_0_{$index}"];
				
				$mode_4 .= "<td class=\"datagrid-label-header\">";
				
				// we now have a label that can be formatted in a few different ways...
				switch ($g_value ['label_mode']) {
					case 2 : // the column name only
						$mode_4 .= trim ( $label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $label_raw );
						$mode_4 .= trim ( $t [1] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $label_raw );
						$mode_4 .= trim ( $t [1] );
						break;
				}
				
				$mode_4 .= "</td>";
			}
		}
		
		// now start creating our data rows
		foreach ( $g_value ['data'] as $index => $r ) {
			
			$mode_4 .= "<tr>";
			
			// first check is if we print row labels
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
				
				$row_label_raw = $label ["{$grid_name}_{$index}_0"]; // note we "hard code" to this first item, as we don't need others.
				
				$mode_4 .= "<td class=\"datagrid-label-row\">";
				
				switch ($g_value ['label_mode']) {
					case 2 : // the row name only
						$mode_4 .= trim ( $row_label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $row_label_raw );
						$mode_4 .= trim ( $t [0] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $row_label_raw );
						$mode_4 .= trim ( $t [0] );
						break;
				}
				
				// close column
				$mode_4 .= "</td>";
			}
			
			// print columns
			foreach ( $r as $idx => $row ) {
				
				$mode_4 .= "<td class=\"datagrid-row-item\">";
				
				$mode_4 .= $row;
				
				$mode_4 .= "</td>";
			}
			
			// new row
			$mode_4 .= "</tr>";
		}
		
		$mode_4 .= "</table>";
	}
	
	$grid_data_formatted ["{$grid_name}"] [4] = $mode_4;
	
	
	
	// 5: PDF, TABLE based Format.
	$mode_5 = '<table class="datagrid-pdf-wrapper" border="0" cellpadding="3">';
	
	foreach ( $grid_data as $g_name => $g_value ) {
	
		// create top row for column labels, if needed.
		if ($g_value ['label_mode'] == 2 || $g_value ['label_mode'] == 3 || $g_value ['label_mode'] == 4) {
				
			$mode_5 .= "<tr>";
				
			// we need an extra column in this upper right slot to accomodate row labels later on.
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
				$mode_5 .= "<td class=\"datagrid-pdf-label-row\">&nbsp;</td>";
			}
				
			// now create each column header
			foreach ( $g_value ['data'] [0] as $index => $c ) {
	
				$label_raw = $label ["{$grid_name}_0_{$index}"];
	
				$mode_5 .= "<td class=\"datagrid-pdf-label-header\">";
	
				// we now have a label that can be formatted in a few different ways...
				switch ($g_value ['label_mode']) {
					case 2 : // the column name only
						$mode_5 .= trim ( $label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $label_raw );
						$mode_5 .= trim ( $t [1] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $label_raw );
						$mode_5 .= trim ( $t [1] );
						break;
				}
	
				$mode_5 .= "</td>";
			}
		}
	
		// now start creating our data rows
		foreach ( $g_value ['data'] as $index => $r ) {
				
			$mode_5 .= "<tr>";
				
			// first check is if we print row labels
			if ($g_value ['label_mode'] != 0 && $g_value ['label_mode'] != 2) {
	
				$row_label_raw = $label ["{$grid_name}_{$index}_0"]; // note we "hard code" to this first item, as we don't need others.
	
				$mode_5 .= "<td class=\"datagrid-pdf-label-row\">";
	
				switch ($g_value ['label_mode']) {
					case 2 : // the row name only
						$mode_5 .= trim ( $row_label_raw );
						break;
					case 3 : // the two split via (-)
						$t = explode ( '-', $row_label_raw );
						$mode_5 .= trim ( $t [0] );
						break;
					case 4 : // the two split via (|)
						$t = explode ( '|', $row_label_raw );
						$mode_5 .= trim ( $t [0] );
						break;
				}
	
				// close column
				$mode_5 .= "</td>";
			}
				
			// print columns
			foreach ( $r as $idx => $row ) {
	
				$mode_5 .= "<td class=\"datagrid-pdf-row-item\">";
	
				$mode_5 .= $row;
	
				$mode_5 .= "</td>";
			}
				
			// new row
			$mode_5 .= "</tr>";
		}
	
		$mode_5 .= "</table>";
	}
	
	$grid_data_formatted ["{$grid_name}"] [5] = $mode_5;
	
	
	
	return $grid_data_formatted;
}

/**
 * Encode String to JSON
 *
 * @param mixed $obj        	
 * @return string
 */
function php_json_encode($obj) {
	if (is_array ( $obj )) {
		if (array_is_associative ( $obj )) {
			$arr_out = array ();
			foreach ( $obj as $key => $val ) {
				$arr_out [] = '"' . $key . '":' . php_json_encode ( $val );
			}
			return '{' . implode ( ',', $arr_out ) . '}';
		} else {
			$arr_out = array ();
			$ct = count ( $obj );
			for($j = 0; $j < $ct; $j ++) {
				$arr_out [] = php_json_encode ( $obj [$j] );
			}
			return '[' . implode ( ',', $arr_out ) . ']';
		}
	} else {
		if (is_int ( $obj )) {
			return $obj;
		} else {
			$str_out = stripslashes ( trim ( $obj ) );
			$str_out = str_replace ( array (
					'"',
					'',
					'/' 
			), array (
					'\"',
					'\\',
					'/' 
			), $str_out );
			return '"' . $str_out . '"';
		}
	}
}

/**
 * Utility call for php_json_encode
 *
 * @param array $array        	
 * @return bool
 */
function array_is_associative($array) {
	$count = count ( $array );
	for($i = 0; $i < $count; $i ++) {
		if (! array_key_exists ( $i, $array )) {
			return true;
		}
	}
	return false;
}

/**
 * Replaces E-Comm Tokens At Run Time
 *
 * Build 693 - Dynamic prices may already have $ and the like, so we strip anything out here just in case.
 *
 * @since 651
 * @param string $code        	
 * @return string
 */
function replaceEcommPriceTokens($code) {
	
	// FORM FIELD
	$matches = array ();
	preg_match_all ( '/(^|.|\r|\n)?(F\{(.*?)\})/', $code, $matches );
	
	if (isset ( $matches [3] )) {
		foreach ( $matches [3] as $idx => $m ) {
			$code = isset ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$m}"] ) ? $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ["{$m}"] : '';
			$code = preg_replace ( "/[^0-9\\.\\,]+/", "", $code );
		}
	}
	
	// SESSION FIELD
	$matches = array ();
	preg_match_all ( '/(^|.|\r|\n)?(S\{(.*?)\})/', $code, $matches );
	
	if (isset ( $matches [3] )) {
		foreach ( $matches [3] as $idx => $m ) {
			$code = isset ( $_SESSION ["{$m}"] ) ? $_SESSION ["{$m}"] : '';
			$code = preg_replace ( "/[^0-9\\.\\,]+/", "", $code );
		}
	}
	
	return $code;
}

/**
 * Create Random Password
 * 
 * @return string
 */
function randomPassword() {
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 55; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function curPageURL() {
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/**
 * Remove Common Path References From Input.
 *
 * Used In:
 * Flat File Creation - 'Remove Path Data From Dynamic Variables' Checkbox.
 *
 * @param string $in        	
 *
 * @since 700
 */
function removePathItems($in) {
	$in = str_replace ( '../', '', $in );
	$in = str_replace ( './', '', $in );
	
	return $in;
}

/**
 * Cleans a folder of all contents.
 *
 * @param string $dirname        	
 * @param string $jobname        	
 * @return bool
 */
function clean_output_location($dirname) {
	$dir_handle = 0;
	if (is_dir ( $dirname ))
		$dir_handle = opendir ( $dirname );
	if (! $dir_handle)
		return false;
	
	while ( $file = readdir ( $dir_handle ) ) {
		if ($file != "." && $file != "..") {
			if (! is_dir ( $dirname . '/' . $file )) {
				unlink ( $dirname . '/' . $file );
			} else {
				clean_output_location ( $dirname . '/' . $file );
			}
		}
	}
	closedir ( $dir_handle );
	
	if (@rmdir ( $dirname )) {
		return true;
	} else {
		return false;
	}
}

/**
 * Clean Data Fields For Query Module Export Operations.
 *
 * @since 781
 * @param type $str        	
 */
function cleanDataForQueryExport(&$str) {
	if ($str == 't')
		$str = 'TRUE';
	if ($str == 'f')
		$str = 'FALSE';
	if (preg_match ( "/^0/", $str ) || preg_match ( "/^\+?\d{8,}$/", $str ) || preg_match ( "/^\d{4}.\d{1,2}.\d{1,2}/", $str )) {
		$str = "'$str";
	}
	if (strstr ( $str, '"' ))
		$str = '"' . str_replace ( '"', '""', $str ) . '"';
}

/**
 * Create RackForms Session.
 *
 * Utility Function Clean up this session's inline data -- remove all field keys, then the qs and qs-label, followed by entry_key and page data
 *
 * @since 785
 */
function clear_session() {
	
	// Remove Any Stripe Transaction Data
	if (isset ( $_SESSION ['stripe'] ))
		unset ( $_SESSION ['stripe'] );
	
	// Build 826 - Better Error Handling
	if(!isset($_SESSION ['qs']))
		return;
	
	if(!isset($_SESSION['entry_key_practiceexam']))
		return;
	
	if(!isset($_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"]))
		return;
		
		// Remove all singleton session data fields (selected items etc)
	$named_sesison_vars = array_keys ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] );
	foreach ( $named_sesison_vars as $var ) {
		// fields
		unset ( $_SESSION ["{\$var}"] );
		// isset
		unset ( $_SESSION ["{\$var}_is"] );
		// _processed
		unset ( $_SESSION ["{\$var}_processed"] );
	}
	
	if (isset ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] )) {
		foreach ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] as $key => $value ) {
			unset ( $_SESSION [$key] );
		}
	}
	
	if (isset ( $_SESSION ['fb_ecomm'] )) {
		unset ( $_SESSION ['fb_ecomm'] );
	}
	
	if (isset ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ['signatures'] )) {
		unset ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] ['signatures'] );
	}
	
	unset ( $_SESSION ['pages'] );
	unset ( $_SESSION ['pages-passed'] ["{$_SESSION['entry_key_practiceexam']}"] );
	
	clean_output_location ( 'tmp' );
	clean_output_location ( 'lib/jquery-upload/server/php/files/' . $_SESSION['entry_key_practiceexam'] ); // Build 860
	
	if (isset ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] )) {
		unset ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] );
	}
	if (isset ( $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] )) {
		unset ( $_SESSION ['qs-label'] ["{$_SESSION['entry_key_practiceexam']}"] );
	}
	if (isset ( $_SESSION['entry_key_practiceexam'] )) {
		unset ( $_SESSION['entry_key_practiceexam'] );
	}
	
	if (isset ( $_SESSION ['fielded_data_array'] )) {
		unset ( $_SESSION ['fielded_data_array'] );
	}
	
	// Unset main indentifiers, which is trnasformed at build time to specific element for this form.
	unset ( $_SESSION['entry_key_practiceexam'] );
	
	// Build 836
	if(isset($_SESSION['fb_entry_id_auto']))
		unset($_SESSION['fb_entry_id_auto']);
}


/**
 * Analyitics and Tracking
 *
 * @since 805
 */
 
 function init_stats($db_key, $pagetitle, $type){
 
    // connection logic    

    if(file_exists("{$_SESSION['MAX_PATH']}config.php")){
        
        if(file_exists("{$_SESSION['MAX_PATH']}Database.php")){
        
            $db_catalog = '';
            @include 'Database.php'; // variable scope of included page vars is local now.
            
            $dbh = new Database ();
            
            $sql = "INSERT INTO fb_analytics(job_id, ts, page_title, page_type, entry_key, remote_ip) VALUES (?,?,?,?,?,?)";
            
		    $params = array (
				    ( int ) $db_key,
                    date('Y-m-d  H:i:s', time()),
                    ( string ) $pagetitle,
                    ( string ) $type,
                    ( string ) $_SESSION['entry_key_practiceexam'],
                    ( string ) $_SERVER['REMOTE_ADDR']
		    );
            
		    $res = $dbh->pdo_procedure_params ( $debug, $sql, $db_host, $db_type, $mysql_socket, $mysql_port, $dbdsn, $db_user, $db_pass, $db_catalog, $params, $return_true = 1 );
        
        }
        
        
    }
 
 }
 
 
 // Form Helper - Used for 'Select Item Text Based List'.
 // Build 853 - Support for $select_text_defaultvalue
 // Build 853 - Needed to wrap checks in html_entity_decode for other languages.
 // @since 836
 function create_select_item($val_name, $val_value, $name, $basicdefaultselectvalue, $select_text_defaultvalue){
 	
 	$dynamic_selected = $basicdefaultselectvalue != "" ? $basicdefaultselectvalue : $select_text_defaultvalue;
 	
 	$html = "\n\t<option value=\"{$val_value}\"";

	if(is_array($_SESSION["{$name}"])){
		if(in_array(html_entity_decode("{$val_value}"), $_SESSION["{$name}"])){
			$html .= ' selected="selected" ';
		}
	} else {
	
		$val = $dynamic_selected;
		
		$dynamic_array = explode('|', $val);
		
		if(is_array($dynamic_array) && count($dynamic_array) > 1){
			
			if($_SESSION["{$name}"] == html_entity_decode($val_value, ENT_QUOTES) || in_array(html_entity_decode($val_value, ENT_QUOTES), $dynamic_array)) {
				$html .= ' selected="selected" ';
			}
			
		} else {
		
			if($_SESSION["{$name}"] == html_entity_decode($val_value, ENT_QUOTES) || html_entity_decode($val_value, ENT_QUOTES) == $dynamic_selected) {
				$html .= ' selected="selected" ';
			}
			
		}
		
	}
	
	$html .= ">{$val_name}</option>";

 	return $html;
	
 }
	
// Form Helper Function - Process array variables for display.
// Build 856 - Added existing function check.
// @since 838
if (! function_exists ( "process_array_variables" )) {
	function process_array_variables($vars, $separator) {
		
		// check for and replace array based variables if not already done
		foreach ( $vars as $var ) {
			if (isset ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] [$var] )) {
				if (is_array ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] [$var] )) {
					$field_items = '';
					foreach ( $_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] [$var] as $key => $v ) {
						if ($key != 0) {
							$field_items .= $separator;
						}
						$field_items .= $v;
					}
					$_SESSION ['qs'] ["{$_SESSION['entry_key_practiceexam']}"] [$var] = $field_items;
				}
			}
		}
	}
}

 
 class rackforms_curl {
	protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
	protected $_url;
	protected $_followlocation;
	protected $_timeout;
	protected $_maxRedirects;
	protected $_cookieFileLocation = './cookie.txt';
	protected $_post;
	protected $_postFields;
	protected $_referer = "http://www.google.com";
	protected $_session;
	protected $_webpage;
	protected $_includeHeader;
	protected $_noBody;
	protected $_status;
	protected $_binaryTransfer;
	public $authentication = 0;
	public $auth_name = '';
	public $auth_pass = '';
	public function useAuth($use) {
		$this->authentication = 0;
		if ($use == true)
			$this->authentication = 1;
	}
	public function setName($name) {
		$this->auth_name = $name;
	}
	public function setPass($pass) {
		$this->auth_pass = $pass;
	}
	public function __construct($url, $followlocation = true, $timeOut = 30, $maxRedirecs = 4, $binaryTransfer = false, $includeHeader = false, $noBody = false) {
		$this->_url = $url;
		$this->_followlocation = $followlocation;
		$this->_timeout = $timeOut;
		$this->_maxRedirects = $maxRedirecs;
		$this->_noBody = $noBody;
		$this->_includeHeader = $includeHeader;
		$this->_binaryTransfer = $binaryTransfer;
		
		$this->_cookieFileLocation = dirname ( __FILE__ ) . '/cookie.txt';
	}
	public function setReferer($referer) {
		$this->_referer = $referer;
	}
	public function setCookiFileLocation($path) {
		$this->_cookieFileLocation = $path;
	}
	public function setPost($postFields) {
		$this->_post = true;
		$this->_postFields = $postFields;
	}
	public function setUserAgent($userAgent) {
		$this->_useragent = $userAgent;
	}
	public function createCurl($url = 'nul') {
		if ($url != 'nul') {
			$this->_url = $url;
		}
		
		$s = curl_init ();
		
		curl_setopt ( $s, CURLOPT_URL, $this->_url );
		curl_setopt ( $s, CURLOPT_HTTPHEADER, array (
				'Expect:' 
		) );
		curl_setopt ( $s, CURLOPT_TIMEOUT, $this->_timeout );
		curl_setopt ( $s, CURLOPT_MAXREDIRS, $this->_maxRedirects );
		curl_setopt ( $s, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $s, CURLOPT_FOLLOWLOCATION, $this->_followlocation );
		curl_setopt ( $s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation );
		curl_setopt ( $s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation );
		
		if ($this->authentication == 1) {
			curl_setopt ( $s, CURLOPT_USERPWD, $this->auth_name . ':' . $this->auth_pass );
		}
		if ($this->_post) {
			curl_setopt ( $s, CURLOPT_POST, true );
			curl_setopt ( $s, CURLOPT_POSTFIELDS, $this->_postFields );
		}
		
		if ($this->_includeHeader) {
			curl_setopt ( $s, CURLOPT_HEADER, true );
		}
		
		if ($this->_noBody) {
			curl_setopt ( $s, CURLOPT_NOBODY, true );
		}
		/*
		 * if($this->_binary) { curl_setopt($s,CURLOPT_BINARYTRANSFER,true); }
		 */
		curl_setopt ( $s, CURLOPT_USERAGENT, $this->_useragent );
		curl_setopt ( $s, CURLOPT_REFERER, $this->_referer );
		
		$this->_webpage = curl_exec ( $s );
		$this->_status = curl_getinfo ( $s, CURLINFO_HTTP_CODE );
		curl_close ( $s );
	}
	public function getHttpStatus() {
		return $this->_status;
	}
	public function __tostring() {
		return $this->_webpage;
	}
}
 

/**
 * Array Functions
 */

/**
 * Convert Array To UTF-8 For json_encode.
 * @since 890
 * @param unknown $array
 * @return unknown
 */
function utf8_converter($array) {
	array_walk_recursive ( $array, function (&$item, $key) {
		if (! mb_detect_encoding ( $item, 'utf-8', true )) {
			$item = utf8_encode ( $item );
		}
	} );
	
	return $array;
}

/**
 * Convert Array To CSV.
 * @param unknown $array
 * @return string
 */
function array_2_csv($array) {
	$csv = array();
	foreach ($array as $item) {
		if(is_object($item))
			continue;
		if (is_array($item)) {
			$csv[] = array_2_csv($item);
		} else {
			if(trim($item) == "")
				continue;
			$csv[] = $item;
		}
	}
	return implode(',', $csv);
}

function formdata_2_csv($array) {
	$keys = array();
	$values = array();
	foreach ($array as $key => $item) {
		if(is_object($item))
			continue;
		if (is_array($item)) {
			$csv[] = array_2_csv($item);
		} else {
			if(substr_count($key, 'geo_') != 0)
				continue;
			
			// Escape Contents.
			if(substr_count($key, '"') != 0)
				$key = '"' . $key . '"';
						
			if(substr_count($key, ',') != 0)
				$key = '"' . $key . '"';
			
			if(substr_count($item, '"') != 0)
				$item = '"' . $item . '"';
			
			if(substr_count($item, ',') != 0)
				$item = '"' . $item . '"';
			
			$keys[] = $key;
			$values[] = $item;
		}
	}
	//print_r($keys); die;
	//print_r($values); die;
	return array( implode(',', $keys), implode(',', $values) ) ;
}

?>
