<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

// ดึงข้อมูลผู้ใช้
$username = $_SESSION['username'];
$db = new PDO('mysql:host=localhost;dbname=pplant_db', 'root', '');
$stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

// ฟังก์ชันอัปเดตข้อมูล
if (isset($_POST['update'])) {
  $name = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // ตรวจสอบข้อมูล
  if (empty($name) || empty($email) || empty($password)) {
    echo '<p style="color: red;">กรุณากรอกข้อมูลให้ครบถ้วน</p>';
  } else {
    // อัปเดตข้อมูล
    $stmt = $db->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE username = ?');
    $stmt->execute([$name, $email, $password, $username]);

    echo '<p style="color: green;">อัปเดตข้อมูลสำเร็จ</p>';
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แก้ไขข้อมูลส่วนตัว</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Plant Shop</h1>
    <ul>
      <li><a href="index.php">หน้าแรก</a></li>
      <li><a href="product.php">สินค้า</a></li>
      <li><a href="profile.php">ข้อมูลส่วนตัว</a></li>
      <li><a href="login.php">ออกจากระบบ</a></li>
    </ul>
  </header>
  <main>
    <h2>แก้ไขข้อมูลส่วนตัว</h2>
    <form method="post">
      <label for="name">ชื่อ-นามสกุล:</label>
      <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>">
      <br>
      <label for="email">อีเมล:</label>
      <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>">
      <br>
      <label for="password">รหัสผ่าน:</label>
      <input type="password" name="password" id="password">
      <br><br>
      <input type="submit" name="update" value="อัปเดตข้อมูล">
    </form>
  </main>
  <footer>
        <p>Copyright © 2023 PPLANT_SHOP. All rights reserved.</p>
        <ul>
            <li><a href="https://www.facebook.com/pplantshop"><img src="facebook-icon.png" alt="facebook icon"></a></li>
            <li><a href="https://www.instagram.com/pplantshop"><img src="instagram-icon.png" alt="instagram icon"></a></li>
            <li><a href="https://line.me/R/ti/p/%40pplantshop"><img src="line-icon.png" alt="line icon"></a></li>
        </ul>
    </footer>
</body>
</html>
