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
$stmt = $db->query('SELECT * FROM employees');
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
//เพิ่ม
if (isset($_POST['add'])) {

    // ดึงข้อมูลจากฟอร์ม
    $employee_id = $_POST['employee_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    // ตรวจสอบชื่อผู้ใช้ซ้ำ
    $stmt = $db->prepare('SELECT * FROM employees WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // เพิ่มพนักงานใหม่
    if (!$existing_user) {
        $stmt = $db->prepare('INSERT INTO employees (employee_id,username, password, email, position)
         VALUES (:employee_id,:username, :password, :email, :position)');
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':position', $position);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">เพิ่มพนักงานใหม่สำเร็จ</p>';
    } else {
        // แจ้งเตือนชื่อผู้ใช้ซ้ำ
        echo '<p style="color: red;">ชื่อผู้ใช้ซ้ำ กรุณาลองใหม่</p>';
    }
}
//ลบ
if (isset($_POST['delete'])) {

    // ดึงรหัสพนักงาน
    $employee_id = $_POST['employee_id'];

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    $stmt = $db->prepare('SELECT * FROM employees WHERE employee_id = :employee_id');
    $stmt->bindParam(':employee_id', $employee_id);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    // ลบพนักงาน
    if ($employee) {
        $stmt = $db->prepare('DELETE FROM employees WHERE employee_id = :employee_id');
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">ลบพนักงานสำเร็จ</p>';
    } else {
        // แจ้งเตือนไม่พบพนักงาน
        echo '<p style="color: red;">ไม่พบพนักงาน กรุณาลองใหม่</p>';
    }
}

if (isset($_POST['edit'])) {

    // ดึงรหัสพนักงาน
    $employee_id = $_POST['employee_id'];

    // ดึงข้อมูลพนักงาน
    $stmt = $db->prepare('SELECT * FROM employees WHERE employee_id = :employee_id');
    $stmt->bindParam(':employee_id', $employee_id);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    if ($employee) {

        // แสดงฟอร์มแก้ไข
        echo '<form method="post" action="employee_admin.php">';
        echo '<input type="hidden" name="employee_id" value="' . $employee['employee_id'] . '">';
        echo '<label for="username">ชื่อผู้ใช้:</label>';
        echo '<input type="text" name="username" id="username" value="' . $employee['username'] . '">';
        echo '<br>';
        echo '<label for="password">รหัสผ่าน:</label>';
        echo '<input type="password" name="password" id="password" value="' . $employee['password'] . '">';
        echo '<br>';
        echo '<label for="email">อีเมล:</label>';
        echo '<input type="email" name="email" id="email" value="' . $employee['email'] . '">';
        echo '<br>';
        echo '<label for="position">ตำแหน่ง:</label>';
        echo '<select name="position" id="position">';
        echo '<option value="พนักงานทั่วไป" ' . ($employee['position'] === 'พนักงานทั่วไป' ? 'selected' : '') . '>พนักงานทั่วไป</option>';
        echo '<option value="หัวหน้างาน" ' . ($employee['position'] === 'หัวหน้างาน' ? 'selected' : '') . '>หัวหน้างาน</option>';
        echo '<option value="ผู้จัดการ" ' . ($employee['position'] === 'ผู้จัดการ' ? 'selected' : '') . '>ผู้จัดการ</option>';
        echo '<option value="admin" ' . ($employee['position'] === 'admin' ? 'selected' : '') . '>adminร</option>';
        echo '</select>';
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
    $employee_id = $_POST['employee_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    // อัปเดตข้อมูลพนักงาน
    $stmt = $db->prepare('UPDATE employees SET username = :username, password = :password, email = :email, position = :position WHERE employee_id = :employee_id');
    $stmt->bindParam(':employee_id', $employee_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':position', $position);
    $stmt->execute();

    // แจ้งเตือนสำเร็จ
    echo '<p style="color: green;">อัปเดตข้อมูลพนักงานสำเร็จ</p>';
}
header('employee_admin.php');
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
        <form method="post" action="employee_admin.php">
            <label for="employee_id">รหัสพนักงาน:</label>
            <input type="text" name="employee_id" id="employee_id">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" name="username" id="username">
            <br>
            <label for="password">รหัสผ่าน:</label>
            <input type="password" name="password" id="password">
            <br>
            <label for="email">อีเมล:</label>
            <input type="email" name="email" id="email">
            <br>
            <label for="position">ตำแหน่ง:</label>
            <select name="position" id="position">
                <option value="พนักงานทั่วไป">พนักงานทั่วไป</option>
                <option value="หัวหน้างาน">หัวหน้างาน</option>
                <option value="ผู้จัดการ">ผู้จัดการ</option>
            </select>
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
                        <th>รหัสพนักงาน</th>
                        <th>ชื่อ</th>
                        <th>รหัส</th>
                        <th>อีเมล</th>
                        <th>ตำแหน่ง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee) : ?>
                        <tr>
                            <td><?php echo $employee['employee_id']; ?></td>
                            <td><?php echo $employee['username']; ?></td>
                            <td><?php echo $employee['password']; ?></td>
                            <td><?php echo $employee['email']; ?></td>
                            <td><?php echo $employee['position']; ?></td>
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