<?php
class IniFileHelper{
	static $server = "103.212.120.23";
	static $database = "cloud";
	static $user = "root";
	static $password = "";
	
	// 	static $server = "103.212.120.23";
	//   	static $database = "onlineiot";
	//   	static $user = "root";
	//   	static $password = "vdFM63.Y7#c5cG";
	static public function ReadIniFile($file_name){
		if(file_exists($file_name)!=true){
			echo"File not found. ";//.$file_name;
			return null;
		}
		// Load the INI file
		$config = parse_ini_file($file_name,true);
		
		if ($config === false) {
			//die('Unable to read the config.ini file.');
			echo "Invalid Config File ";
			return null;
		}
		return $config;
				
	}
	static public function WriteBackupSchedule($schedule){
		// Validate schedule
		if($schedule != BackupSchedule::$daily && 
		   $schedule != BackupSchedule::$weekly && 
		   $schedule != BackupSchedule::$monthly) {
			$schedule = BackupSchedule::$daily; // Default to daily if invalid
		}

		$data = array(BackUpConfig::$DatabaseBackupSection => array(
				BackUpConfig::$DatabaseBackupScheduleKey => $schedule
			)
		);
		
		// Convert the data array to an INI string
		$iniString = '';
		foreach ($data as $section => $values) {
			$iniString .= "[$section]\n";
			foreach ($values as $key => $value) {
				$iniString .= "$key = \"$value\"\n";
			}
			$iniString .= "\n";
		}
		
		// Write the INI string to a file
		$filename = BackUpConfig::$iniFile;
		if (file_put_contents($filename, $iniString) !== false) {
			//echo "INI file has been written successfully to $filename";
		} else {
			echo "Unable to write INI file";
		}
	}
}
