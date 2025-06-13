<?php
class FtpConfig {
    // FTP Server Settings
    static $ftpHost = "ftp.onlineiotdata.com"; // FTP server hostname
    static $ftpUser = "testuser@onlineiotdata.com"; // FTP username
    static $ftpPass = "Onlineiot@1234"; // FTP password
    static $ftpPort = 21; // Default FTP port
    static $ftpPath = "/live.onlineiotdata.in/backups"; // Remote directory to store backups
    
    // Secure the configuration
    private function __construct() {} // Prevent instantiation
} 