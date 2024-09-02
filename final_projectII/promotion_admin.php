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
$stmt = $db->query('SELECT * FROM promotion');
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
//เพิ่ม
if (isset($_POST['add'])) {

    // ดึงข้อมูลจากฟอร์ม
    $id = $_POST['id_pro'];
    $name = $_POST['name_pro'];
    $img = $_POST['img_pro'];
    $datail = $_POST['datail_pro'];

    // ตรวจสอบชื่อผู้ใช้ซ้ำ
    $stmt = $db->prepare('SELECT * FROM promotion WHERE name_pro = :name_pro');
    $stmt->bindParam(':name_pro', $name);
    $stmt->execute();
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // เพิ่มพนักงานใหม่
    if (!$existing_user) {
        $stmt = $db->prepare('INSERT INTO promotion (name_pro, img_pro,datail_pro)
         VALUES (:name_pro, :img_pro, :datail_pro)');
        $stmt->bindParam(':id_pro', $id);
        $stmt->bindParam(':name_pro', $name);
        $stmt->bindParam(':img_pro', $img);
        $stmt->bindParam(':datail_pro', $datail);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">เพิ่มพนักงานใหม่สำเร็จ</p>';
        header('location:promotion_admin.php');
    } else {
        // แจ้งเตือนชื่อผู้ใช้ซ้ำ
        echo '<p style="color: red;">ชื่อผู้ใช้ซ้ำ กรุณาลองใหม่</p>';
    }
}
//ลบ
if (isset($_POST['delete'])) {

    // ดึงรหัสพนักงาน
    $id = $_POST['id_pro'];

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    $stmt = $db->prepare('SELECT * FROM promotion WHERE id_pro = :id_pro');
    $stmt->bindParam(':id_pro', $id);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    // ลบพนักงาน
    if ($employee) {
        $stmt = $db->prepare('DELETE FROM promotion WHERE id_pro = :id_pro');
        $stmt->bindParam(':id_pro', $id);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">ลบพนักงานสำเร็จ</p>';
        header('protion_admin.php');
    } else {
        // แจ้งเตือนไม่พบพนักงาน
        echo '<p style="color: red;">ไม่พบพนักงาน กรุณาลองใหม่</p>';
    }
}

if (isset($_POST['edit'])) {

    // ดึงรหัสพนักงาน
    $id = $_POST['id_pro'];

    // ดึงข้อมูลพนักงาน
    $stmt = $db->prepare('SELECT * FROM promotion WHERE id_pro = :id_pro');
    $stmt->bindParam(':id_pro', $id);
    $stmt->execute();
    $promotion = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    if ($promotion) {

        // แสดงฟอร์มแก้ไข
        echo '<form method="post" action="promotion_admin.php">';
        echo '<input type="hidden" name="id_pro" value="' . $promotion['id_pro'] . '">';
        echo '<label for="name_pro">ชื่อโปรโมชั่น:</label>';
        echo '<input type="text" name="name_pro" id="name_pro" value="' . $promotion['name_pro'] . '">';
        echo '<br>';
        echo '<label for="img_pro">ภาพโปรโมชั่น:</label>';
        echo '<input type="text" name="img_pro" id="img_pro" value="' . $promotion['img_pro'] . '">';
        echo '<br>';
        echo '<label for="datail_pro">รายละเอียดโปรโมชั่น:</label>';
        echo '<input type="text" name="datail_pro" id="datail_pro" value="' . $promotion['datail_pro'] . '">';
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
    $id = $_POST['id_pro'];
    $name = $_POST['name_pro'];
    $img  = $_POST['img_pro'];
    $detail = $_POST['datail_pro'];

    // อัปเดตข้อมูลพนักงาน
    $stmt = $db->prepare('UPDATE promotion SET name_pro = :name_pro, img_pro = :img_pro, datail_pro = :datail_pro 
    WHERE id_pro =  :id_pro');
    $stmt->bindParam(':id_pro', $id);
    $stmt->bindParam(':name_pro', $name);
    $stmt->bindParam(':img_pro', $img);
    $stmt->bindParam(':datail_pro', $datail);
    $stmt->execute();

    // แจ้งเตือนสำเร็จ
    echo '<p style="color: green;">อัปเดตข้อมูลพนักงานสำเร็จ</p>';
    header('promotion_admin.php');
}

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
        <form method="post" action="promotion_admin.php">
            <label for="id_pro">รหัสโปรโมชั่น:</label>
            <input type="text" name="id_pro" id="id_pro">
            <label for="name_pro">ชื่อโปรโมชั่น:</label>
            <input type="text" name="name_pro" id="name_pro">
            <br>
            <label for="img_pro">ภาพโปรโมชั่น:</label>
            <input type="text" name="img_pro" id="img_pro">
            <br>
            <label for="datail_pro">รายละเอียดโปรโมชั่น:</label>
            <input type="text" name="datail_pro" id="datail_pro">
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
                        <th>รหัสโปรโมชั่น</th>
                        <th>ชื่อโปรโมชั่น</th>
                        <th>ภาพโปรโมชั่น</th>
                        <th>รายละเอียดโปรโมชั่น</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee) : ?>
                        <tr>
                            <td><?php echo $employee['id_pro']; ?></td>
                            <td><?php echo $employee['name_pro']; ?></td>
                            <td><?php echo $employee['img_pro']; ?></td>
                            <td><?php echo $employee['datail_pro']; ?></td>

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