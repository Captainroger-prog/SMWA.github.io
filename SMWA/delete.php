<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";


 
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'] ?? null;

    if ($id) {

        $stmt = $conn->prepare("DELETE FROM students WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "Record deleted successfully!";
        header("Location: dashboard.php");
        exit;
    } 


