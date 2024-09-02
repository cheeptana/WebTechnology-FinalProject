<?php
$db = new PDO('mysql:host=localhost;dbname=pplant_db', 'root', '');

$stmt = $db->query('SELECT * FROM plant');
$plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPLANT_SHOP - สินค้า</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <a href="index.html"><img src="./img/PPLANT_LOGO-removebg-preview.png" alt="PPLANT_SHOP"></a>
        <ul>
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="product.php">สินค้า</a></li>
            <li><a href="profile.php">ข้อมูลส่วนตัว</a></li>
            <li><a href="login.php">ออกจากระบบ</a></li>
        </ul>
    </header>
    <main>
        <form action="product.php" method="post">
            <input type="text" name="search" placeholder="ค้นหา...">
            <button type="submit">ค้นหา</button>
        </form>

        <?php
        error_reporting( );
        @$search = $_POST['search'];
        $stmt = $db->query("SELECT * FROM plant WHERE name_plant LIKE '%$search%'");
        $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <section class="product">
            <h2>รายการพืชทั้งหมด</h2>
            <div class="product-list">
                <?php foreach ($plants as $plant) : ?>
                    <div class="product-card">
                        <img src="./img/<?php echo $plant['img_plant']; ?>" alt="<?php echo $plant['name_plant']; ?>">
                        <h3><?php echo $plant['name_plant']; ?></h3>
                        <p><?php echo $plant['description_plant']; ?></p>
                        <p>ราคา: <?php echo number_format($plant['price_plant'], 2); ?> บาท</p>
                    </div>
                <?php endforeach; ?>
            </div>
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