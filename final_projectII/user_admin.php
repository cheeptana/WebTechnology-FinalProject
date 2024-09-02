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
<?php
$db = new PDO('mysql:host=localhost;dbname=pplant_db', 'root', '');
$stmt = $db->query('SELECT * FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
//เพิ่ม
if (isset($_POST['add'])) {

    // ดึงข้อมูลจากฟอร์ม
    $user_id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // ตรวจสอบชื่อผู้ใช้ซ้ำ
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // เพิ่มพนักงานใหม่
    if (!$existing_user) {
        $stmt = $db->prepare('INSERT INTO users (username, password, email) 
        VALUES (:username, :password, :email)');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">เพิ่มผู้ใช้ใหม่สำเร็จ</p>';
        header('location:user_admin.php');
    } else {
        // แจ้งเตือนชื่อผู้ใช้ซ้ำ
        echo '<p style="color: red;">ชื่อผู้ใช้ซ้ำ กรุณาลองใหม่</p>';
    }
}
//ลบ
if (isset($_POST['delete'])) {

    // ดึงรหัสพนักงาน
    $user_id = $_POST['id'];

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ลบพนักงาน
    if ($user) {
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">ลบพนักงานสำเร็จ</p>';
        header('location:user_admin.php');
    } else {
        // แจ้งเตือนไม่พบพนักงาน
        echo '<p style="color: red;">ไม่พบพนักงาน กรุณาลองใหม่</p>';
    }
}

if (isset($_POST['edit'])) {

    // ดึงรหัสพนักงาน
    $user_id = $_POST['id'];

    // ดึงข้อมูลพนักงาน
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    if ($user) {

        // แสดงฟอร์มแก้ไข
        echo '<form method="post" action="user_admin.php">';
        echo '<input type="hidden" name="id" value="' . $user['id'] . '">';
        echo '<label for="username">ชื่อผู้ใช้:</label>';
        echo '<input type="text" name="username" id="username" value="' . $user['username'] . '">';
        echo '<br>';
        echo '<label for="password">รหัสผ่าน:</label>';
        echo '<input type="password" name="password" id="password" value="' . $user['password'] . '">';
        echo '<br>';
        echo '<label for="email">อีเมล:</label>';
        echo '<input type="email" name="email" id="email" value="' . $user['email'] . '">';
        echo '<br>';
        echo '<button type="submit" name="update">อัปเดตข้อมูล</button>';
        echo '</form>';
    } else {
        // แจ้งเตือนไม่พบพนักงาน
        echo '<p style="color: red;">ไม่พบพนักงาน กรุณาลองใหม่</p>';
    }
}

// อัปเดตข้อมูล
if (isset($_POST['update'])) {

    // ดึงข้อมูลจากฟอร์ม
    $user_id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // อัปเดตข้อมูลพนักงาน
    $stmt = $db->prepare('UPDATE users SET username = :username, password = :password, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $user_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // แจ้งเตือนสำเร็จ
    echo '<p style="color: green;">อัปเดตข้อมูลพนักงานสำเร็จ</p>';
    header('location:user_admin.php');
}
header('user_admin.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ADMIN-employee</title>
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
        <form method="post" action="user_admin.php">
            <label for="id">รหัสผู้ใช้:</label>
            <input type="text" name="id" id="id">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" name="username" id="username">
            <br>
            <label for="password">รหัสผ่าน:</label>
            <input type="password" name="password" id="password">
            <br>
            <label for="email">อีเมล:</label>
            <input type="email" name="email" id="email">
            <br>
            <button type="submit" name="add">เพิ่มพนักงาน</button>
            <button type="submit" name="delete">ลบพนักงาน</button>
            <button type="submit" name="edit">แก้ไขพนักงาน</button>
        </form>
        <section>
            <h1>ข้อมูลพนักงาน</h1>

            <table class="table">
                <thead>
                    <tr>
                        <th>รหัสผู้ใช้</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>รหัส</th>
                        <th>อีเมล</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['password']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
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