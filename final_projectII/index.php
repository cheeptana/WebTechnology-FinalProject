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
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="product.php">สินค้า</a></li>
            <li><a href="profile.php">ข้อมูลส่วนตัว</a></li>
            <?php
            if (isset($_SESSION['username'])) {
                        echo '<li class="logged-in"><form method="post" action="index.php"><button type="submit" name="logout" class="logout-button">ออกจากระบบ</button></form></li>'; 
                    } else {
                        echo '<li><a href="login.php">เข้าสู่ระบบ</a></li>';
                    }
                    ?>
        </ul>
    </header>


    <main>
        <section class="hero">
            <div class="hero-text">
            <h2>ยินดีต้อนรับสู่ PPLANT_SHOP<?php 
            if(isset($_SESSION['username'])) {
            echo "คุณ".$username;
            }
             ?></h2>
                <p>แหล่งรวมต้นไม้คุณภาพ บริการดีเยี่ยม ราคาเป็นธรรม</p>
                <a href="promotion.html">เลือกซื้อต้นไม้</a>
            </div>
        </section>

        <section class="promotion">
            <h2>โปรโมชั่นแนะนำ</h2>
            <table>
                <tr>
                    <?php
                    for ($i = 0; $i < $promotionCount; $i++) {
                        echo '<td>';
                        echo '<div class="promotion-card">';
                        echo '<img src="./img/' . $promotions[$i]['img_pro'] . '" alt="' . $promotions[$i]['name_pro'] . '">';
                        echo '</div>';
                        echo '</td>';
                    }
                    ?>
                <tr>
            </table>
        </section>

        <section class="services">
            <h2>บริการของเรา</h2>
            <div class="services-list">
                <div class="service-card">
                    <img src="./img/service01.jpg" alt="service image">
                    <h3>คัดสรรอย่างพิถีพิถัน</h3>
                    <p>ต้นไม้ทุกต้นผ่านการดูแลอย่างประณีต มั่นใจได้ว่าต้นไม้ของคุณจะเติบโตอย่างสวยงาม เปี่ยมชีวิตชีวา</p>
                </div>
                <div class="service-card">
                    <img src="./img/service02.jpg" alt="service image">
                    <h3>อาณาจักรแห่งความหลากหลาย</h3>
                    <p>ไม้ใบ ไม้ดอก ไม้ประดับ ไม้มงคล ไม้หายาก อุปกรณ์สำหรับต้นไม้ ครบครันในที่เดียว</p>
                </div>
                <div class="service-card">
                    <img src="./img/service03.jpg" alt="service image">
                    <h3>บริการที่ประทับใจ</h3>
                    <p>จัดส่งรวดเร็ว ทั่วประเทศ บรรจุอย่างดี ให้คำปรึกษาเกี่ยวกับการเลือก และ ดูแลต้นไม้ ด้วยความใส่ใจ</p>
                </div>
                <div class="service-card">
                    <img src="./img/service04.jpg" alt="service image">
                    <h3>ราคาที่ย่อมเยา</h3>
                    <p>คุ้มค่ากับคุณภาพ สินค้าราคาเป็นธรรม มีโปรโมชั่นและส่วนลดพิเศษ</p>
                </div>
            </div>
        </section>

        <section class="testimonials">
            <h2>รีวิวจากลูกค้า</h2>
            <div class="testimonials-list">
                <div class="testimonial">
                    <img src="customer-1.jpg" alt="customer image">
                    <blockquote>
                        "ต้นไม้สวยมาก สุขภาพดี จัดส่งรวดเร็ว ประทับใจมากค่ะ"
                    </blockquote>
                    <p>- คุณ A</p>
                </div>
                <div class="testimonial">
                    <img src="customer-2.jpg" alt="customer image">
                    <blockquote>
                        "บริการดีมาก ให้คำปรึกษาอย่างละเอียด แนะนำร้านนี้เลยค่ะ"
                    </blockquote>
                    <p>- คุณ B</p>
                </div>
                <div class="testimonial">
                    <img src="customer-3.jpg" alt="customer image">
                    <blockquote>
                        "ราคาสินค้าเป็นธรรม สินค้ามีคุณภาพ บริการดีเยี่ยม"
                    </blockquote>
                    <p>- คุณ C</p>
                </div>
            </div>
        </section>

        <section class="contact">
            <h2>ติดต่อเรา</h2>
            <div class="contact-form">
                <form action="#">
                    <input type="text" placeholder="ชื่อ">
                    <input type="email" placeholder="อีเมล">
                    <input type="tel" placeholder="เบอร์โทรศัพท์">
                    <textarea placeholder="ข้อความ"></textarea>
                    <button type="submit">ส่งข้อความ</button>
                </form>
            </div>
            <div class="contact-info">
                <p>เบอร์โทรศัพท์: 081-234-5678</p>
                <p>อีเมล: info@pplant_shop.com</p>
                <p>ที่อยู่: 123 ถนนสุขุมวิท กรุงเทพฯ</p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.535344183364!2d100.5234275147964!3d13.731339290234144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e299b366021349:0x1959249974882874!2s123+Sukhumvit+Rd!5e0!3m2!1sth!2s!4v1646422350822!5m2!1sth!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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
