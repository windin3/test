<?php
/**
 * Widget แสดงภาพกิจกรรมล่าสุด
 * สำหรับหน้าแรกของเว็บไซต์
 */
class GalleryWidget {
    private $db;
    private $gallery;
    
    /**
     * Constructor
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
        require_once 'admin/classes/Gallery.php';
        $this->gallery = new Gallery($db);
    }
    
    /**
     * รับข้อมูลแกลเลอรี่ล่าสุดพร้อมรูปภาพตัวอย่าง
     * @param int $limit จำนวนที่ต้องการแสดง
     * @return array
     */
    private function getLatestGalleries($limit = 4) {
        // ดึงข้อมูลแกลเลอรี่ล่าสุด
        $stmt = $this->db->prepare("
            SELECT g.*, 
                   u.display_name as created_by_name,
                   DATE_FORMAT(g.created_at, '%d %M %Y') as formatted_date
            FROM galleries g
            LEFT JOIN users u ON g.created_by = u.id
            ORDER BY g.created_at DESC
            LIMIT :limit
        ");
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $galleries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // แปลงวันที่เป็นภาษาไทย และดึงรูปภาพแรกในแต่ละแกลเลอรี่
        foreach ($galleries as &$gallery) {
            // แปลงวันที่เป็นรูปแบบไทย
            if (isset($gallery['created_at'])) {
                $date = new DateTime($gallery['created_at']);
                $gallery['formatted_date'] = $this->formatThaiDate($date);
            }
            
            // ดึงรูปภาพแรกของแกลเลอรี่
            $imageStmt = $this->db->prepare("
                SELECT * FROM gallery_images 
                WHERE gallery_id = :gallery_id 
                ORDER BY sort_order ASC, uploaded_at DESC 
                LIMIT 1
            ");
            $imageStmt->bindParam(':gallery_id', $gallery['id']);
            $imageStmt->execute();
            
            $image = $imageStmt->fetch(PDO::FETCH_ASSOC);
            if ($image) {
                $gallery['image_path'] = $image['image_path'];
                $gallery['image_title'] = $image['title'] ?? $gallery['name'];
            } else {
                $gallery['image_path'] = 'images/no-image.jpg';
                $gallery['image_title'] = $gallery['name'];
            }
        }
        
        return $galleries;
    }
    
    /**
     * แปลงวันที่เป็นรูปแบบภาษาไทย
     * @param DateTime $date
     * @return string
     */
    private function formatThaiDate($date) {
        $thai_months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม',
            4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
            7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน',
            10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        
        $day = $date->format('d');
        $month = $thai_months[(int)$date->format('m')];
        $year = $date->format('Y') + 543; // แปลง ค.ศ. เป็น พ.ศ.
        
        return "$day $month $year";
    }
    
    /**
     * สร้าง HTML สำหรับหนึ่งรายการแกลเลอรี่
     * @param array $gallery ข้อมูลแกลเลอรี่
     * @return string
     */
    private function renderGalleryItem($gallery) {
        $html = '<div class="gallery-item">';
        
        // รูปภาพ
        $html .= '<img src="' . SITE_URL . htmlspecialchars($gallery['image_path']) . '" alt="' . 
                htmlspecialchars($gallery['image_title']) . '">';
        
        // ข้อมูลซ้อนทับ (overlay)
        $html .= '<div class="gallery-overlay">';
        $html .= '<h3>' . htmlspecialchars($gallery['name']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($gallery['formatted_date']) . '</p>';
        
        // ลิงก์ไปยังหน้าแกลเลอรี่
        $html .= '<a href="' . SITE_URL . 'news/gallery-view.php?id=' . $gallery['id'] . '" class="btn-view">ดูเพิ่มเติม <i class="fas fa-search-plus"></i></a>';
        
        $html .= '</div>'; // ปิด gallery-overlay
        $html .= '</div>'; // ปิด gallery-item
        
        return $html;
    }
    
    /**
     * แสดง Widget ภาพกิจกรรมล่าสุด
     * @param int $limit จำนวนที่ต้องการแสดง
     * @param string $title หัวข้อส่วน (ถ้าต้องการกำหนดเอง)
     * @param string $view_all_url URL สำหรับลิงก์ "ดูทั้งหมด"
     * @return string
     */
    public function render($limit = 4, $title = 'ภาพกิจกรรมล่าสุด', $view_all_url = 'news/gallery.php') {
        // ดึงข้อมูลแกลเลอรี่ล่าสุด
        $galleries = $this->getLatestGalleries($limit);
        
        // ถ้าไม่มีแกลเลอรี่ให้แสดงข้อความว่าง
        if (empty($galleries)) {
            return '<div class="container"><p class="no-content">ไม่พบภาพกิจกรรม</p></div>';
        }
        
        // สร้าง HTML
        $html = '<section class="gallery-section">';
        $html .= '<div class="container">';
        
        // ส่วนหัวข้อ
        $html .= '<div class="section-header with-decoration">';
        $html .= '<div class="decoration left"></div>';
        $html .= '<h2><i class="fas fa-images"></i> ' . htmlspecialchars($title) . '</h2>';
        $html .= '<div class="decoration right"></div>';
        $html .= '<a href="' . SITE_URL . $view_all_url . '" class="view-all">ดูทั้งหมด <i class="fas fa-arrow-right"></i></a>';
        $html .= '</div>';
        
        // ส่วนแสดงแกลเลอรี่
        $html .= '<div class="gallery-container">';
        $html .= '<div class="gallery-items">';
        
        // วนลูปแสดงรายการ
        foreach ($galleries as $gallery) {
            $html .= $this->renderGalleryItem($gallery);
        }
        
        $html .= '</div>'; // ปิด gallery-items
        
        // ปุ่มนำทาง
        $html .= '<button class="gallery-nav prev" aria-label="ก่อนหน้า"><i class="fas fa-chevron-left"></i></button>';
        $html .= '<button class="gallery-nav next" aria-label="ถัดไป"><i class="fas fa-chevron-right"></i></button>';
        
        $html .= '</div>'; // ปิด gallery-container
        $html .= '</div>'; // ปิด container
        $html .= '</section>'; // ปิด gallery-section
        
        return $html;
    }
}
?>