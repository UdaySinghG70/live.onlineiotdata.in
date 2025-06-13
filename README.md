# IoT MQTT Client

This PHP application connects to an MQTT broker and processes messages from IoT devices, storing logged data in a MySQL database.

## Requirements

- PHP 7.4 or higher
- Composer
- MySQL/MariaDB
- MQTT Broker (configured at 103.212.120.23)

## Installation

1. Clone this repository
2. Install dependencies:
   ```bash
   composer install
   ```

## Configuration

The MQTT client is configured with the following settings:

- Host: 103.212.120.23
- Port: 1883
- Username: admin
- Password: BeagleBone99

## Message Formats

### Live Data Topic (+/live)
Format: `value1,value2,value3,...,valuen,device_id`
Example: `100.00,100.00,0.00,0,0976.2,0.0,12.32,device_id`
- The topic format uses a wildcard (+) to match any device ID (e.g., ESP/live, device1/live, sensor2/live)
- Values are matched with parameters from the `modem_params` table
- Data is processed but not stored

### Logged Data Topic (+/data)
Format: `DDMMYYHHMM,value1,value2,...,valuen,device_id`
- The topic format uses a wildcard (+) to match any device ID (e.g., ESP/data, device1/data, sensor2/data)
- First value is timestamp in format DDMMYYHHMM
- Remaining values are matched with parameters from the `logparam` table
- Data is stored in the `logdata` table

## Running the Client

To start the MQTT client:

```bash
php mqtt_client.php
```

The client will:
1. Connect to the MQTT broker
2. Subscribe to both topics (+/live and +/data)
3. Process incoming messages
4. Store data in the appropriate database tables

## Database Tables

- `modem_params`: Stores parameter definitions for live data
- `logparam`: Stores parameter definitions for logged data
- `logdata`: Stores historical data with timestamps 

## Backup System

The application includes an automated backup system that maintains database backups at different intervals:

### Backup Types

1. **Daily Backups**
   - Located in `backup/daily` directory
   - Format: `daily_DD-MM-YYYY.sql`
   - Retains backups for the last 3 days
   - Older backups are automatically cleaned up

2. **Weekly Backups**
   - Located in `backup/weekly` directory
   - Format: `weekly_weekXX.sql`
   - Keeps backups for the last 5 weeks
   - Older backups are automatically removed

3. **Monthly Backups**
   - Located in `backup/monthly` directory
   - Format: `monthly_MonthName.sql`
   - Keeps the current month's backup and previous two months
   - Backups older than 2 months are automatically deleted

### Features

- **Automatic Cleanup**: Old backups are automatically removed based on retention policies
- **Database Synchronization**: The backup table in the database stays synchronized with actual backup files
- **FTP Upload**: Backups are automatically uploaded to a configured FTP server
- **Logging**: All backup operations are logged for monitoring and troubleshooting

### Running Backups

To create a backup:
```bash
php backup_data.php?table=all&schedule=daily    # For daily backup
php backup_data.php?table=all&schedule=weekly   # For weekly backup
php backup_data.php?table=all&schedule=monthly  # For monthly backup
```

You can also backup specific tables by changing the `table` parameter:
- `table=all`: Backup all tables
- `table=received`: Backup only the received table
- `table=logdata`: Backup only the logdata table 