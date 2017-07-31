# You may have to run the Unblock-File INSERT_RELATIVE_FILE_PATH_TO_THIS_FILE_HERE
# If powershell is saying it won't run this due to a security risk

# Specify connection variables
$MySQLUserName = 'root'
$MySQLUserPassword = 'root'
$MySQLDatabase = 'vita'
$MySQLHost = '127.0.0.1'
$MySQLPort = '3306'


$ConnectionString = "server=" + $MySQLHost + ";port=" + $MySQLPort + ";uid=" + $MySQLUserName + ";pwd=" + $MySQLUserPassword + ";database="+$MySQLDatabase +";Allow User Variables=True"

Try {
  [void][System.Reflection.Assembly]::LoadWithPartialName("MySql.Data")
  $Connection = New-Object MySql.Data.MySqlClient.MySqlConnection
  $Connection.ConnectionString = $ConnectionString
  $Connection.Open()
  
  # Create tables
  Write-Host "Creating tables"
  $TableCreationSQL = Get-Content -Raw "./table_creation/FullDB.sql"
  $TableCreationCommand = New-Object MySql.Data.MySqlClient.MySqlCommand($TableCreationSQL, $Connection)
  $TableCreationResult = $TableCreationCommand.ExecuteNonQuery()
  if ($TableCreationResult -eq 0) {
    Write-Host "Successfully created tables"
  }
  
  # Insert table data
  Write-Host "Inserting test data"
  $TestDataSQL = Get-Content -Raw "./test_data/FullDBTestData.sql"
  $TestDataCommand = New-Object MySql.Data.MySqlClient.MySqlCommand($TestDataSQL, $Connection)
  $TestDataResult = $TestDataCommand.ExecuteNonQuery()
  if ($TestDataResult -gt 0) {
    Write-Host "Successfully inserted test data"
  }
  
  Write-Host "`r`nSUCCESSFULLY FINISHED"
} Catch {
  Write-Host "ERROR : Unable to run query : $query `n$Error[0]"
  $Error.Clear()
} Finally {
  $Connection.Close()
}