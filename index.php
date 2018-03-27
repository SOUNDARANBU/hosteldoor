<?php
$servername = "localhost";
$username = "root";
$password = "P@ssw0rd";

try {
    $db = new PDO("mysql:host=$servername;dbname=paisatracker", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
	$sql = "CREATE TABLE IF NOT EXISTS paisa(
		id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255)
		)";
    $db->exec($sql);
	echo "table created sucessfully";
	
	$db->exec("insert into paisa (name) values ('helo')");
	
	$stmt = $db->query('SELECT name FROM paisa');
foreach ($stmt as $row)
{
    echo $row['name'] . "\n";
}
	}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>