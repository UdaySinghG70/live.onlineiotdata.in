-- Database Backup
-- Server: 103.212.120.23
-- Database: onlineiot
-- Backup Type: Daily
-- Date: 2025-06-11 11:17:35
-- Tables: admin, backup, data_received, devices, logdata, logparam, modem_params, received, recharge, user

CREATE TABLE IF NOT EXISTS  `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email_id` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO admin (`id`, `admin_name`, `password`, `email_id`) VALUES ('1', 'admin', '999', 'admin@example.com');
CREATE TABLE IF NOT EXISTS  `backup` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `backup_date` date NOT NULL,
  `schedule` varchar(20) NOT NULL,
  `tables` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS  `data_received` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(30) NOT NULL,
  `data_type` varchar(2) NOT NULL,
  `date_time_utc` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `data_status` int(1) NOT NULL,
  `max_data` double NOT NULL,
  `min_data` double NOT NULL,
  `instant_data` double NOT NULL,
  `imei_nr` varchar(100) NOT NULL,
  `recharge_status` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS  `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(30) NOT NULL,
  `imei_nr` varchar(50) NOT NULL,
  `user` varchar(50) NOT NULL,
  `latitude` varchar(40) NOT NULL,
  `longitude` varchar(40) NOT NULL,
  `place` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `country` varchar(40) NOT NULL,
  `address` varchar(200) NOT NULL,
  `date_time` datetime NOT NULL,
  `timezone_minute` int(11) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `project_id` varchar(20) NOT NULL,
  `location_id` varchar(20) NOT NULL,
  `project_name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO devices (`id`, `device_id`, `imei_nr`, `user`, `latitude`, `longitude`, `place`, `city`, `country`, `address`, `date_time`, `timezone_minute`, `mobile_no`, `project_id`, `location_id`, `project_name`) VALUES ('55', 'ESP', '124234123', 'Uday', '32', '32', 'Roorkee', 'Roorkee', 'India', '', '2025-06-03 00:00:00', '5', '34512352345', '3123123412', '34123514351345', 'ESPcse');
