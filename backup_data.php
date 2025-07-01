<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Support CLI arguments as key=value pairs
if (php_sapi_name() === 'cli') {
    global $argv;
    foreach ($argv as $arg) {
        if (strpos($arg, '=') !== false) {
            list($key, $value) = explode('=', $arg, 2);
            $_REQUEST[$key] = $value;
        }
    }
}

// Function to clean old daily backups
function cleanOldDailyBackups($daysToKeep = 3) {
	$dailyBackupDir = "backup/daily";
	if (!file_exists($dailyBackupDir)) {
		return;
	}

	echo "\nChecking for old daily backups to clean...\n";
	$files = glob($dailyBackupDir . "/daily_*.sql");
	$now = time();
	$deletedFiles = array();

	foreach ($files as $file) {
		// Extract date from filename (format: daily_dd-mm-yyyy.sql or daily_dd-mm-yyyy_n.sql)
		if (preg_match('/daily_(\d{2}-\d{2}-\d{4})(?:_\d+)?\.sql/', basename($file), $matches)) {
			$backupDate = DateTime::createFromFormat('d-m-Y', $matches[1]);
			if ($backupDate) {
				$backupTimestamp = $backupDate->getTimestamp();
				$daysDiff = floor(($now - $backupTimestamp) / (60 * 60 * 24));
				
				if ($daysDiff > $daysToKeep) {
					if (unlink($file)) {
						$deletedFiles[] = basename($file);
						echo "Deleted old backup: " . basename($file) . " (${daysDiff} days old)\n";
					} else {
						echo "Failed to delete old backup: " . basename($file) . "\n";
					}
				}
			}
		}
	}

	// Delete corresponding database records
	if (!empty($deletedFiles)) {
		include_once 'model/admindao.php';
		$adao = new AdminDao();
		foreach ($deletedFiles as $file) {
			$adao->deleteBackupsByPattern($file);
		}
	}
}

// Function to clean old weekly backups
function cleanOldWeeklyBackups($weeksToKeep = 5) {
    $weeklyBackupDir = "backup/weekly";
    if (!file_exists($weeklyBackupDir)) {
        return;
    }

    echo "\nChecking for old weekly backups to clean...\n";
    $files = glob($weeklyBackupDir . "/weekly_*.sql");
    $now = time();
    $deletedFiles = array();

    foreach ($files as $file) {
        // Get file creation time as backup date
        $backupTimestamp = filectime($file);
        if ($backupTimestamp) {
            $weeksDiff = floor(($now - $backupTimestamp) / (60 * 60 * 24 * 7));
            
            if ($weeksDiff > $weeksToKeep) {
                if (unlink($file)) {
                    $deletedFiles[] = basename($file);
                    echo "Deleted old weekly backup: " . basename($file) . " (${weeksDiff} weeks old)\n";
                } else {
                    echo "Failed to delete old weekly backup: " . basename($file) . "\n";
                }
            }
        }
    }

    // Delete corresponding database records
    if (!empty($deletedFiles)) {
        include_once 'model/admindao.php';
        $adao = new AdminDao();
        foreach ($deletedFiles as $file) {
            $adao->deleteBackupsByPattern($file);
        }
    }
}

// Function to clean old monthly backups
function cleanOldMonthlyBackups() {
    $monthlyBackupDir = "backup/monthly";
    if (!file_exists($monthlyBackupDir)) {
        return;
    }

    echo "\nChecking for old monthly backups to clean...\n";
    $files = glob($monthlyBackupDir . "/monthly_*.sql");
    $currentMonth = date('n'); // 1-12
    $currentYear = date('Y');
    $deletedFiles = array();

    foreach ($files as $file) {
        // Extract month from filename (format: monthly_MonthName.sql or monthly_MonthName_n.sql)
        if (preg_match('/monthly_([A-Za-z]+)(?:_\d+)?\.sql/', basename($file), $matches)) {
            $backupMonth = date('n', strtotime($matches[1])); // Convert month name to number (1-12)
            
            // Get file creation time to determine the year
            $backupTimestamp = filectime($file);
            $backupYear = date('Y', $backupTimestamp);
            
            // Calculate how many months old the backup is
            $monthsOld = (($currentYear - $backupYear) * 12) + ($currentMonth - $backupMonth);
            
            // Delete if it's older than 2 months
            if ($monthsOld > 2) {
                if (unlink($file)) {
                    $deletedFiles[] = basename($file);
                    echo "Deleted old monthly backup: " . basename($file) . " (" . $matches[1] . " $backupYear, $monthsOld months old)\n";
                } else {
                    echo "Failed to delete old monthly backup: " . basename($file) . "\n";
                }
            } else {
                echo "Keeping backup: " . basename($file) . " (" . $matches[1] . " $backupYear, $monthsOld months old)\n";
            }
        }
    }

    // Delete corresponding database records
    if (!empty($deletedFiles)) {
        include_once 'model/admindao.php';
        $adao = new AdminDao();
        foreach ($deletedFiles as $file) {
            $adao->deleteBackupsByPattern($file);
        }
    }
}

