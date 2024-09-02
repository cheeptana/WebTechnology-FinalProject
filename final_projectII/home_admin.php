<?php
session_start();

if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    $username = '';
}

if (isset($_POST['logout'])) {
    setcookie('username', '', time() - 3600, '/');
    unset($_SESSION['username']);
    $username = '';
}
?>
<!DOCTYPE html>
<html lang="th">
<?php
$db = new PDO('mysql:host=localhost;dbname=pplant_db', 'root', '');
$stmt = $db->query('SELECT * FROM promotion');
$promotionCount = $stmt->rowCount();
$promotions = $stmt->fetchAll();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPLANT_SHOP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <a href="index.php"><img src="./img/PPLANT_LOGO-removebg-preview.png" alt="PPLANT_SHOP"></a>
        <ul>
            <li><a href="home_admin.php">หน้าหลัก</a></li>
            <li><a href="product_admin.php">ผลิตภัณท์</a></li>
            <li><a href="promotion_admin.php">โปรโมชั่น</a></li>
            <li><a href="user_admin.php">บัญชีผู้ใช้</a></li>
            <li><a href="employee_admin.php">บัญชีพนักงาน</a></li>
            <?php
            if (isset($_SESSION['username'])) {
                echo '<li class="logged-in"><form method="post" 
                action="index.php"><button type="submit" name="logout" 
                class="logout-button">ออกจากระบบ</button></form></li>';
            } else {
                echo '<li><a href="login.php">เข้าสู่ระบบ</a></li>';
            }
            ?>
        </ul>
    </header>

    <main>
        <? echo "dashbord"; ?>
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