<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPLANT_SHOP - เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
        <a href="index.php"><img src="./img/PPLANT_LOGO-removebg-preview.png" alt="PPLANT_SHOP"></a>
    </header>
<main>
    <div class="login-form">
        <h1>เข้าสู่ระบบ</h1>
        <form action="login.php" method="post">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">รหัสผ่าน:</label>
            <input type="password" name="password" id="password" required>
            <select name="role" id="role">
                <option value="user">ผู้ใช้ทั่วไป</option>
                <option value="employee">พนักงาน</option>
            </select>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <p>หากไม่มีบัญชี <a href="register.php">สมัครสมาชิก</a> ได้ที่นี่</p>
    </div>
</body>
</main>
<footer>
        <p>Copyright © 2023 PPLANT_SHOP. All rights reserved.</p>
        <ul>
            <li><a href="https://www.facebook.com/pplantshop"><img src="facebook-icon.png" alt="facebook icon"></a></li>
            <li><a href="https://www.instagram.com/pplantshop"><img src="instagram-icon.png" alt="instagram icon"></a></li>
            <li><a href="https://line.me/R/ti/p/%40pplantshop"><img src="line-icon.png" alt="line icon"></a></li>
        </ul>
    </footer>
</html>
<?php

@$username = $_POST['username'];
@$password = $_POST['password'];
@$role = $_POST['role'];


$db = new PDO('mysql:host=localhost;dbname=pplant_db', 'root', '');

if ($role === 'user') {
    $stmt = $db->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        setcookie('username',$user['username'],time()+ 60*60*24*30,'/');
        header('Location: index.php');
    } else {
        $moniter= "<p style='color: red;'>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</p>";
    }
} else if ($role === 'employee') {
    $stmt = $db->query("SELECT * FROM employees WHERE username='$username' AND password='$password'");
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($employee) {
        // เข้าสู่ระบบสำหรับพนักงาน
        session_start();
        $_SESSION['employee_id'] = $employee['id'];
        $_SESSION['username'] = $employee['username'];
        setcookie('username',$employee['username'],time()+ 60*60*24*30,'/');
        header('Location: home_admin.php');
    } else {
        $moniter= "<p style='color: red;'>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</p>";
    }
} else {
    echo "<p style='color: red;'>กรุณาเลือกประเภทผู้ใช้</p>";
}


?>