// Function to synchronize backup folder with database
function syncBackupFolderWithDatabase() {
    echo "\nSynchronizing backup folders with database...\n";
    include_once 'model/admindao.php';
    $adao = new AdminDao();
    
    // Get all backup records from database
    $backups = $adao->getBackups(0, 1000); // Get up to 1000 records
    if (!$backups) {
        return;
    }

    // Get list of all physical backup files in both locations
    $backupDirs = ['backup/daily', 'backup/weekly', 'backup/monthly'];
    $backendBasePath = 'C:/xampp/mysql/data/' . QueryManager::$database . '/backup/';
    $allPhysicalFiles = [];
    $allBackendFiles = [];
    
    // Collect all physical files from database backup folders
    foreach ($backupDirs as $dir) {
        if (file_exists($dir)) {
            $files = glob($dir . "/*.sql");
            foreach ($files as $file) {
                $allPhysicalFiles[basename($file)] = $file;
            }
        }
    }

    // Collect all physical files from backend backup folders
    foreach ($backupDirs as $dir) {
        $backendDir = str_replace('backup/', $backendBasePath, $dir);
        if (file_exists($backendDir)) {
            $files = glob($backendDir . "/*.sql");
            foreach ($files as $file) {
                $allBackendFiles[basename($file)] = $file;
            }
        }
    }
    
    // Check each database record against filesystem
    foreach ($backups as $backup) {
        $filename = basename($backup->file_name);
        $filePath = $backup->file_name;
        
        // If file doesn't exist in database backup folder
        if (!file_exists($filePath)) {
            // Check if it exists in backend folder
            $backendPath = str_replace('backup/', $backendBasePath, $filePath);
            if (file_exists($backendPath)) {
                // Delete from backend folder
                if (unlink($backendPath)) {
                    echo "Deleted from backend folder: $filename\n";
                }
            }
            // Delete from database
            echo "Removing database record for missing file: $filename\n";
            $adao->deleteBackUp($backup->file_name);
        }
    }

    // Create backend backup folders if they don't exist
    foreach ($backupDirs as $dir) {
        $backendDir = str_replace('backup/', $backendBasePath, $dir);
        if (!file_exists($backendDir)) {
            mkdir($backendDir, 0777, true);
            echo "Created backend backup directory: $backendDir\n";
        }
    }

    // Sync backend files with database backup files
    foreach ($allPhysicalFiles as $filename => $filepath) {
        $backendPath = str_replace('backup/', $backendBasePath, $filepath);
        
        // If file exists in database backup but not in backend
        if (!file_exists($backendPath)) {
            // Copy file to backend
            $backendDir = dirname($backendPath);
            if (!file_exists($backendDir)) {
                mkdir($backendDir, 0777, true);
            }
            if (copy($filepath, $backendPath)) {
                echo "Copied to backend folder: $filename\n";
            }
        }
    }

    // Delete any files in backend that don't exist in database backup
    foreach ($allBackendFiles as $filename => $backendPath) {
        $databasePath = str_replace($backendBasePath, 'backup/', $backendPath);
        if (!file_exists($databasePath)) {
            if (unlink($backendPath)) {
                echo "Deleted orphaned file from backend: $filename\n";
            }
        }
    }
}

// Database configuration
include_once 'model/querymanager.php';

$servername = QueryManager::$server;
$username = QueryManager::$user;
$password = QueryManager::$password;
$database = QueryManager::$database;

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$table = mysqli_real_escape_string($conn, $_REQUEST['table']);
$schedule = isset($_REQUEST['schedule']) ? mysqli_real_escape_string($conn, $_REQUEST['schedule']) : 'daily';

// Sync backup folder with database before cleaning or creating new backups
syncBackupFolderWithDatabase();

// Clean old backups before creating new backup
if ($schedule === 'daily') {
	cleanOldDailyBackups(3);
} else if ($schedule === 'weekly') {
    cleanOldWeeklyBackups(5);
} else if ($schedule === 'monthly') {
    cleanOldMonthlyBackups();
}

// Get all tables in the database
$result = $conn->query("SHOW TABLES");
if (!$result) {
	die("Error getting tables: " . $conn->error);
}

$tables = array();
while ($row = $result->fetch_array()) {
	$tables[] = $row[0];
}

echo "Found tables: " . implode(", ", $tables) . "\n";

if($table=="all"){
	// Use all tables
	echo "Using all tables\n";
}else if($table=="received"){
	$tables = array("received");
	echo "Using received table only\n";
}else if($table=="logdata"){
	$tables = array("logdata");
	echo "Using logdata table only\n";
}else{
	echo "No process done";
	return;
}

