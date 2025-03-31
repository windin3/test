<?php
/**
 * หน้าหลักของเว็บไซต์โรงเรียนเทพปัญญา
 * เวอร์ชัน: 1.0
 * วันที่อัปเดตล่าสุด: 30 มีนาคม 2568
 */

// กำหนดค่าเริ่มต้นสำหรับหน้านี้
$page_title = "หน้าหลัก";
$page_description = "โรงเรียนเทพปัญญา - สร้างความรู้คู่คุณธรรม นำไปสู่การพัฒนาที่ยั่งยืน";
$active_menu = "home";

// นำเข้าส่วน header
require_once 'includes/header.php';
?>

    <!-- เนื้อหาหลัก -->
    <main>
        <!-- สไลด์หลักเต็มหน้าจอ -->
        <?php 
        // แสดง SlideWidget
        // กำหนด category_id ของสไลด์ที่ต้องการแสดง (ในตัวอย่างนี้เราใช้ category_id = 1)
        $slide_category_id = 1;
        // กำหนดลำดับสไลด์ที่จะแสดงเป็นค่าเริ่มต้น (ในตัวอย่างนี้เราใช้ลำดับ 0)
        $active_slide_index = 0;
        echo $slideWidget->render($slide_category_id, $active_slide_index); 
        ?>

        <!-- ข่าวด่วน และลิงก์ด่วน -->
        <section class="announcement-section">
            <div class="container">
                <div class="grid-layout">
                    <!-- ข่าวด่วน / ประกาศสำคัญ -->
                    <div class="grid-item highlight-news">
                        <div class="section-header">
                            <h2><i class="fas fa-bullhorn"></i> ข่าวด่วน / ประกาศสำคัญ</h2>
                        </div>
                        <div class="news-marquee">
                            <div class="marquee-content">
                                <?php
                                // ดึงข้อมูลข่าวด่วนจากฐานข้อมูล (ตัวอย่าง)
                                $urgent_news = [
                                    ['date' => '15 มี.ค. 2568', 'text' => 'เปิดรับสมัครนักเรียนใหม่ ประจำปีการศึกษา 2568 วันที่ 1-30 เมษายน 2568', 'url' => '#'],
                                    ['date' => '10 มี.ค. 2568', 'text' => 'ประกาศผลสอบโครงการพัฒนาความเป็นเลิศทางวิชาการ ประจำปีการศึกษา 2568', 'url' => '#'],
                                    ['date' => '5 มี.ค. 2568', 'text' => 'ขอเชิญผู้ปกครองเข้าร่วมประชุมผู้ปกครองนักเรียน ประจำภาคเรียนที่ 2/2567 วันที่ 18 มีนาคม 2568', 'url' => '#'],
                                ];

                                foreach ($urgent_news as $news) :
                                ?>
                                <a href="<?php echo $news['url']; ?>" class="marquee-item">
                                    <span class="date"><?php echo htmlspecialchars($news['date']); ?></span>
                                    <span class="text"><?php echo htmlspecialchars($news['text']); ?></span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ลิงก์ด่วน -->
                    <div class="grid-item quick-links">
                        <div class="section-header">
                            <h2><i class="fas fa-link"></i> ลิงก์ด่วน</h2>
                        </div>
                        <div class="quick-link-icons">
                            <?php
                            // ตัวอย่างข้อมูลลิงก์ด่วน
                            $quick_links = [
                                ['icon' => 'fas fa-user-plus', 'text' => 'สมัครเรียน', 'url' => '#'],
                                ['icon' => 'fas fa-credit-card', 'text' => 'ชำระค่าเทอม', 'url' => '#'],
                                ['icon' => 'fas fa-file-alt', 'text' => 'ผลการเรียน', 'url' => '#'],
                                ['icon' => 'fas fa-calendar-alt', 'text' => 'ปฏิทิน', 'url' => '#'],
                                ['icon' => 'fas fa-book', 'text' => 'ห้องสมุด', 'url' => '#'],
                                ['icon' => 'fas fa-download', 'text' => 'เอกสาร', 'url' => '#'],
                                ['icon' => 'fas fa-chalkboard', 'text' => 'ห้องเรียนออนไลน์', 'url' => '#'],
                                ['icon' => 'fas fa-question-circle', 'text' => 'ช่วยเหลือ', 'url' => '#'],
                            ];

                            foreach ($quick_links as $link) :
                            ?>
                            <a href="<?php echo $link['url']; ?>" class="quick-link-item">
                                <i class="<?php echo $link['icon']; ?>"></i>
                                <span><?php echo htmlspecialchars($link['text']); ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ส่วนวิสัยทัศน์และพันธกิจ -->
        <? echo $visionMissionWidget->render(); ?>
        <? echo $statsWidget->render(); ?>
        <? echo $executivesWidget->render(); ?>

        <!-- ส่วนวิสัยทัศน์และพันธกิจ -->
        <section class="vision-mission-section">
            <div class="container">
                <div class="section-header-center">
                    <h2>วิสัยทัศน์และพันธกิจ</h2>
                    <p>แนวทางการจัดการศึกษาของโรงเรียนเทพปัญญา มุ่งสู่การเป็นสถานศึกษาชั้นนำที่มุ่งพัฒนาผู้เรียนให้มีคุณภาพ</p>
                </div>
                
                <div class="vision-mission-wrapper">
                    <!-- ส่วนซ้าย: วิสัยทัศน์และพันธกิจ -->
                    <div class="vision-mission-content">
                        <div class="vision-box scroll-reveal" data-delay="100">
                            <div class="vision-header">
                                <i class="fas fa-eye"></i>
                                <h3>วิสัยทัศน์</h3>
                            </div>
                            <div class="vision-content">
                                <p>"เป็นสถานศึกษาชั้นนำที่มุ่งพัฒนาผู้เรียนให้มีความเป็นเลิศทางวิชาการ มีคุณธรรม จริยธรรม และมีทักษะในศตวรรษที่ 21 สามารถแข่งขันได้ในระดับสากล"</p>
                                <p>โรงเรียนเทพปัญญามุ่งมั่นในการสร้างบรรยากาศแห่งการเรียนรู้ที่มีคุณภาพ พัฒนาหลักสูตรที่ตอบสนองต่อความต้องการของผู้เรียนและสังคม และสร้างเครือข่ายความร่วมมือทางการศึกษาทั้งในและต่างประเทศ</p>
                            </div>
                        </div>
                        
                        <div class="mission-box scroll-reveal" data-delay="300">
                            <div class="mission-header">
                                <i class="fas fa-tasks"></i>
                                <h3>พันธกิจ</h3>
                            </div>
                            <div class="mission-content">
                                <ul class="mission-list">
                                    <li>จัดการศึกษาที่มุ่งเน้นความเป็นเลิศทางวิชาการและพัฒนาทักษะในศตวรรษที่ 21</li>
                                    <li>ปลูกฝังคุณธรรม จริยธรรม และค่านิยมที่พึงประสงค์ให้แก่ผู้เรียน</li>
                                    <li>พัฒนาหลักสูตรและกระบวนการเรียนรู้ที่หลากหลายตอบสนองความต้องการของผู้เรียน</li>
                                    <li>จัดสภาพแวดล้อมและบรรยากาศที่เอื้อต่อการเรียนรู้อย่างมีความสุข</li>
                                    <li>ส่งเสริมการใช้เทคโนโลยีและนวัตกรรมในการจัดการเรียนการสอน</li>
                                    <li>สร้างเครือข่ายความร่วมมือกับผู้ปกครอง ชุมชน และองค์กรทั้งในและต่างประเทศ</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ส่วนขวา: ภาพผู้บริหาร -->
                    <div class="executives-slider">
                        <div class="executives-slider-wrapper">
                            <div class="executive-slide">
                                <div class="executive-image">
                                    <img src="images/principal.jpg" alt="ผู้อำนวยการโรงเรียนเทพปัญญา">
                                </div>
                                <div class="executive-info">
                                    <h3 class="executive-title">ดร.สมชาย รักการศึกษา</h3>
                                    <p class="executive-position">ผู้อำนวยการโรงเรียนเทพปัญญา</p>
                                </div>
                            </div>
                            
                            <div class="executive-slide">
                                <div class="executive-image">
                                    <img src="images/executive1.jpg" alt="รองผู้อำนวยการฝ่ายวิชาการ">
                                </div>
                                <div class="executive-info">
                                    <h3 class="executive-title">ดร.วิชิต ปัญญาชน</h3>
                                    <p class="executive-position">รองผู้อำนวยการฝ่ายวิชาการ</p>
                                </div>
                            </div>
                            
                            <div class="executive-slide">
                                <div class="executive-image">
                                    <img src="images/executive2.jpg" alt="รองผู้อำนวยการฝ่ายบริหารทั่วไป">
                                </div>
                                <div class="executive-info">
                                    <h3 class="executive-title">นางสาวนภา จันทร์สว่าง</h3>
                                    <p class="executive-position">รองผู้อำนวยการฝ่ายบริหารทั่วไป</p>
                                </div>
                            </div>
                            
                            <div class="executive-slide">
                                <div class="executive-image">
                                    <img src="images/executive3.jpg" alt="รองผู้อำนวยการฝ่ายกิจการนักเรียน">
                                </div>
                                <div class="executive-info">
                                    <h3 class="executive-title">นายธีรวัฒน์ พัฒนาดี</h3>
                                    <p class="executive-position">รองผู้อำนวยการฝ่ายกิจการนักเรียน</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ปุ่มควบคุมสไลด์ผู้บริหาร -->
                        <div class="executive-slider-controls">
                            <button class="slider-control prev" aria-label="ผู้บริหารก่อนหน้า"><i class="fas fa-chevron-left"></i></button>
                            <div class="slider-dots">
                                <span class="dot active" data-slide="0"></span>
                                <span class="dot" data-slide="1"></span>
                                <span class="dot" data-slide="2"></span>
                                <span class="dot" data-slide="3"></span>
                            </div>
                            <button class="slider-control next" aria-label="ผู้บริหารถัดไป"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ส่วนสถิติและตัวเลข -->
        <section class="stats-section parallax-bg">
            <div class="container">
                <div class="stats-container">
                    <?php
                    // ข้อมูลสถิติ
                    $stats = [
                        ['icon' => 'fas fa-user-graduate', 'number' => 3500, 'text' => 'นักเรียน'],
                        ['icon' => 'fas fa-chalkboard-teacher', 'number' => 185, 'text' => 'ครูและบุคลากร'],
                        ['icon' => 'fas fa-trophy', 'number' => 150, 'text' => 'รางวัลความสำเร็จ'],
                        ['icon' => 'fas fa-graduation-cap', 'number' => 98, 'text' => 'เปอร์เซ็นต์สอบเข้ามหาวิทยาลัย']
                    ];
                    
                    foreach ($stats as $stat) :
                    ?>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="<?php echo $stat['icon']; ?>"></i>
                        </div>
                        <div class="stat-number" data-target="<?php echo $stat['number']; ?>">0</div>
                        <div class="stat-text"><?php echo htmlspecialchars($stat['text']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ส่วนหลักสูตรเด่นของโรงเรียน -->
        <section class="programs-section">
            <div class="container">
                <div class="section-header-center">
                    <h2>หลักสูตรที่โดดเด่น</h2>
                    <p>โรงเรียนเทพปัญญามีหลักสูตรที่หลากหลายตอบสนองความต้องการและความสนใจของผู้เรียน</p>
                </div>
                
                <div class="programs-grid">
                    <?php
                    // ข้อมูลหลักสูตร
                    $programs = [
                        [
                            'icon' => 'fas fa-language',
                            'title' => 'หลักสูตรภาษาอังกฤษ (EP)',
                            'desc' => 'จัดการเรียนการสอนโดยใช้ภาษาอังกฤษเป็นสื่อกลาง สอนโดยครูชาวต่างชาติที่เป็นเจ้าของภาษา',
                            'url' => '#',
                            'delay' => 100
                        ],
                        [
                            'icon' => 'fas fa-atom',
                            'title' => 'หลักสูตรวิทยาศาสตร์-คณิตศาสตร์',
                            'desc' => 'เน้นการเรียนรู้ด้านวิทยาศาสตร์ คณิตศาสตร์ และเทคโนโลยี เพื่อสร้างพื้นฐานสู่การศึกษาต่อในระดับที่สูงขึ้น',
                            'url' => '#',
                            'delay' => 200
                        ],
                        [
                            'icon' => 'fas fa-robot',
                            'title' => 'STEM Education',
                            'desc' => 'บูรณาการความรู้ด้านวิทยาศาสตร์ เทคโนโลยี วิศวกรรมศาสตร์ และคณิตศาสตร์ ผ่านกิจกรรมการเรียนรู้แบบโครงงาน',
                            'url' => '#',
                            'delay' => 300
                        ],
                        [
                            'icon' => 'fas fa-music',
                            'title' => 'ศิลปะและดนตรี',
                            'desc' => 'ส่งเสริมความสามารถพิเศษด้านศิลปะและดนตรี โดยวิทยากรผู้เชี่ยวชาญ พร้อมอุปกรณ์ที่ทันสมัย',
                            'url' => '#',
                            'delay' => 400
                        ]
                    ];
                    
                    foreach ($programs as $program) :
                    ?>
                    <div class="program-box scroll-reveal" data-delay="<?php echo $program['delay']; ?>">
                        <div class="program-icon">
                            <i class="<?php echo $program['icon']; ?>"></i>
                        </div>
                        <h3 class="program-title"><?php echo htmlspecialchars($program['title']); ?></h3>
                        <p class="program-desc"><?php echo htmlspecialchars($program['desc']); ?></p>
                        <a href="<?php echo $program['url']; ?>" class="program-btn">รายละเอียด <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ส่วนพื้นที่และสิ่งอำนวยความสะดวก -->
        <? echo $ArticleWidget->render(9);  ?>

        <!-- ส่วนคั่นด้วยภาพพื้นหลัง -->
        <section class="parallax-section">
            <div class="parallax-content">
                <div class="thai-frame featured-content">
                    <div class="thai-motif thai-motif-top-left"></div>
                    <div class="thai-motif thai-motif-top-right"></div>
                    <div class="thai-motif thai-motif-bottom-left"></div>
                    <div class="thai-motif thai-motif-bottom-right"></div>
                    <h2>สมัครเรียนปีการศึกษา 2568</h2>
                    <p>เปิดรับสมัครทั้งระดับประถมศึกษาและมัธยมศึกษา พร้อมทุนการศึกษาสำหรับนักเรียนที่มีความสามารถพิเศษ</p>
                    <a href="#" class="btn-primary"><i class="fas fa-user-plus"></i> สมัครเรียนออนไลน์</a>
                </div>
            </div>
        </section>

        <!-- ข่าวสารล่าสุด -->
        <!-- ส่วนข่าวสารล่าสุด -->
        <?php echo $newsTabWidget->render($active_tab); ?>
        
        

        <!-- ภาพกิจกรรมล่าสุด -->
        <?php echo $galleryWidget->render(4, 'ภาพกิจกรรมล่าสุด', '#'); ?>

        <!-- คำถามที่พบบ่อย -->
        <section class="faq-section">
            <div class="container">
                <div class="section-header-center">
                    <h2>คำถามที่พบบ่อย</h2>
                    <p>คำถามและคำตอบที่ผู้ปกครองและนักเรียนมักสอบถามเกี่ยวกับโรงเรียนเทพปัญญา</p>
                </div>

                <div class="faq-container">
                    <?php
                    // ข้อมูลคำถามที่พบบ่อย
                    $faqs = [
                        [
                            'question' => 'โรงเรียนเปิดรับสมัครนักเรียนช่วงไหนบ้าง?',
                            'answer' => '<p>โรงเรียนเทพปัญญาเปิดรับสมัครนักเรียนใหม่ประจำปีการศึกษา ในช่วงเดือนมกราคม-เมษายน โดยแบ่งเป็น</p>
                                        <p>1. รอบแรก: เดือนมกราคม-กุมภาพันธ์ (โควตาความสามารถพิเศษ)<br>
                                           2. รอบทั่วไป: เดือนมีนาคม-เมษายน</p>
                                        <p>ผู้ปกครองสามารถสมัครออนไลน์ผ่านเว็บไซต์โรงเรียน หรือติดต่อสมัครด้วยตนเองที่ฝ่ายทะเบียนของโรงเรียน</p>',
                            'active' => true
                        ],
                        [
                            'question' => 'โรงเรียนมีหลักสูตรพิเศษอะไรบ้าง?',
                            'answer' => '<p>โรงเรียนเทพปัญญามีหลักสูตรพิเศษหลากหลาย ได้แก่</p>
                                        <p>1. หลักสูตรภาษาอังกฤษ (English Program - EP)<br>
                                           2. หลักสูตรวิทยาศาสตร์-คณิตศาสตร์ (Science-Math Program)<br>
                                           3. หลักสูตร STEM Education<br>
                                           4. หลักสูตรภาษาจีนและญี่ปุ่น<br>
                                           5. หลักสูตรศิลปะและดนตรี</p>
                                        <p>แต่ละหลักสูตรมีการคัดเลือกโดยการสอบ และมีค่าธรรมเนียมการศึกษาที่แตกต่างกัน สามารถสอบถามรายละเอียดเพิ่มเติมได้ที่ฝ่ายวิชาการของโรงเรียน</p>',
                            'active' => false
                        ],
                        [
                            'question' => 'โรงเรียนมีรถรับ-ส่งนักเรียนหรือไม่?',
                            'answer' => '<p>โรงเรียนเทพปัญญามีบริการรถรับ-ส่งนักเรียนครอบคลุมหลายเส้นทางในกรุงเทพมหานครและปริมณฑล</p>
                                        <p>ผู้ปกครองสามารถลงทะเบียนใช้บริการรถโรงเรียนได้ที่ฝ่ายกิจการนักเรียน โดยค่าบริการจะคิดตามระยะทางและจัดเก็บเป็นรายภาคเรียน</p>
                                        <p>โรงเรียนมีระบบติดตามรถ GPS ให้ผู้ปกครองสามารถติดตามตำแหน่งรถได้ผ่านแอปพลิเคชันของโรงเรียน เพื่อความปลอดภัยและสะดวกในการรับส่งนักเรียน</p>',
                            'active' => false
                        ],
                        [
                            'question' => 'โรงเรียนมีกิจกรรมเสริมหลักสูตรอะไรบ้าง?',
                            'answer' => '<p>โรงเรียนเทพปัญญามีกิจกรรมเสริมหลักสูตรที่หลากหลาย เพื่อพัฒนาศักยภาพของนักเรียนรอบด้าน ได้แก่</p>
                                        <p>1. ชมรมวิชาการ: คณิตศาสตร์ วิทยาศาสตร์ คอมพิวเตอร์ ภาษาต่างประเทศ<br>
                                           2. ชมรมศิลปะและดนตรี: วาดภาพ ดนตรีไทย ดนตรีสากล นาฏศิลป์<br>
                                           3. ชมรมกีฬา: ฟุตบอล บาสเกตบอล วอลเลย์บอล เทนนิส ว่ายน้ำ<br>
                                           4. ชมรมจิตอาสา: บำเพ็ญประโยชน์ อนุรักษ์สิ่งแวดล้อม<br>
                                           5. กิจกรรมค่ายวิชาการและทัศนศึกษา</p>
                                        <p>นักเรียนสามารถเลือกเข้าร่วมกิจกรรมได้ตามความสนใจ โดยจะมีการจัดกิจกรรมชมรมทุกวันพุธ ช่วงบ่าย</p>',
                            'active' => false
                        ],
                        [
                            'question' => 'ค่าธรรมเนียมการศึกษาของโรงเรียนเป็นอย่างไร?',
                            'answer' => '<p>ค่าธรรมเนียมการศึกษาของโรงเรียนเทพปัญญาแตกต่างกันตามระดับชั้นและหลักสูตร โดยประมาณดังนี้</p>
                                        <p>1. หลักสูตรปกติ:<br>
                                           - ระดับประถมศึกษา: XX,XXX - XX,XXX บาทต่อปี<br>
                                           - ระดับมัธยมศึกษา: XX,XXX - XX,XXX บาทต่อปี</p>
                                        <p>2. หลักสูตรพิเศษ (EP, วิทย์-คณิต):<br>
                                           - ระดับประถมศึกษา: XX,XXX - XX,XXX บาทต่อปี<br>
                                           - ระดับมัธยมศึกษา: XX,XXX - XX,XXX บาทต่อปี</p>
                                        <p>ค่าธรรมเนียมนี้ครอบคลุมค่าเล่าเรียน ค่าอาหารกลางวัน ค่าหนังสือและอุปกรณ์การเรียนพื้นฐาน โดยสามารถแบ่งชำระได้เป็นรายภาคเรียน</p>',
                            'active' => false
                        ]
                    ];
                    
                    foreach ($faqs as $index => $faq) :
                        $active_class = $faq['active'] ? 'active' : '';
                    ?>
                    <div class="faq-item <?php echo $active_class; ?>">
                        <div class="faq-question">
                            <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <?php echo $faq['answer']; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- ส่วนลิงค์หน่วยงานที่เกี่ยวข้อง -->
        <section class="links-section">
            <div class="container">
                <div class="section-header-center">
                    <h2>พันธมิตรและหน่วยงานที่เกี่ยวข้อง</h2>
                    <p>หน่วยงานและองค์กรที่ร่วมสนับสนุนการจัดการศึกษาของโรงเรียนเทพปัญญา</p>
                </div>

                <div class="links-container">
                    <?php
                    // ข้อมูลลิงก์พันธมิตร
                    $partners = [
                        ['name' => 'กระทรวงศึกษาธิการ', 'image' => 'link1.png', 'url' => 'https://www.moe.go.th/'],
                        ['name' => 'สำนักงานคณะกรรมการการศึกษาขั้นพื้นฐาน', 'image' => 'link2.png', 'url' => 'https://www.obec.go.th/'],
                        ['name' => 'สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน', 'image' => 'link3.png', 'url' => 'https://www.opec.go.th/'],
                        ['name' => 'สถาบันทดสอบทางการศึกษาแห่งชาติ', 'image' => 'link4.png', 'url' => 'https://www.niets.or.th/'],
                        ['name' => 'Dek-D', 'image' => 'link5.png', 'url' => 'https://www.dek-d.com/'],
                        ['name' => 'ทรูปลูกปัญญา', 'image' => 'link6.png', 'url' => 'https://www.trueplookpanya.com/']
                    ];
                    
                    foreach ($partners as $partner) :
                    ?>
                    <a href="<?php echo $partner['url']; ?>" target="_blank" class="link-item" title="<?php echo htmlspecialchars($partner['name']); ?>">
                        <img src="<?php echo SITE_URL; ?>images/<?php echo $partner['image']; ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>">
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Popup แนะนำ -->
    <div class="popup-container" id="welcome-popup">
        <div class="popup-content">
            <span class="popup-close">&times;</span>
            <div class="popup-header">
                <div class="popup-logo">
                    <img src="<?php echo SITE_URL; ?>images/school-logo.png" alt="โลโก้โรงเรียนเทพปัญญา">
                </div>
                <h2>ยินดีต้อนรับสู่โรงเรียนเทพปัญญา</h2>
            </div>
            <div class="popup-body">
                <div class="thai-frame popup-frame">
                    <div class="thai-motif thai-motif-top-left"></div>
                    <div class="thai-motif thai-motif-top-right"></div>
                    <div class="thai-motif thai-motif-bottom-left"></div>
                    <div class="thai-motif thai-motif-bottom-right"></div>
                    <h3>เปิดรับสมัครนักเรียนใหม่ ปีการศึกษา 2568</h3>
                    <p>ระดับชั้นอนุบาล ประถมศึกษา และมัธยมศึกษา</p>
                    <p>เปิดรับสมัคร 1 มีนาคม - 30 เมษายน 2568</p>
                    <div class="popup-buttons">
                        <a href="#" class="btn-primary"><i class="fas fa-user-plus"></i> สมัครเรียนออนไลน์</a>
                        <a href="#" class="btn-secondary"><i class="fas fa-info-circle"></i> ข้อมูลเพิ่มเติม</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
// นำเข้าส่วน footer
require_once 'includes/footer.php';
?>