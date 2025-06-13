@echo off
cd "C:\xampp\htdocs\live.onlineiotdata.in"
"C:\xampp\php\php.exe" -r "$_REQUEST['table']='all'; $_REQUEST['schedule']='daily'; include 'backup_data.php';"
exit 