include_once 'model/IniFileHelper.php';
include_once 'model/BackUpConfig.php';
include_once 'model/admindao.php';
$adao = new AdminDao();

// Create backup directory if it doesn't exist
if (!file_exists('backup')) {
	if (!mkdir('backup', 0777, true)) {
		die('Failed to create backup directory');
	}
	echo "Created main backup directory\n";
}

// Set backup directory based on schedule
$backupDir = "backup/$schedule";
if (!file_exists($backupDir)) {
	if (!mkdir($backupDir, 0777, true)) {
		die("Failed to create $schedule backup directory");
	}
	echo "Created $schedule backup directory\n";
}

// Create a file to write the SQL data
$date = date('d-m-Y');
$month = date('F');
$week = date('W');
$monthShort = date('M');

// Format the filename based on schedule type
switch($schedule) {
    case 'daily':
        $baseFilename = "daily_" . $date;
        break;
    case 'weekly':
        $weekNumber = str_pad(date('W'), 2, '0', STR_PAD_LEFT); // Get week number (01-53)
        $baseFilename = "weekly_week" . $weekNumber;
        break;
    case 'monthly':
        $baseFilename = "monthly_{$month}";
        break;
    default:
        $baseFilename = "backup_" . $date;
}

// Check if file exists and add counter if needed
$counter = 0;
do {
    if ($counter == 0) {
        $filename = "$backupDir/{$baseFilename}.sql";
    } else {
        $filename = "$backupDir/{$baseFilename}_{$counter}.sql";
    }
    $counter++;
} while (file_exists($filename));

// Make sure the directory exists
if (!file_exists($backupDir)) {
    if (!mkdir($backupDir, 0777, true)) {
        die("Failed to create directory: $backupDir");
    }
    echo "Created directory: $backupDir\n";
}

echo "Creating $schedule backup file: $filename\n";

$sqlFile = fopen($filename, "w");
if (!$sqlFile) {
    die("Failed to open file for writing: $filename");
}

// Add header comment to the SQL file
$headerComment = "-- Database Backup\n";
$headerComment .= "-- Server: " . $servername . "\n";
$headerComment .= "-- Database: " . $database . "\n";
$headerComment .= "-- Backup Type: " . ucfirst($schedule) . "\n";
$headerComment .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
$headerComment .= "-- Tables: " . implode(", ", $tables) . "\n\n";

fwrite($sqlFile, $headerComment);

$strReplace = "CREATE TABLE IF NOT EXISTS ";
$strSearch = "CREATE TABLE";

try {
	$pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "Connected to database successfully\n";
} catch (PDOException $e) {
	die("Database connection failed: " . $e->getMessage());
}

foreach($tables as $tableName){ 
	echo "Processing table: $tableName\n";
	
	// Query to get the CREATE TABLE statement
	$query = "SHOW CREATE TABLE $tableName";
	
	try {
		$statement = $pdo->query($query);
		$result = $statement->fetch(PDO::FETCH_ASSOC);
	
		// The CREATE TABLE statement is in the "Create Table" column of the result
		$createTableQuery = $result['Create Table'];
	
		$createTableQuery = str_replace($strSearch, $strReplace, $createTableQuery);
		fwrite($sqlFile, $createTableQuery . ";\n\n");
		
		// Now get the data
		$query = "SELECT * FROM $tableName";
		$result = $conn->query($query);
	
		if ($result) {
			while ($row = $result->fetch_assoc()) {
				$insertSQL = "INSERT INTO $tableName (";
				$values = "VALUES (";
	
				foreach ($row as $key => $value) {
					$insertSQL .= "`$key`, ";
					$values .= "'" . mysqli_real_escape_string($conn, $value) . "', ";
				}
	
				// Remove trailing commas and add closing parentheses
				$insertSQL = rtrim($insertSQL, ", ") . ")";
				$values = rtrim($values, ", ") . ");\n";
	
				// Write the INSERT statement to the file
				fwrite($sqlFile, $insertSQL . " " . $values);
			}
			echo "Exported data from table: $tableName\n";
		}
	} catch (PDOException $e) {
		echo "Error processing table $tableName: " . $e->getMessage() . "\n";
		continue;
	}
}

// Close the file and connections
fclose($sqlFile);
$pdo = null;
$conn->close();

echo "Backup completed successfully: $filename\n";

// Upload to FTP server
require_once 'model/FtpUploader.php';
$ftpUploader = new FtpUploader();
if ($ftpUploader->uploadFile($filename, $schedule)) {
    echo "Backup file uploaded to FTP server successfully\n";
} else {
    echo "FTP upload failed: " . $ftpUploader->getLastError() . "\n";
}

$adao->saveBackupFile($filename, date('Y-m-d'), date('Y-m-d'), $table, $schedule);
	
