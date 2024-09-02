<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPLANT_SHOP - สมัครสมาชิกพนักงาน</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
        <a href="index.php"><img src="./img/PPLANT_LOGO-removebg-preview.png" alt="PPLANT_SHOP"></a>
    </header>
    <main>
    <div class="register-form">
        <h1>สมัครสมาชิก</h1>
        <form action="register.php" method="post">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" name="username" id="username" required>
            <label for="email">อีเมล:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">รหัสผ่าน:</label>
            <input type="password" name="password" id="password" required>
            <label for="confirm_password">ยืนยันรหัสผ่าน:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <p>มีบัญชีอยู่แล้ว? <a href="login.php">ไปสู่หน้าลงชื่อเข้าใช้</a></p> 
            <button type="submit">สมัครสมาชิก</button>
        </form>
        
    </div>
    </main>
</body>
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
@$email = $_POST['email'];
@$password = $_POST['password'];
@$confirm_password = $_POST['confirm_password'];


// เชื่อมต่อกับฐานข้อมูล
$db = new PDO('mysql:host=localhost;dbname=pplant_db', 'root', '');

// ตรวจสอบความถูกต้องของข้อมูล
if ($password !== $confirm_password) {
    echo "<p style='color: red;'>รหัสผ่านไม่ตรงกัน</p>";
} else {
    // ตรวจสอบว่า username นี้มีอยู่แล้วหรือไม่
    $stmt = $db->query("SELECT * FROM users WHERE username='$username'");
    $users = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($users) {
        echo "<p style='color: red;'>ชื่อผู้ใช้นี้ถูกใช้งานแล้ว</p>";
    } else {
        // เพิ่มพนักงานใหม่
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);

        echo "<p style='color: green;'>สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ</p>";
    }
}

?>