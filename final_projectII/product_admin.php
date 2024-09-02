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
$stmt = $db->query('SELECT * FROM plant');
$plant = $stmt->fetchAll(PDO::FETCH_ASSOC);
//เพิ่ม
if (isset($_POST['add'])) {

    // ดึงข้อมูลจากฟอร์ม
    $plant_id = $_POST['id_plant'];
    $name_plant = $_POST['name_plant'];
    $price_plant = $_POST['price_plant'];
    $detail = $_POST['description_plant '];
    $img = $_POST['img_plant'];

    // ตรวจสอบชื่อผู้ใช้ซ้ำ
    $stmt = $db->prepare('SELECT * FROM plant WHERE name_plant = :name_plant');
    $stmt->bindParam(':name_plant', $name_plant);
    $stmt->execute();
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // เพิ่มพนักงานใหม่
    if (!$existing_user) {
        $stmt = $db->prepare('INSERT INTO plant (id_plant,name_plant, price_plant, description_plant, img_plant) VALUES (:id_plant,:name_plant, :price_plant, :description_plant, :img_plant)');
        $stmt->bindParam(':id_plant', $plant_id);
        $stmt->bindParam(':name_plant', $name_plant);
        $stmt->bindParam(':price_plant', $price_plant);
        $stmt->bindParam(':description_plant', $detail);
        $stmt->bindParam(':img_plant', $img);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">เพิ่มพนักงานใหม่สำเร็จ</p>';
        header('location:product_admin.php');
    } else {
        // แจ้งเตือนชื่อผู้ใช้ซ้ำ
        echo '<p style="color: red;">ชื่อผู้ใช้ซ้ำ กรุณาลองใหม่</p>';
    }
}
//ลบ
if (isset($_POST['delete'])) {

    // ดึงรหัสพนักงาน
    $plant_id = $_POST['id_plant'];

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    $stmt = $db->prepare('SELECT * FROM plant WHERE id_plant = :id_plant');
    $stmt->bindParam(':id_plant', $plant_id);
    $stmt->execute();
    $plant = $stmt->fetch(PDO::FETCH_ASSOC);

    // ลบพนักงาน
    if ($plant) {
        $stmt = $db->prepare('DELETE FROM plant WHERE id_plant = :id_plant');
        $stmt->bindParam(':id_plant', $plant_id);
        $stmt->execute();

        // แจ้งเตือนสำเร็จ
        echo '<p style="color: green;">ลบพนักงานสำเร็จ</p>';
        header('location:product_admin.php');
    } else {
        // แจ้งเตือนไม่พบพนักงาน
        echo '<p style="color: red;">ไม่พบพนักงาน กรุณาลองใหม่</p>';
    }
}

if (isset($_POST['edit'])) {

    // ดึงรหัสพนักงาน
    $plant_id = $_POST['id_plant'];

    // ดึงข้อมูลพนักงาน
    $stmt = $db->prepare('SELECT * FROM plant WHERE id_plant = :id_plant');
    $stmt->bindParam(':id_plant', $plant_id);
    $stmt->execute();
    $plant = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพนักงานมีอยู่จริง
    if ($plant) {

        // แสดงฟอร์มแก้ไข
        echo '<form method="post" action="product_admin.php">';
        echo '<input type="hidden" name="plant_id" value="' . $plant['id_plant'] . '">';
        echo '<label for="name_plant">ชื่อสินค้า:</label>';
        echo '<input type="text" name="name_plant" id="name_plant" value="' . $plant['name_plant'] . '">';
        echo '<br>';
        echo '<label for="price_plant">ราคาสินค้า:</label>';
        echo '<input type="number" name="price_plant" id="price_plant" value="' . $plant['price_plant'] . '">';
        echo '<br>';
        echo '<label for="description_plant ">รายละเอียดสสินค้า:</label>';
        echo '<input type="text" name="description_plant " id="description_plant " value="' . $plant['description_plant'] . '">';
        echo '<br>';
        echo '<label for="img_plant  ">ภาพสินค้า:</label>';
        echo '<input type="text" name="img_plant" id="img_plant  " value="' . $plant['img_plant'] . '">';
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
    $plant_id = $_POST['id_plant'];
    $name_plant = $_POST['name_plant'];
    $price_plant = $_POST['price_plant'];
    $detail = $_POST['description_plant '];
    $img = $_POST['img_plant'];

    // อัปเดตข้อมูลพนักงาน
    $stmt = $db->prepare('UPDATE plant SET name_plant = :name_plant, price_plant = :price_plant, description_plant = :description_plant,img_plant=:img_plant
     WHERE id_plant = :id_plant');
    $stmt->bindParam(':id_plant', $plant_id);
    $stmt->bindParam(':name_plant', $name_plant);
    $stmt->bindParam(':price_plant', $price_plant);
    $stmt->bindParam(':description_plant', $detail);
    $stmt->bindParam(':img_plant', $img);
    $stmt->execute();

    // แจ้งเตือนสำเร็จ
    echo '<p style="color: green;">อัปเดตข้อมูลพนักงานสำเร็จ</p>';
    header('location:product_admin.php');
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
        <form method="post" action="product_admin.php">
            <label for="id_plant">รหัสสินค้า:</label>
            <input type="text" name="id_plant" id="id_plant">
            <label for="name_plant">ชื่อสินค้า:</label>
            <input type="text" name="name_plant" id="name_plant">
            <br>
            <label for="price_plant">ราคาสินค้า:</label>
            <input type="ntext" name="price_plant" id="price_plant">
            <br>
            <label for="description_plant">รายละเอียดสินค้า:</label>
            <input type="text" name="description_plant" id="description_plant">
            <br>
            <label for="img_plant">ภาพสินค้า:</label>
            <input type="text" name="img_plant" id="img_plant">
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
                        <th>ชื่อพนักงาน</th>
                        <th>รหัส</th>
                        <th>อีเมล</th>
                        <th>ตำแหน่ง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plant as $plants) : ?>
                        <tr>
                            <td><?php echo $plants['id_plant']; ?></td>
                            <td><?php echo $plants['name_plant']; ?></td>
                            <td><?php echo $plants['price_plant']; ?></td>
                            <td><?php echo $plants['description_plant']; ?></td>
                            <td><?php echo $plants['img_plant']; ?></td>
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