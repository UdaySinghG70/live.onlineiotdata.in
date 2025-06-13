<?php
require_once 'FtpConfig.php';

class FtpUploader {
    private $lastError = '';
    private $isConnected = false;

    public function uploadFile($localFile, $schedule) {
        if (!file_exists($localFile)) {
            echo "Cannot upload: Local file not found\n";
            return false;
        }

        try {
            echo "\nPreparing to upload to FTP server " . FtpConfig::$ftpHost . "...\n";
            
            // Get filename and remote path
            $filename = basename($localFile);
            $remoteDir = FtpConfig::$ftpPath . '/' . $schedule;
            $remotePath = $remoteDir . '/' . $filename;
            
            // Get file size for progress tracking
            $filesize = filesize($localFile);
            echo "File size: " . round($filesize / 1024 / 1024, 2) . " MB\n";
            
            // Initialize cURL
            $ch = curl_init();
            
            // Set up the connection
            curl_setopt($ch, CURLOPT_URL, "ftp://" . FtpConfig::$ftpHost . $remotePath);
            curl_setopt($ch, CURLOPT_USERPWD, FtpConfig::$ftpUser . ":" . FtpConfig::$ftpPass);
            curl_setopt($ch, CURLOPT_UPLOAD, 1);
            curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_FTP);
            curl_setopt($ch, CURLOPT_FTP_CREATE_MISSING_DIRS, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 5 minute timeout
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            
            // Progress tracking function
            $progress = 0;
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $downloadSize, $downloaded, $uploadSize, $uploaded) use (&$progress, $filesize) {
                if ($uploadSize > 0) {
                    $newProgress = round(($uploaded / $uploadSize) * 100);
                    if ($newProgress > $progress + 4) { // Show progress every 5%
                        $progress = $newProgress;
                        echo "Progress: $progress%\n";
                    }
                }
            });
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
            
            // Open file for reading
            $fp = fopen($localFile, 'rb');
            curl_setopt($ch, CURLOPT_INFILE, $fp);
            curl_setopt($ch, CURLOPT_INFILESIZE, $filesize);
            
            echo "Uploading " . $filename . " to " . $remoteDir . "...\n";
            
            // Execute the request
            $result = curl_exec($ch);
            
            if ($result === false) {
                throw new Exception(curl_error($ch));
            }
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode >= 400) {
                throw new Exception("FTP server returned error code: " . $httpCode);
            }
            
            fclose($fp);
            curl_close($ch);
            
            echo "Upload completed successfully!\n";
            $this->log("Successfully uploaded: " . $filename . " to " . $remoteDir);
            return true;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            echo "Upload Error: " . $this->lastError . "\n";
            if (strpos($this->lastError, "timeout") !== false) {
                echo "Suggestion: Check your network connection and firewall settings\n";
            }
            if (strpos($this->lastError, "permission") !== false) {
                echo "Suggestion: Verify FTP username, password, and folder permissions\n";
            }
            $this->log("Upload Error: " . $this->lastError);
            
            // Clean up
            if (isset($fp) && is_resource($fp)) {
                fclose($fp);
            }
            if (isset($ch) && is_resource($ch)) {
                curl_close($ch);
            }
            return false;
        }
    }

    private function log($message) {
        $logFile = dirname(__FILE__) . '/../logs/ftp_upload.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";
        
        // Create logs directory if it doesn't exist
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    public function getLastError() {
        return $this->lastError;
    }
} 