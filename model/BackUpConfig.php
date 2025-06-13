<?php
class BackUpConfig{
	static $iniFile = "backup.ini";
	static $DatabaseBackupSection = "Backup";
	static $DatabaseBackupScheduleKey = "backup_schedule";
	static $DailyScheduleKey = "daily_schedule";
	static $WeeklyScheduleKey = "weekly_schedule";
	static $MonthlyScheduleKey = "monthly_schedule";
}

class BackupSchedule{
	static $daily = "daily";
	static $weekly = "weekly";
	static $monthly = "monthly";
}