
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

try {
   
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $id = $_GET['id'] ?? null;

    if ($id) {
        // Retrieve the record
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            echo "Student not found!";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fname = $_POST['fname'] ?? $student['fname'];
            $mname = $_POST['mname'] ?? $student['mname'];
            $lname = $_POST['lname'] ?? $student['lname'];
            $sex = $_POST['sex'] ?? $student['sex'];

         
            $updateStmt = $conn->prepare("UPDATE students SET fname = :fname, mname = :mname, lname = :lname, sex = :sex WHERE id = :id");
            $updateStmt->execute([
                ':fname' => $fname,
                ':mname' => $mname,
                ':lname' => $lname,
                ':sex' => $sex,
                ':id' => $id
            ]);

            echo "Record updated successfully!";
          
            header("Location: dashboard.php");
            exit;
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Update Student</title>
</head>
<body>
    <div class="login">
        <form action="update.php?id=<?= htmlspecialchars($id) ?>" method="POST">
            <input type="text" name="fname" value="<?= htmlspecialchars($student['fname']) ?>" placeholder="First Name" required><br>
            <input type="text" name="mname" value="<?= htmlspecialchars($student['mname']) ?>" placeholder="Middle Name"><br>
            <input type="text" name="lname" value="<?= htmlspecialchars($student['lname']) ?>" placeholder="Last Name" required><br>
            <select name="sex" required>
                <option value="male" <?= $student['sex'] === 'male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= $student['sex'] === 'female' ? 'selected' : '' ?>>Female</option>
            </select><br>
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