INSERT INTO devices (`id`, `device_id`, `imei_nr`, `user`, `latitude`, `longitude`, `place`, `city`, `country`, `address`, `date_time`, `timezone_minute`, `mobile_no`, `project_id`, `location_id`, `project_name`) VALUES ('59', 'TEST', '1654161654163163', 'Uday', '23', '42', 'Dehradun', 'Dehradun', 'India', '', '2025-06-07 00:00:00', '330', '9865998632', 'TEST123', 'DUN12456', 'CSEtest');
INSERT INTO devices (`id`, `device_id`, `imei_nr`, `user`, `latitude`, `longitude`, `place`, `city`, `country`, `address`, `date_time`, `timezone_minute`, `mobile_no`, `project_id`, `location_id`, `project_name`) VALUES ('60', 'ANKIT', '413252135', 'Uday', '32', '23', 'Roorkee', 'Roorkee', 'India', '', '2025-06-07 00:00:00', '3231', '653161346', '42134123', '32512345', '542315125');
CREATE TABLE IF NOT EXISTS  `logdata` (
  `date` date NOT NULL,
  `time` time NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `data` longtext NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1240 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:35:00', 'ESP', '1106251035,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.27', '768');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:35:00', 'TEST', '1106251035,76.84,97.74,43.63,57.26,34.9,37.05,8.41,71.95', '769');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:36:00', 'ESP', '1106251036,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.25', '770');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:36:00', 'TEST', '1106251036,79.26,93.45,86.64,60.58,83.13,14.74,47.07,41.63', '771');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:37:00', 'ESP', '1106251037,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.25', '772');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:37:00', 'TEST', '1106251037,36.1,50.88,62.39,33.46,63.83,70.62,11.45,85.95', '773');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:38:00', 'ESP', '1106251038,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.25', '774');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:38:00', 'TEST', '1106251038,91.27,52.16,35.54,97.06,34.49,40.0,33.73,9.75', '775');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:39:00', 'ESP', '1106251039,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.25', '776');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:39:00', 'TEST', '1106251039,44.5,33.06,82.2,16.88,38.63,55.45,55.72,45.4', '777');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:40:00', 'ESP', '1106251040,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.27', '778');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:40:00', 'TEST', '1106251040,84.9,49.94,95.63,50.49,41.16,52.38,76.22,34.25', '779');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:41:00', 'ESP', '1106251041,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.27', '780');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:41:00', 'TEST', '1106251041,80.92,52.61,13.78,70.73,22.72,71.19,18.08,60.91', '781');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:42:00', 'ESP', '1106251042,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.25', '782');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:42:00', 'TEST', '1106251042,77.15,56.57,11.69,65.36,76.22,54.55,72.07,32.39', '783');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:43:00', 'ESP', '1106251043,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.25', '784');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:43:00', 'TEST', '1106251043,52.55,11.45,66.43,46.83,95.77,6.4,23.15,85.23', '785');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:44:00', 'ESP', '1106251044,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.27', '786');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:44:00', 'TEST', '1106251044,1.03,61.9,23.69,11.45,15.41,10.17,1.02,64.22', '787');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:45:00', 'ESP', '1106251045,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.27', '788');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:45:00', 'TEST', '1106251045,9.03,98.71,33.42,64.12,9.12,19.03,68.61,43.77', '789');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:46:00', 'ESP', '1106251046,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.25', '790');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:46:00', 'TEST', '1106251046,4.73,33.72,98.12,26.8,40.01,17.36,2.52,25.6', '791');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:47:00', 'ESP', '1106251047,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.27', '792');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:47:00', 'TEST', '1106251047,15.93,57.28,72.02,17.67,55.88,93.6,63.29,90.95', '793');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:48:00', 'ESP', '1106251048,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.25', '794');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:48:00', 'TEST', '1106251048,59.81,15.81,61.63,70.16,27.61,83.98,22.22,63.51', '795');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:49:00', 'ESP', '1106251049,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.27', '796');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:49:00', 'TEST', '1106251049,95.37,39.97,4.79,2.79,32.72,39.06,90.63,44.7', '797');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:50:00', 'ESP', '1106251050,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.27', '798');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:50:00', 'TEST', '1106251050,38.46,83.35,49.17,57.4,93.79,55.23,34.6,8.54', '799');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:51:00', 'ESP', '1106251051,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.25', '800');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:51:00', 'TEST', '1106251051,36.92,54.66,39.43,19.72,24.87,64.39,37.23,84.08', '801');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:52:00', 'ESP', '1106251052,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '802');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:52:00', 'TEST', '1106251052,97.99,87.81,28.33,78.03,77.76,90.34,13.93,22.49', '803');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:53:00', 'ESP', '1106251053,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '804');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:53:00', 'TEST', '1106251053,26.25,74.47,6.46,68.44,70.98,19.41,72.58,44.58', '805');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:54:00', 'ESP', '1106251054,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '806');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:54:00', 'TEST', '1106251054,64.76,76.07,70.92,79.76,15.24,85.49,17.65,49.81', '807');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:55:00', 'ESP', '1106251055,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '808');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:55:00', 'TEST', '1106251055,34.72,1.46,46.23,43.67,50.63,29.14,19.14,50.87', '809');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:56:00', 'ESP', '1106251056,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '810');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:56:00', 'TEST', '1106251056,88.89,67.08,4.13,7.14,78.69,46.52,69.46,66.22', '811');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:57:00', 'ESP', '1106251057,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '812');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:57:00', 'TEST', '1106251057,21.34,21.11,29.21,28.81,54.62,78.8,17.0,91.72', '813');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:58:00', 'ESP', '1106251058,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '814');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:58:00', 'TEST', '1106251058,1.52,41.81,26.3,33.26,0.7,36.09,99.14,44.12', '815');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:59:00', 'ESP', '1106251059,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '816');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '10:59:00', 'TEST', '1106251059,65.27,35.3,99.56,5.52,50.8,16.45,12.8,66.27', '817');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:00:00', 'ESP', '1106251100,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '818');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:00:00', 'TEST', '1106251100,42.73,95.56,21.18,31.6,80.49,28.07,13.36,1.25', '819');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:01:00', 'ESP', '1106251101,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '820');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:01:00', 'TEST', '1106251101,62.09,11.72,82.6,13.02,99.74,5.75,9.3,46.37', '821');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:02:00', 'ESP', '1106251102,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '822');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:02:00', 'TEST', '1106251102,29.67,45.62,48.49,93.11,2.99,47.97,88.75,87.51', '823');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:03:00', 'ESP', '1106251103,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '824');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:03:00', 'TEST', '1106251103,43.44,60.06,64.93,37.27,35.37,70.9,8.55,74.0', '825');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:04:00', 'ESP', '1106251104,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '826');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:04:00', 'TEST', '1106251104,29.77,68.98,84.67,67.77,97.05,95.96,63.97,89.24', '827');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:05:00', 'ESP', '1106251105,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '828');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:05:00', 'TEST', '1106251105,24.26,11.47,19.58,94.44,56.75,5.85,90.51,94.18', '829');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:06:00', 'ESP', '1106251106,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '830');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:06:00', 'TEST', '1106251106,97.44,12.61,34.23,38.34,78.13,68.32,21.82,87.35', '831');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:07:00', 'ESP', '1106251107,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '832');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:07:00', 'TEST', '1106251107,10.41,14.4,84.17,94.98,94.5,60.96,63.95,1.33', '833');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:08:00', 'ESP', '1106251108,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.37', '834');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:08:00', 'TEST', '1106251108,45.99,0.38,91.26,4.12,97.29,67.59,80.69,71.76', '835');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:09:00', 'ESP', '1106251109,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '836');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:09:00', 'TEST', '1106251109,24.87,72.36,89.98,11.54,99.78,36.44,28.81,79.28', '837');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:10:00', 'ESP', '1106251110,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '838');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:10:00', 'TEST', '1106251110,88.19,17.05,12.95,96.72,62.65,49.9,23.49,60.89', '839');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:11:00', 'ESP', '1106251111,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '840');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:11:00', 'TEST', '1106251111,96.3,66.88,69.5,59.75,86.9,56.47,68.75,21.77', '841');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:12:00', 'ESP', '1106251112,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '842');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:12:00', 'TEST', '1106251112,74.11,91.82,79.87,16.86,72.84,71.42,78.46,64.01', '843');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:13:00', 'ESP', '1106251113,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '844');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:13:00', 'TEST', '1106251113,91.98,3.78,30.52,77.96,78.43,9.1,16.65,44.32', '845');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:14:00', 'ESP', '1106251114,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '846');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:14:00', 'TEST', '1106251114,41.53,46.74,1.7,40.65,53.3,62.9,61.76,56.41', '847');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:15:00', 'ESP', '1106251115,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '848');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:15:00', 'TEST', '1106251115,75.26,55.24,68.07,24.46,6.71,75.89,9.01,42.51', '849');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:16:00', 'ESP', '1106251116,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '850');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:16:00', 'TEST', '1106251116,82.04,66.95,6.6,74.4,16.27,28.69,66.5,47.66', '851');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:17:00', 'ESP', '1106251117,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '852');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:17:00', 'TEST', '1106251117,17.14,83.36,60.86,71.33,22.1,66.06,41.64,34.91', '853');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:18:00', 'ESP', '1106251118,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '854');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:18:00', 'TEST', '1106251118,59.55,45.89,2.17,95.44,70.89,7.92,76.08,0.6', '855');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:19:00', 'ESP', '1106251119,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '856');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:19:00', 'TEST', '1106251119,85.03,74.25,80.66,66.87,53.0,62.33,27.25,73.44', '857');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:20:00', 'ESP', '1106251120,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '858');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:20:00', 'TEST', '1106251120,54.93,38.39,93.32,53.76,32.63,80.56,44.09,70.43', '859');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:21:00', 'ESP', '1106251121,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '860');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:21:00', 'TEST', '1106251121,59.63,83.72,94.32,34.81,96.49,26.66,40.44,97.67', '861');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:22:00', 'ESP', '1106251122,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '862');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:22:00', 'TEST', '1106251122,11.49,41.01,94.19,3.92,17.41,35.1,24.1,26.91', '863');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:23:00', 'ESP', '1106251123,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '864');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:23:00', 'TEST', '1106251123,70.38,13.92,15.71,91.54,26.22,28.61,20.33,23.88', '865');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:24:00', 'ESP', '1106251124,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '866');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:24:00', 'TEST', '1106251124,9.68,30.09,19.59,47.26,20.94,56.9,49.79,8.98', '867');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:25:00', 'ESP', '1106251125,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '868');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:25:00', 'TEST', '1106251125,63.1,4.66,65.63,18.07,18.98,32.05,40.12,63.7', '869');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:26:00', 'ESP', '1106251126,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '870');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:26:00', 'TEST', '1106251126,22.73,70.61,25.73,98.84,63.7,36.13,48.76,60.69', '871');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:27:00', 'ESP', '1106251127,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.37', '872');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:27:00', 'TEST', '1106251127,45.39,36.5,74.49,21.33,91.16,95.8,41.71,34.58', '873');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:28:00', 'ESP', '1106251128,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.37', '874');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:28:00', 'TEST', '1106251128,9.19,37.04,37.43,2.92,19.34,47.28,54.46,87.1', '875');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:29:00', 'ESP', '1106251129,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '876');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:29:00', 'TEST', '1106251129,7.59,78.89,59.9,62.56,37.75,42.51,86.13,8.1', '877');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:30:00', 'ESP', '1106251130,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '878');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:30:00', 'TEST', '1106251130,29.43,12.26,20.12,50.8,76.74,33.23,1.29,86.0', '879');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:31:00', 'ESP', '1106251131,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '880');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:31:00', 'TEST', '1106251131,30.59,55.36,45.39,19.4,44.5,9.17,29.42,4.14', '881');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:32:00', 'ESP', '1106251132,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '882');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:32:00', 'TEST', '1106251132,37.54,80.07,90.98,26.94,82.52,24.18,6.29,49.45', '883');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:33:00', 'ESP', '1106251133,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '884');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:33:00', 'TEST', '1106251133,5.11,41.98,41.97,40.08,63.83,29.93,90.0,85.06', '885');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:34:00', 'ESP', '1106251134,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '886');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:34:00', 'TEST', '1106251134,72.79,66.29,82.92,66.1,54.16,61.02,34.58,36.22', '887');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:35:00', 'ESP', '1106251135,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '888');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:35:00', 'TEST', '1106251135,2.62,12.42,90.61,25.95,52.54,23.21,18.52,20.47', '889');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:36:00', 'ESP', '1106251136,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.34', '890');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:36:00', 'TEST', '1106251136,11.63,49.33,57.04,12.78,86.4,78.39,62.98,63.11', '891');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:37:00', 'ESP', '1106251137,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '892');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:37:00', 'TEST', '1106251137,37.41,30.23,78.85,85.11,14.49,83.66,31.89,38.42', '893');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:38:00', 'ESP', '1106251138,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.3,0.0,12.35', '894');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:38:00', 'TEST', '1106251138,27.63,6.58,60.51,64.4,71.66,68.12,46.82,0.07', '895');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:39:00', 'ESP', '1106251139,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '896');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:39:00', 'TEST', '1106251139,34.54,41.56,13.54,49.58,95.38,87.75,39.16,94.44', '897');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:40:00', 'ESP', '1106251140,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '898');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:40:00', 'TEST', '1106251140,47.92,92.5,93.49,20.61,88.27,99.37,55.12,30.09', '899');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:41:00', 'ESP', '1106251141,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '900');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:41:00', 'TEST', '1106251141,81.12,22.99,92.08,23.62,80.13,28.59,67.62,67.64', '901');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:42:00', 'ESP', '1106251142,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '902');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:42:00', 'TEST', '1106251142,38.64,12.88,24.54,9.6,22.56,93.85,9.21,18.51', '903');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:43:00', 'ESP', '1106251143,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '904');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:43:00', 'TEST', '1106251143,23.46,98.02,61.72,17.35,2.75,76.82,42.04,27.22', '905');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:44:00', 'ESP', '1106251144,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '906');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:44:00', 'TEST', '1106251144,38.24,56.35,58.44,11.23,27.55,56.58,25.19,35.18', '907');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:45:00', 'ESP', '1106251145,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '908');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:45:00', 'TEST', '1106251145,20.5,25.11,91.38,77.5,78.54,98.78,62.08,65.42', '909');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:46:00', 'ESP', '1106251146,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '910');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:46:00', 'TEST', '1106251146,66.9,88.74,92.54,49.1,58.86,45.91,27.86,44.88', '911');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:47:00', 'ESP', '1106251147,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '912');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:47:00', 'TEST', '1106251147,3.56,37.78,79.28,54.2,64.62,7.77,38.21,63.54', '913');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:48:00', 'ESP', '1106251148,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '914');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:48:00', 'TEST', '1106251148,86.23,22.96,18.11,14.53,61.4,90.41,80.44,58.66', '915');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:49:00', 'ESP', '1106251149,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.34', '916');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:50:00', 'TEST', '1106251150,17.29,58.31,97.7,3.9,94.64,70.95,51.12,27.94', '917');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:50:00', 'ESP', '1106251150,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.35', '918');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:51:00', 'TEST', '1106251151,61.4,82.68,73.41,11.37,89.68,92.74,63.15,89.68', '919');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:51:00', 'ESP', '1106251151,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.2,0.0,12.35', '920');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:52:00', 'TEST', '1106251152,3.79,91.75,72.8,89.29,97.77,2.35,50.17,98.85', '921');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:52:00', 'ESP', '1106251152,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '922');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:53:00', 'TEST', '1106251153,62.66,69.77,56.23,75.44,48.88,29.98,69.6,38.95', '923');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:53:00', 'ESP', '1106251153,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.35', '924');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:54:00', 'TEST', '1106251154,89.63,36.82,15.16,5.25,18.51,86.79,60.16,17.99', '925');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:54:00', 'ESP', '1106251154,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '926');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:55:00', 'TEST', '1106251155,53.82,13.51,59.46,38.13,9.75,9.27,50.87,58.21', '927');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:55:00', 'ESP', '1106251155,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '928');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:56:00', 'TEST', '1106251156,52.88,61.78,50.17,48.17,37.57,89.35,83.38,88.73', '929');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:56:00', 'ESP', '1106251156,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '930');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:57:00', 'TEST', '1106251157,9.03,32.83,18.69,84.03,69.54,79.98,44.02,52.51', '931');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:57:00', 'ESP', '1106251157,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.35', '932');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:58:00', 'TEST', '1106251158,33.64,46.58,45.25,72.23,36.65,73.35,6.85,3.12', '933');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:58:00', 'ESP', '1106251158,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '934');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:59:00', 'TEST', '1106251159,20.93,94.34,65.14,98.22,34.87,0.79,99.72,24.63', '935');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '11:59:00', 'ESP', '1106251159,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.35', '936');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:00:00', 'TEST', '1106251200,64.93,22.39,23.33,5.61,70.79,38.11,66.56,23.87', '937');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:00:00', 'ESP', '1106251200,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.35', '938');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:01:00', 'TEST', '1106251201,31.77,27.79,9.34,8.37,35.52,66.31,49.79,30.43', '939');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:01:00', 'ESP', '1106251201,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.35', '940');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:02:00', 'TEST', '1106251202,5.91,69.61,94.57,34.75,79.79,4.88,73.97,31.47', '941');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:02:00', 'ESP', '1106251202,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '942');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:03:00', 'TEST', '1106251203,46.61,65.79,58.06,59.42,29.41,89.59,87.42,38.67', '943');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:03:00', 'ESP', '1106251203,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '944');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:04:00', 'TEST', '1106251204,15.92,69.8,69.8,24.99,26.74,53.3,33.39,69.1', '945');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:04:00', 'ESP', '1106251204,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '946');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:05:00', 'TEST', '1106251205,6.04,58.07,24.04,40.67,40.14,98.59,11.89,73.95', '947');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:05:00', 'ESP', '1106251205,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.1,0.0,12.34', '948');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:06:00', 'TEST', '1106251206,30.57,41.62,11.02,34.56,60.76,5.68,65.17,90.89', '949');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:06:00', 'ESP', '1106251206,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.34', '950');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:07:00', 'TEST', '1106251207,34.01,59.8,87.07,56.31,94.27,65.42,46.22,45.99', '951');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:07:00', 'ESP', '1106251207,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.35', '952');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:08:00', 'TEST', '1106251208,8.78,18.47,26.9,36.76,87.78,24.13,33.23,92.05', '953');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:08:00', 'ESP', '1106251208,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.37', '954');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:09:00', 'TEST', '1106251209,46.28,35.93,21.58,58.7,0.5,64.93,1.1,74.14', '955');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:09:00', 'ESP', '1106251209,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.34', '956');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:10:00', 'TEST', '1106251210,39.72,47.43,11.92,24.32,26.51,38.25,66.98,3.08', '957');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:10:00', 'ESP', '1106251210,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.34', '958');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:11:00', 'TEST', '1106251211,20.04,74.85,5.19,13.73,3.25,31.94,21.68,18.07', '959');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:11:00', 'ESP', '1106251211,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0972.0,0.0,12.35', '960');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:12:00', 'TEST', '1106251212,86.88,9.08,7.68,32.9,55.19,49.68,58.24,0.17', '961');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:12:00', 'ESP', '1106251212,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.34', '962');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:13:00', 'TEST', '1106251213,11.59,95.38,16.97,52.53,34.81,40.27,83.9,77.46', '963');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:13:00', 'ESP', '1106251213,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.35', '964');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:14:00', 'TEST', '1106251214,90.62,34.32,25.26,42.31,27.76,29.39,72.04,45.58', '965');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:14:00', 'ESP', '1106251214,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.35', '966');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:15:00', 'TEST', '1106251215,17.07,10.28,74.37,84.11,25.01,43.92,65.06,78.06', '967');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:15:00', 'ESP', '1106251215,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.35', '968');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:16:00', 'TEST', '1106251216,31.03,84.18,68.77,51.63,40.64,28.55,20.92,37.67', '969');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:16:00', 'ESP', '1106251216,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.34', '970');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:17:00', 'TEST', '1106251217,27.72,82.13,16.4,0.29,55.8,15.8,71.55,76.96', '971');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:17:00', 'ESP', '1106251217,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.35', '972');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:18:00', 'TEST', '1106251218,5.16,85.37,74.9,84.64,65.24,55.99,52.73,91.81', '973');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:18:00', 'ESP', '1106251218,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.35', '974');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:19:00', 'TEST', '1106251219,96.84,81.24,91.28,16.66,35.68,67.16,91.06,19.93', '975');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:19:00', 'ESP', '1106251219,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.35', '976');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:20:00', 'TEST', '1106251220,78.01,86.55,2.63,78.54,95.49,40.6,10.29,36.47', '977');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:20:00', 'ESP', '1106251220,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.34', '978');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:21:00', 'TEST', '1106251221,81.97,89.77,31.94,57.6,15.72,69.23,16.79,81.38', '979');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:21:00', 'ESP', '1106251221,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.34', '980');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:22:00', 'TEST', '1106251222,30.49,75.33,97.37,96.89,67.23,11.83,79.43,59.83', '981');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:22:00', 'ESP', '1106251222,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.9,0.0,12.35', '982');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:23:00', 'TEST', '1106251223,92.41,41.84,26.13,58.66,37.06,49.98,81.07,85.39', '983');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:23:00', 'ESP', '1106251223,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.8,0.0,12.34', '984');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:24:00', 'TEST', '1106251224,29.09,21.96,83.93,76.24,15.04,32.95,67.92,52.92', '985');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:24:00', 'ESP', '1106251224,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.8,0.0,12.37', '986');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:25:00', 'TEST', '1106251225,47.8,58.11,36.51,29.73,18.85,19.07,20.16,20.65', '987');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:25:00', 'ESP', '1106251225,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.8,0.0,12.35', '988');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:26:00', 'TEST', '1106251226,17.85,97.09,32.63,81.31,86.82,3.53,82.71,76.27', '989');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:26:00', 'ESP', '1106251226,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.8,0.0,12.35', '990');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:27:00', 'TEST', '1106251227,77.48,6.73,99.7,54.97,87.94,80.5,97.08,41.43', '991');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:27:00', 'ESP', '1106251227,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.35', '992');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:28:00', 'TEST', '1106251228,16.52,84.01,86.72,67.08,65.28,88.52,12.26,11.62', '993');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:28:00', 'ESP', '1106251228,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.35', '994');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:29:00', 'TEST', '1106251229,5.12,47.97,14.46,1.98,7.98,31.34,78.06,42.41', '995');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:29:00', 'ESP', '1106251229,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.34', '996');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:30:00', 'TEST', '1106251230,89.65,13.71,66.48,88.29,17.63,25.45,21.58,49.34', '997');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:30:00', 'ESP', '1106251230,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.35', '998');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:31:00', 'TEST', '1106251231,85.7,38.79,48.64,54.14,85.01,95.42,85.93,8.36', '999');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:31:00', 'ESP', '1106251231,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.34', '1000');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:32:00', 'TEST', '1106251232,82.45,55.06,22.88,14.51,49.46,90.0,30.54,51.34', '1001');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:32:00', 'ESP', '1106251232,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.35', '1002');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:33:00', 'TEST', '1106251233,68.62,58.22,61.52,14.89,0.72,47.51,44.82,52.36', '1003');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:33:00', 'ESP', '1106251233,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.35', '1004');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:34:00', 'TEST', '1106251234,17.72,83.06,48.77,45.58,16.09,98.91,30.15,95.23', '1005');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:34:00', 'ESP', '1106251234,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.35', '1006');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:35:00', 'TEST', '1106251235,40.36,81.77,50.6,51.09,61.0,13.28,30.99,12.72', '1007');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:35:00', 'ESP', '1106251235,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.7,0.0,12.34', '1008');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:36:00', 'TEST', '1106251236,7.17,72.21,25.27,5.28,20.57,84.29,11.03,83.42', '1009');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:36:00', 'ESP', '1106251236,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1010');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:37:00', 'TEST', '1106251237,5.92,8.34,77.86,73.86,14.26,40.67,55.48,2.85', '1011');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:37:00', 'ESP', '1106251237,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.34', '1012');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:38:00', 'TEST', '1106251238,88.29,32.33,94.03,33.55,55.05,32.14,51.26,90.15', '1013');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:38:00', 'ESP', '1106251238,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1014');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:39:00', 'TEST', '1106251239,37.54,95.17,15.33,86.65,71.77,97.9,78.06,29.23', '1015');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:39:00', 'ESP', '1106251239,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1016');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:40:00', 'TEST', '1106251240,8.96,83.03,16.34,51.35,74.72,90.62,51.34,89.02', '1017');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:40:00', 'ESP', '1106251240,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1018');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:41:00', 'TEST', '1106251241,57.5,19.09,67.69,16.8,48.53,71.98,88.36,77.64', '1019');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:41:00', 'ESP', '1106251241,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1020');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:42:00', 'TEST', '1106251242,59.7,27.38,31.23,85.61,37.32,78.85,5.21,37.41', '1021');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:42:00', 'ESP', '1106251242,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.5,0.0,12.35', '1022');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:43:00', 'TEST', '1106251243,11.92,62.12,45.42,36.46,27.68,95.36,22.23,13.08', '1023');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:43:00', 'ESP', '1106251243,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.5,0.0,12.37', '1024');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:44:00', 'TEST', '1106251244,61.87,73.23,33.04,69.93,89.1,26.66,40.45,86.41', '1025');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:44:00', 'ESP', '1106251244,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.34', '1026');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:45:00', 'TEST', '1106251245,43.23,75.51,97.59,60.6,5.01,59.65,96.45,21.52', '1027');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:45:00', 'ESP', '1106251245,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1028');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:46:00', 'TEST', '1106251246,4.86,89.89,84.5,77.32,98.91,4.52,77.51,35.64', '1029');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:46:00', 'ESP', '1106251246,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.34', '1030');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:47:00', 'TEST', '1106251247,16.09,58.25,43.83,33.78,63.5,44.01,76.85,37.64', '1031');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:47:00', 'ESP', '1106251247,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1032');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:48:00', 'TEST', '1106251248,72.12,75.34,48.4,10.59,29.34,30.56,57.56,5.18', '1033');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:48:00', 'ESP', '1106251248,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1034');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:49:00', 'TEST', '1106251249,73.4,68.46,77.94,50.77,36.14,16.37,36.41,52.28', '1035');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:49:00', 'ESP', '1106251249,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.34', '1036');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:50:00', 'TEST', '1106251250,64.99,65.56,86.64,37.08,79.72,7.56,61.5,55.38', '1037');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:50:00', 'ESP', '1106251250,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1038');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:51:00', 'TEST', '1106251251,63.66,72.47,46.65,30.45,25.15,48.55,28.98,73.99', '1039');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:51:00', 'ESP', '1106251251,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1040');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:52:00', 'TEST', '1106251252,23.31,72.35,14.37,10.71,1.62,11.24,88.01,81.85', '1041');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:52:00', 'ESP', '1106251252,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.34', '1042');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:53:00', 'TEST', '1106251253,64.7,68.65,68.21,99.27,27.43,2.31,21.57,92.31', '1043');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:53:00', 'ESP', '1106251253,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1044');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:54:00', 'TEST', '1106251254,70.86,82.67,43.46,13.58,47.44,36.68,81.96,31.55', '1045');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:54:00', 'ESP', '1106251254,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.35', '1046');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:55:00', 'TEST', '1106251255,96.79,18.07,20.78,8.32,73.38,31.79,23.76,40.66', '1047');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:55:00', 'ESP', '1106251255,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.34', '1048');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:56:00', 'TEST', '1106251256,6.31,72.87,79.38,41.97,75.75,14.67,90.97,23.07', '1049');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:56:00', 'ESP', '1106251256,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.6,0.0,12.34', '1050');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:57:00', 'TEST', '1106251257,50.21,97.26,30.43,61.25,30.18,65.12,50.98,64.14', '1051');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:57:00', 'ESP', '1106251257,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.5,0.0,12.35', '1052');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:58:00', 'TEST', '1106251258,31.41,5.97,98.33,98.84,76.93,59.45,55.88,12.07', '1053');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:58:00', 'ESP', '1106251258,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.5,0.0,12.34', '1054');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:59:00', 'TEST', '1106251259,80.8,41.42,88.52,86.46,21.75,43.18,8.42,64.89', '1055');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '12:59:00', 'ESP', '1106251259,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.5,0.0,12.35', '1056');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:00:00', 'TEST', '1106251300,7.94,2.73,68.45,28.91,80.88,23.98,18.38,15.68', '1057');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:00:00', 'ESP', '1106251300,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.5,0.0,12.35', '1058');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:01:00', 'TEST', '1106251301,4.35,30.86,2.49,58.04,71.21,76.55,6.93,72.69', '1059');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:01:00', 'ESP', '1106251301,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.5,0.0,12.35', '1060');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:02:00', 'TEST', '1106251302,44.68,58.84,91.02,12.95,75.11,4.36,19.76,89.85', '1061');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:02:00', 'ESP', '1106251302,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.35', '1062');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:03:00', 'TEST', '1106251303,43.01,74.59,33.8,87.06,87.81,93.66,49.7,30.73', '1063');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:03:00', 'ESP', '1106251303,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.34', '1064');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:04:00', 'TEST', '1106251304,77.6,62.7,75.77,61.94,90.46,95.5,95.98,47.94', '1065');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:04:00', 'ESP', '1106251304,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.35', '1066');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:05:00', 'TEST', '1106251305,38.87,74.62,74.26,53.16,81.14,71.46,52.96,93.37', '1067');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:05:00', 'ESP', '1106251305,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.35', '1068');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:06:00', 'TEST', '1106251306,83.37,95.94,14.79,14.51,94.52,89.6,50.78,57.95', '1069');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:06:00', 'ESP', '1106251306,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.34', '1070');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:07:00', 'TEST', '1106251307,39.83,15.04,10.54,53.87,17.96,32.64,78.91,90.59', '1071');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:07:00', 'ESP', '1106251307,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.35', '1072');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:08:00', 'TEST', '1106251308,27.68,58.15,33.03,7.65,85.96,51.5,35.23,42.2', '1073');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:08:00', 'ESP', '1106251308,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.34', '1074');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:09:00', 'TEST', '1106251309,75.1,93.26,1.48,40.02,7.71,79.04,42.62,19.89', '1075');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:09:00', 'ESP', '1106251309,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.35', '1076');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:10:00', 'TEST', '1106251310,97.51,62.5,17.89,67.73,70.23,58.25,80.52,21.08', '1077');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:10:00', 'ESP', '1106251310,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.35', '1078');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:11:00', 'TEST', '1106251311,2.67,93.38,62.49,72.75,51.38,80.74,89.6,39.76', '1079');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:11:00', 'ESP', '1106251311,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.34', '1080');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:12:00', 'TEST', '1106251312,58.81,45.69,34.96,9.34,80.77,6.42,90.32,82.21', '1081');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:12:00', 'ESP', '1106251312,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.4,0.0,12.35', '1082');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:13:00', 'TEST', '1106251313,21.51,22.12,73.17,43.89,40.13,96.74,7.11,44.79', '1083');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:13:00', 'ESP', '1106251313,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.3,0.0,12.35', '1084');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:14:00', 'TEST', '1106251314,94.05,28.86,44.05,88.95,94.01,27.33,59.48,57.02', '1085');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:14:00', 'ESP', '1106251314,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.3,0.0,12.34', '1086');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:15:00', 'TEST', '1106251315,29.32,37.19,11.38,86.94,52.08,47.35,15.98,69.63', '1087');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:15:00', 'ESP', '1106251315,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.3,0.0,12.34', '1088');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:16:00', 'TEST', '1106251316,39.17,15.57,0.22,42.95,10.0,54.11,78.56,50.4', '1089');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:16:00', 'ESP', '1106251316,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.3,0.0,12.37', '1090');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:17:00', 'TEST', '1106251317,86.14,93.45,91.48,72.08,90.66,38.71,42.86,11.63', '1091');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:17:00', 'ESP', '1106251317,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.2,0.0,12.34', '1092');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:18:00', 'TEST', '1106251318,33.15,30.12,34.22,24.53,87.71,53.51,20.98,56.82', '1093');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:18:00', 'ESP', '1106251318,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.2,0.0,12.35', '1094');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:19:00', 'TEST', '1106251319,89.03,65.91,84.79,99.2,78.94,99.03,52.5,30.14', '1095');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:19:00', 'ESP', '1106251319,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.2,0.0,12.34', '1096');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:20:00', 'TEST', '1106251320,88.08,39.77,50.02,36.15,0.81,55.81,57.63,58.64', '1097');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:20:00', 'ESP', '1106251320,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.2,0.0,12.34', '1098');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:21:00', 'TEST', '1106251321,78.7,50.75,28.19,57.9,9.38,74.28,85.41,54.4', '1099');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:21:00', 'ESP', '1106251321,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.2,0.0,12.35', '1100');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:22:00', 'TEST', '1106251322,49.64,4.68,26.52,50.54,57.48,56.16,9.04,95.82', '1101');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:22:00', 'ESP', '1106251322,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.1,0.0,12.35', '1102');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:23:00', 'TEST', '1106251323,26.09,81.78,86.31,92.18,40.6,44.57,42.57,53.61', '1103');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:23:00', 'ESP', '1106251323,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.1,0.0,12.35', '1104');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:24:00', 'TEST', '1106251324,80.14,79.78,72.34,99.24,65.26,60.07,38.61,20.92', '1105');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:24:00', 'ESP', '1106251324,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.1,0.0,12.35', '1106');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:25:00', 'TEST', '1106251325,82.02,10.57,31.41,95.89,64.89,94.94,47.63,73.37', '1107');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:25:00', 'ESP', '1106251325,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.1,0.0,12.35', '1108');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:26:00', 'TEST', '1106251326,66.68,53.49,67.6,97.03,26.61,89.63,53.19,30.9', '1109');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:26:00', 'ESP', '1106251326,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.0,0.0,12.34', '1110');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:27:00', 'TEST', '1106251327,65.57,66.61,76.09,20.1,68.2,67.37,49.19,71.27', '1111');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:27:00', 'ESP', '1106251327,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.0,0.0,12.35', '1112');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:28:00', 'TEST', '1106251328,51.25,72.03,47.51,30.8,10.65,11.15,18.91,1.3', '1113');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:28:00', 'ESP', '1106251328,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.0,0.0,12.34', '1114');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:29:00', 'TEST', '1106251329,1.58,77.28,16.22,47.63,39.25,38.82,12.95,33.42', '1115');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:29:00', 'ESP', '1106251329,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0971.0,0.0,12.34', '1116');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:30:00', 'TEST', '1106251330,4.27,42.23,13.16,97.4,49.5,27.85,89.14,13.38', '1117');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:31:00', 'TEST', '1106251331,37.08,18.08,69.09,14.0,21.48,34.04,12.59,79.55', '1118');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:32:00', 'TEST', '1106251332,65.64,71.91,92.05,54.26,96.33,12.49,61.23,89.99', '1119');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:33:00', 'TEST', '1106251333,94.6,82.05,32.7,76.0,62.77,48.09,16.3,78.61', '1120');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:34:00', 'TEST', '1106251334,15.08,95.66,68.13,88.46,98.27,16.11,71.53,49.12', '1121');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:35:00', 'TEST', '1106251335,47.83,70.8,72.07,15.0,57.04,41.98,25.53,11.25', '1122');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:36:00', 'TEST', '1106251336,18.55,99.54,58.91,68.32,18.09,72.93,98.9,41.77', '1123');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:37:00', 'TEST', '1106251337,84.53,9.57,72.67,22.99,4.34,19.61,3.43,25.06', '1124');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:38:00', 'TEST', '1106251338,2.6,22.56,12.92,53.55,90.65,89.43,61.47,15.02', '1125');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:39:00', 'TEST', '1106251339,44.09,24.57,72.12,28.68,71.96,60.35,61.54,41.94', '1126');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:40:00', 'TEST', '1106251340,53.53,71.63,55.57,66.78,60.59,46.91,45.83,21.57', '1127');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:41:00', 'TEST', '1106251341,32.13,31.03,28.65,59.14,27.12,69.27,5.99,84.41', '1128');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:42:00', 'TEST', '1106251342,66.68,57.61,36.08,0.41,8.57,59.14,45.3,13.72', '1129');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:43:00', 'TEST', '1106251343,94.89,40.06,7.49,68.63,33.99,28.96,17.94,19.61', '1130');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:44:00', 'TEST', '1106251344,47.6,28.74,78.26,50.17,33.27,80.24,72.85,9.48', '1131');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:45:00', 'TEST', '1106251345,67.83,80.0,96.55,77.68,90.72,36.27,61.72,24.14', '1132');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:46:00', 'TEST', '1106251346,62.69,96.95,96.21,90.84,39.96,80.39,39.36,76.97', '1133');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:47:00', 'TEST', '1106251347,84.68,39.7,38.44,47.51,26.43,29.46,10.54,85.35', '1134');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:48:00', 'TEST', '1106251348,86.43,54.36,15.5,26.2,31.53,77.91,53.52,81.51', '1135');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:49:00', 'TEST', '1106251349,35.45,59.0,16.0,30.21,71.48,91.41,61.13,41.01', '1136');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:50:00', 'TEST', '1106251350,91.25,90.28,84.25,91.24,40.37,47.57,16.91,67.05', '1137');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:51:00', 'TEST', '1106251351,0.08,41.69,49.82,63.87,91.29,43.72,58.28,88.45', '1138');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:52:00', 'TEST', '1106251352,5.76,28.92,1.18,27.09,11.57,83.99,18.59,75.27', '1139');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:53:00', 'TEST', '1106251353,98.0,40.53,30.81,51.71,54.06,43.34,49.72,16.64', '1140');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:54:00', 'TEST', '1106251354,50.89,9.14,34.81,18.71,31.15,24.37,7.01,85.38', '1141');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:55:00', 'TEST', '1106251355,60.98,64.29,73.87,76.13,77.99,22.55,34.27,55.77', '1142');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:56:00', 'TEST', '1106251356,77.91,96.58,57.72,94.8,64.56,78.18,46.89,43.81', '1143');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:57:00', 'TEST', '1106251357,23.71,20.39,56.89,44.98,53.72,31.1,85.03,31.05', '1144');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:58:00', 'TEST', '1106251358,97.44,87.62,41.73,38.02,99.39,26.95,67.47,30.26', '1145');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '13:59:00', 'TEST', '1106251359,83.44,49.0,3.64,51.19,86.95,65.1,65.36,57.15', '1146');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:00:00', 'TEST', '1106251400,89.03,78.77,37.25,51.26,14.45,22.35,12.03,45.47', '1147');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:01:00', 'TEST', '1106251401,38.21,99.74,67.23,44.86,81.61,21.54,93.91,66.15', '1148');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:02:00', 'TEST', '1106251402,68.68,5.94,39.42,86.98,88.34,33.15,55.39,54.1', '1149');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:02:00', 'ESP', '1106251402,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.7,0.0,12.34', '1150');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:03:00', 'TEST', '1106251403,27.31,14.46,22.97,34.9,21.82,32.32,0.36,3.7', '1151');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:03:00', 'ESP', '1106251403,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.7,0.0,12.35', '1152');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:04:00', 'TEST', '1106251404,14.62,30.65,76.68,16.32,42.76,57.5,72.58,26.87', '1153');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:04:00', 'ESP', '1106251404,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.7,0.0,12.35', '1154');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:05:00', 'TEST', '1106251405,2.37,82.53,42.07,82.92,73.22,25.41,74.54,82.97', '1155');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:05:00', 'ESP', '1106251405,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.7,0.0,12.35', '1156');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:06:00', 'TEST', '1106251406,75.92,16.6,17.81,98.83,68.76,33.94,18.17,31.66', '1157');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:06:00', 'ESP', '1106251406,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.7,0.0,12.35', '1158');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:07:00', 'TEST', '1106251407,41.61,99.99,32.54,51.48,1.34,69.61,85.67,34.79', '1159');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:07:00', 'ESP', '1106251407,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.7,0.0,12.35', '1160');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:08:00', 'TEST', '1106251408,35.3,46.2,8.18,91.84,55.42,90.02,96.21,54.12', '1161');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:08:00', 'ESP', '1106251408,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.7,0.0,12.35', '1162');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:09:00', 'TEST', '1106251409,70.34,25.4,41.4,71.08,13.01,26.11,17.52,91.76', '1163');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:09:00', 'ESP', '1106251409,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.6,0.0,12.35', '1164');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:10:00', 'TEST', '1106251410,26.8,43.08,14.6,32.31,93.85,85.73,86.45,34.34', '1165');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:10:00', 'ESP', '1106251410,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.6,0.0,12.34', '1166');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:11:00', 'TEST', '1106251411,9.68,85.24,68.86,32.66,78.1,48.88,4.32,26.95', '1167');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:11:00', 'ESP', '1106251411,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.6,0.0,12.35', '1168');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:12:00', 'TEST', '1106251412,81.13,5.08,2.08,97.92,13.83,26.92,84.53,8.39', '1169');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:12:00', 'ESP', '1106251412,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.6,0.0,12.35', '1170');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:13:00', 'TEST', '1106251413,22.63,58.94,63.03,42.73,24.85,76.89,51.67,60.21', '1171');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:13:00', 'ESP', '1106251413,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.6,0.0,12.34', '1172');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:14:00', 'TEST', '1106251414,72.32,14.77,16.93,57.71,30.07,1.71,94.9,63.06', '1173');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:14:00', 'ESP', '1106251414,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.5,0.0,12.34', '1174');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:15:00', 'TEST', '1106251415,14.36,13.7,2.99,43.56,97.16,58.14,78.04,70.23', '1175');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:15:00', 'ESP', '1106251415,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.5,0.0,12.35', '1176');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:16:00', 'TEST', '1106251416,15.5,70.22,85.14,8.91,33.07,19.5,1.98,35.06', '1177');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:16:00', 'ESP', '1106251416,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.5,0.0,12.34', '1178');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:17:00', 'TEST', '1106251417,16.89,8.0,35.18,78.76,66.43,22.58,13.79,44.25', '1179');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:17:00', 'ESP', '1106251417,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.5,0.0,12.34', '1180');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:18:00', 'TEST', '1106251418,25.34,48.13,88.97,92.07,92.12,41.95,20.31,85.55', '1181');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:18:00', 'ESP', '1106251418,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.4,0.0,12.34', '1182');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:19:00', 'TEST', '1106251419,67.17,30.9,43.68,5.83,55.01,72.18,29.06,63.49', '1183');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:19:00', 'ESP', '1106251419,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.4,0.0,12.34', '1184');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:20:00', 'TEST', '1106251420,0.37,6.57,11.54,88.87,40.56,44.99,2.32,35.37', '1185');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:20:00', 'ESP', '1106251420,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.4,0.0,12.35', '1186');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:21:00', 'TEST', '1106251421,1.69,64.13,3.06,21.22,46.55,9.06,31.29,5.63', '1187');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:21:00', 'ESP', '1106251421,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.4,0.0,12.35', '1188');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:22:00', 'TEST', '1106251422,49.76,25.24,17.66,31.19,40.24,87.29,16.94,21.59', '1189');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:22:00', 'ESP', '1106251422,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.4,0.0,12.35', '1190');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:23:00', 'TEST', '1106251423,81.56,36.51,18.52,28.13,41.8,48.53,67.99,74.61', '1191');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:23:00', 'ESP', '1106251423,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.34', '1192');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:24:00', 'TEST', '1106251424,36.39,36.18,63.48,57.55,60.46,5.5,80.48,84.52', '1193');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:24:00', 'ESP', '1106251424,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.34', '1194');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:25:00', 'TEST', '1106251425,14.79,22.74,13.5,29.17,2.71,36.75,48.9,81.74', '1195');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:25:00', 'ESP', '1106251425,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.35', '1196');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:26:00', 'TEST', '1106251426,73.17,64.26,87.56,93.53,2.19,92.37,67.35,24.57', '1197');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:26:00', 'ESP', '1106251426,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.35', '1198');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:27:00', 'TEST', '1106251427,26.0,20.37,61.24,71.36,36.89,5.04,79.44,39.98', '1199');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:27:00', 'ESP', '1106251427,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.34', '1200');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:28:00', 'TEST', '1106251428,45.02,48.08,83.51,50.22,68.72,44.64,62.26,86.94', '1201');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:28:00', 'ESP', '1106251428,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.34', '1202');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:29:00', 'TEST', '1106251429,43.19,64.51,99.59,27.14,38.41,65.16,58.21,98.44', '1203');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:29:00', 'ESP', '1106251429,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.34', '1204');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:30:00', 'TEST', '1106251430,87.78,47.07,12.92,83.36,82.55,94.28,70.9,47.6', '1205');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:30:00', 'ESP', '1106251430,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.34', '1206');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:31:00', 'TEST', '1106251431,20.56,67.89,89.52,60.27,8.83,53.54,76.0,27.87', '1207');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:31:00', 'ESP', '1106251431,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.3,0.0,12.35', '1208');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:32:00', 'TEST', '1106251432,40.54,53.94,54.8,46.51,24.59,34.15,48.12,88.07', '1209');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:32:00', 'ESP', '1106251432,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.2,0.0,12.34', '1210');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:33:00', 'TEST', '1106251433,83.46,8.47,85.96,67.23,46.79,49.9,31.12,89.72', '1211');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:33:00', 'ESP', '1106251433,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.2,0.0,12.35', '1212');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:34:00', 'TEST', '1106251434,61.47,87.9,36.53,40.81,67.83,52.16,10.68,14.92', '1213');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:34:00', 'ESP', '1106251434,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.2,0.0,12.35', '1214');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:35:00', 'TEST', '1106251435,28.54,39.23,17.43,58.25,29.45,66.7,79.09,36.38', '1215');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:35:00', 'ESP', '1106251435,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.2,0.0,12.34', '1216');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:36:00', 'TEST', '1106251436,4.95,3.75,6.22,20.19,32.46,38.89,30.05,70.78', '1217');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:36:00', 'ESP', '1106251436,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.2,0.0,12.35', '1218');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:37:00', 'TEST', '1106251437,1.78,79.48,73.51,46.39,17.66,92.81,67.52,76.71', '1219');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:37:00', 'ESP', '1106251437,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.2,0.0,12.35', '1220');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:38:00', 'TEST', '1106251438,75.59,66.55,2.38,33.82,41.94,92.63,72.65,39.73', '1221');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:38:00', 'ESP', '1106251438,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.1,0.0,12.34', '1222');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:39:00', 'TEST', '1106251439,35.57,95.33,14.97,62.07,24.03,24.67,48.85,31.13', '1223');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:39:00', 'ESP', '1106251439,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.1,0.0,12.35', '1224');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:40:00', 'TEST', '1106251440,35.62,40.7,25.09,96.41,40.63,14.58,37.78,30.95', '1225');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:40:00', 'ESP', '1106251440,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.1,0.0,12.34', '1226');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:41:00', 'TEST', '1106251441,40.96,88.54,80.98,6.35,26.91,60.08,1.5,22.43', '1227');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:41:00', 'ESP', '1106251441,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.1,0.0,12.34', '1228');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:42:00', 'TEST', '1106251442,94.97,28.31,68.95,1.78,56.32,83.18,46.33,9.12', '1229');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:42:00', 'ESP', '1106251442,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.1,0.0,12.37', '1230');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:43:00', 'TEST', '1106251443,52.06,41.61,2.16,48.45,73.27,54.92,39.62,45.08', '1231');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:43:00', 'ESP', '1106251443,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.1,0.0,12.34', '1232');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:44:00', 'TEST', '1106251444,53.83,17.34,33.51,15.32,64.36,95.75,79.26,34.38', '1233');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:44:00', 'ESP', '1106251444,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.1,0.0,12.34', '1234');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:45:00', 'TEST', '1106251445,51.56,71.99,15.91,44.21,43.84,84.06,32.91,55.36', '1235');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:45:00', 'ESP', '1106251445,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.0,0.0,12.34', '1236');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:46:00', 'TEST', '1106251446,17.47,84.57,29.95,59.55,67.04,31.98,27.88,22.49', '1237');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:46:00', 'ESP', '1106251446,100.00,100.00,100.00,100.00,100.00,100.00,0.00,0.00,0,0970.0,0.0,12.34', '1238');
