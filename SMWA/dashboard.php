<?php
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$name = htmlspecialchars($_SESSION['user']);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";





try {
   
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fname = $_POST['fname'] ?? null;
        $mname = $_POST['mname'] ?? null;
        $lname = $_POST['lname'] ?? null;
        $sex = $_POST['sex'] ?? null;

        if ($fname && $lname && $sex) {
           
            $stmt = $conn->prepare("INSERT INTO students (fname, mname, lname, sex) VALUES (:fname, :mname, :lname, :sex)");
            $stmt->execute([':fname' => $fname, ':mname' => $mname, ':lname' => $lname, ':sex' => $sex]);
            echo "New record created successfully";
        } else {
            echo "Please fill in all required fields.";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Student Management</title>
</head>


<body>

    <div class="sidebar">
        
        <div class="profile">
          
        <div class="name"><?php echo $name; ?> (Admin)</div>
            <div class="status">Online</div>
        </div>

        <div class="menu">

            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="navbar">
            <i class="fas fa-bars"></i>
        </div>
        <div class="main-content">
        <div class="dashboard">
        <form action="dashboard.php" method="POST">
            <input type="text" name="fname" placeholder="First Name" required><br>
            <input type="text" name="mname" placeholder="Middle Name"><br>
            <input type="text" name="lname" placeholder="Last Name" required><br>
            <select name="sex" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select><br>
            <input type="submit" value="Save">
        </form>

        <div>Student List</div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Sex</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                   
                    $stmt = $conn->query("SELECT * FROM students");
                    foreach ($stmt as $row) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['fname']) . "</td>
                            <td>" . htmlspecialchars($row['mname']) . "</td>
                            <td>" . htmlspecialchars($row['lname']) . "</td>
                            <td>" . htmlspecialchars($row['sex']) . "</td>
                            <td><a href='update.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a></td>
                            <td><a href='delete.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></td>
                        </tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='5'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
        </div>
    </div>

</body>
</html>



