# MySQL User Library
I created this library to make building user profiles easier with PHP and MySQL.

## How to start:
First, you need to install Medoo framework using composer into your local (or global directory). Look up how to do that.

## Basic table setup.
```php
<?php
	
  // These variables must be set before requiring UserLibrary.php for the library to work.
	$databasename = "DataBase";                			         
	$ipaddress = "192.168.1.1";                                  
	$username = "User";                                         
	$password = "password";                                 			
	$usertablename = "tablename";												           

	// Require file.
	require "UserLibrary.php";

	// This always needs to run to ensure that a table exists.
	CreateUserDataTable();
	
	// This adds a new column to the table;
	addUserTableColumn("messages", "text", "NOT NULL");

?>
```

You now have a table with columns for a username, password, and messages that you can reference later!