INSERT INTO logdata (`date`, `time`, `device_id`, `data`, `id`) VALUES ('2025-06-11', '14:47:00', 'TEST', '1106251447,79.13,42.99,24.08,20.47,90.98,14.13,84.7,3.84', '1239');
CREATE TABLE IF NOT EXISTS  `logparam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param_name` varchar(30) NOT NULL,
  `param_type` varchar(30) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `position` int(3) NOT NULL,
  `device_id` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('5', 'Timestamp', 'datetime', 'DD-MM-YY hh:mm', '1', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('6', 'Temperature', 'float', 'C', '2', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('7', 'Max Temperature', 'float', 'C', '3', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('8', 'Min Temperature', 'float', 'C', '4', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('9', 'Wind Speed', 'float', 'm/s', '5', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('10', 'Wind Direction', 'float', 'Deg', '6', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('11', 'Max Wind', 'float', 'm/s', '7', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('12', 'Humidity', 'float', '%', '8', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('13', 'Max Humidity', 'float', '%', '9', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('14', 'Min Humidity', 'float', '%', '10', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('15', 'Barometric Pressure', 'float', 'mbar', '11', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('16', 'Rainfall', 'float', 'mm', '12', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('17', 'Battery Voltage', 'float', 'V', '13', 'ESP');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('20', 'Timestamp', 'datetime', 'dd-MM-YYYY HH:mm:ss', '1', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('21', 'Temperature', 'float', 'C', '2', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('22', 'Humidity', 'float', '%', '3', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('23', 'Rainfall', 'float', 'mm', '4', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('24', 'Min Rainfall', 'float', 'mm', '5', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('25', 'Max Rainfall', 'float', 'mm', '6', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('26', 'Pressure', 'float', 'mbar', '7', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('27', 'Min Pressure', 'float', 'mbar', '8', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('28', 'Max Pressure', 'float', 'mbar', '9', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('29', 'Wind Speed', 'float', 'm/s', '10', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('30', 'Wind Direction', 'float', 'Deg', '11', 'TEST');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('31', 'Time', 'datetime', 'dd-MM-YYYY HH:mm:ss', '1', 'ANKIT');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('32', 'Temperature', 'float', 'C', '2', 'ANKIT');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('33', 'Wind Speed', 'float', 'm/s', '3', 'ANKIT');
INSERT INTO logparam (`id`, `param_name`, `param_type`, `unit`, `position`, `device_id`) VALUES ('34', 'Wind Direction', 'float', 'Deg', '4', 'ANKIT');
CREATE TABLE IF NOT EXISTS  `modem_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param_name` varchar(30) NOT NULL,
  `param_type` varchar(30) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `graph` varchar(2) NOT NULL DEFAULT 'n',
  `position` int(3) NOT NULL,
  `device_id` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('220', 'Temperature', 'float', 'C', 'n', '1', 'ESP');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('221', 'Relative Humidity', 'float', '%', 'n', '2', 'ESP');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('222', 'Wind Speed', 'float', 'm/s', 'n', '3', 'ESP');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('223', 'Wind Direction', 'float', 'Deg', 'n', '4', 'ESP');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('224', 'Barometric Pressure', 'float', 'mbar', 'n', '5', 'ESP');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('225', 'Rainfall', 'float', 'mm', 'n', '6', 'ESP');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('226', 'Battery Voltage', 'float', 'V', 'n', '7', 'ESP');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('239', 'Temperature', 'float', 'C', 'n', '1', 'TEST');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('240', 'Rainfall', 'float', 'mm', 'n', '2', 'TEST');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('241', 'Pressure', 'float', 'mbar', 'n', '3', 'TEST');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('242', 'Wind Speed', 'float', 'm/s', 'n', '4', 'TEST');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('243', 'Temperature', 'float', 'C', 'n', '1', 'ANKIT');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('244', 'Wind Speed', 'float', 'm/s', 'n', '2', 'ANKIT');
INSERT INTO modem_params (`id`, `param_name`, `param_type`, `unit`, `graph`, `position`, `device_id`) VALUES ('245', 'Wind Direction', 'float', 'Deg', 'n', '3', 'ANKIT');
CREATE TABLE IF NOT EXISTS  `received` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(30) NOT NULL,
  `data` varchar(300) NOT NULL,
  `imei_nr` varchar(20) NOT NULL DEFAULT 'n',
  `date` date NOT NULL,
  `time` time NOT NULL,
  `recharge_status` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3162 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS  `recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `no_of_days` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO recharge (`id`, `device_id`, `start_date`, `end_date`, `no_of_days`) VALUES ('4', 'ESP', '2025-06-04', '2025-06-30', '0');
INSERT INTO recharge (`id`, `device_id`, `start_date`, `end_date`, `no_of_days`) VALUES ('6', 'TEST', '2025-06-09', '2025-06-11', '0');
INSERT INTO recharge (`id`, `device_id`, `start_date`, `end_date`, `no_of_days`) VALUES ('7', 'ANKIT', '2025-06-09', '2025-06-30', '0');
CREATE TABLE IF NOT EXISTS  `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `pincode` int(11) NOT NULL,
  `address` varchar(400) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `date_time` date NOT NULL,
  `country` varchar(50) NOT NULL,
  `department_name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO user (`id`, `user_name`, `password`, `email_id`, `city`, `pincode`, `address`, `mobile`, `date_time`, `country`, `department_name`) VALUES ('5', 'Uday', '9999', 'udaysinghg10@gmail.com', 'Roorkee', '247667', 'KB Sensormart', '1234567890', '2025-06-03', 'India', 'CSEesp');
