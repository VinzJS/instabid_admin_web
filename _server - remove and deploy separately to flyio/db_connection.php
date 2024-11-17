<?php
/**
 * This script connects to the MySQL database used by the API.
 *
 * @var string $dbHost The hostname of the database server.
 * @var string $dbUsername The username to use when connecting to the database.
 * @var string $dbPassword The password to use when connecting to the database.
 * @var string $dbName The name of the database to connect to.
 *
 * The script then creates a new MySQLi object and uses it to connect to the database.
 *
 * If the connection fails, the script will output a JSON response with a success
 * property set to false and a message property set to a string describing the
 * error. The script will then exit.
 */
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'instabid_db';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Failed to connect to database'));
    exit();
}

