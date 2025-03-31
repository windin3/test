<?php
/**
 * Widget แสดงบทความตามหมวดหมู่
 * สำหรับหน้าแรกของเว็บไซต์
 */
class ArticleWidget {
    private $db;
    private $article;
    
    /**
     * Constructor
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
        $this->article = new Article($db);
    }
    
    /**
     * ดึงข้อมูลหมวดหมู่จาก ID
     * @param int $category_id รหัสหมวดหมู่
     * @return array|null ข้อมูลหมวดหมู่หรือ null ถ้าไม่พบ
     */
    private function getCategoryInfo($category_id) {
        $stmt = $this->db->prepare("
            SELECT name, description, type, slug
            FROM categories
            WHERE id = :category_id
        ");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * รับบทความในหมวดหมู่สิ่งอำนวยความสะดวก
     * @param int $category_id รหัสหมวดหมู่ที่ต้องการ
     * @param int $limit จำนวนที่ต้องการแสดง
     * @return array
     */
    public function getFacilitiesArticles($category_id = 9, $limit = 3) {
        // ดึงบทความทั้งหมดในหมวดหมู่ที่กำหนด
        return $this->article->get_all($limit, 0, 'created_at DESC', 'published', $category_id);
    }
    
    /**
     * สร้าง HTML สำหรับการ์ดแสดงสิ่งอำนวยความสะดวก
     * @param array $article ข้อมูลบทความ
     * @param int $delay ค่าดีเลย์สำหรับเอฟเฟกต์ scroll reveal
     * @return string
     */
    private function renderFacilityCard($article, $delay = 100) {
        // กำหนดหมวดหมู่ย่อย (ถ้าไม่มีให้ใช้ค่าเริ่มต้น)
        $category = !empty($article['sub_category']) ? $article['sub_category'] : 'การเรียนรู้';
        
        $html = '<div class="facility-card scroll-reveal" data-delay="' . $delay . '">';
        
        // ส่วนรูปภาพ
        $html .= '<div class="facility-image">';
        if (!empty($article['cover_image'])) {
            $html .= '<img src="' . htmlspecialchars($article['cover_image']) . '" alt="' . 
                    htmlspecialchars($article['title']) . '">';
        } else {
            // รูปภาพเริ่มต้นถ้าไม่มีรูป
            $html .= '<img src="' . SITE_URL . 'images/no-image.jpg" alt="' . 
                    htmlspecialchars($article['title']) . '">';
        }
        $html .= '<span class="facility-category">' . htmlspecialchars($category) . '</span>';
        $html .= '</div>';
        
        // ส่วนเนื้อหา
        $html .= '<div class="facility-body">';
        $html .= '<h3 class="facility-title">' . htmlspecialchars($article['title']) . '</h3>';
        
        // แสดงข้อความสั้น
        if (!empty($article['excerpt'])) {
            $html .= '<p class="facility-desc">' . htmlspecialchars($article['excerpt']) . '</p>';
        } else {
            // สร้าง excerpt จากเนื้อหาถ้าไม่มี
            $excerpt = $this->article->generate_excerpt($article['content'], 150);
            $html .= '<p class="facility-desc">' . htmlspecialchars($excerpt) . '</p>';
        }
        
        // ลิงก์ดูรายละเอียด
        $html .= '<a href="' . SITE_URL . 'articles/view.php?slug=' . 
                htmlspecialchars($article['slug']) . '" class="facility-link">ดูเพิ่มเติม <i class="fas fa-arrow-right"></i></a>';
        
        $html .= '</div>'; // ปิด facility-body
        $html .= '</div>'; // ปิด facility-card
        
        return $html;
    }
    
    /**
     * แสดง Widget
     * @param int $category_id รหัสหมวดหมู่ที่ต้องการแสดง
     * @param string $title หัวข้อส่วน (ถ้าต้องการกำหนดเอง)
     * @param string $subtitle คำอธิบายส่วน (ถ้าต้องการกำหนดเอง)
     * @param int $limit จำนวนที่ต้องการแสดง
     * @return string
     */
    public function render($category_id = 9, $title = null, $subtitle = null, $limit = 3) {
        // ดึงข้อมูลหมวดหมู่จากฐานข้อมูล
        $category = $this->getCategoryInfo($category_id);
        
        // ถ้าไม่พบหมวดหมู่
        if (!$category) {
            return '<div class="container"><p class="no-content">ไม่พบหมวดหมู่ที่ระบุ</p></div>';
        }
        
        // ใช้ข้อมูลจากฐานข้อมูลถ้าไม่ได้กำหนดค่าเอง
        if (empty($title)) {
            $title = $category['name'];
        }
        
        if (empty($subtitle)) {
            $subtitle = $category['description'] ?? 'แสดงรายการบทความในหมวดหมู่ ' . $category['name'];
        }
        
        // ดึงข้อมูลบทความ
        $articles = $this->getFacilitiesArticles($category_id, $limit);
        
        // ถ้าไม่มีบทความให้แสดงข้อความว่าง
        if (empty($articles)) {
            return '<div class="container"><p class="no-content">ไม่พบบทความในหมวดหมู่นี้</p></div>';
        }
        
        // สร้าง HTML
        $html = '<section class="facilities-section">';
        $html .= '<div class="container">';
        
        // ส่วนหัวข้อ
        $html .= '<div class="section-header-center">';
        $html .= '<h2>' . htmlspecialchars($title) . '</h2>';
        $html .= '<p>' . htmlspecialchars($subtitle) . '</p>';
        $html .= '</div>';
        
        // แสดงบทความในรูปแบบกริด
        $html .= '<div class="facilities-grid">';
        
        // วนลูปแสดงบทความ
        foreach ($articles as $index => $article) {
            // กำหนดค่า delay เพิ่มขึ้นตามลำดับ
            $delay = 100 * ($index + 1);
            $html .= $this->renderFacilityCard($article, $delay);
        }
        
        $html .= '</div>'; // ปิด facilities-grid
        
        // ลิงก์ดูทั้งหมด
        $html .= '<div class="view-more-container">';
        
        // ใช้ slug ถ้ามี แทนการใช้ ID
        $url_param = !empty($category['slug']) ? 'slug=' . $category['slug'] : 'id=' . $category_id;
        $html .= '<a href="' . SITE_URL . 'articles/category.php?' . $url_param . 
                '" class="btn-view-more">ดูทั้งหมด <i class="fas fa-long-arrow-alt-right"></i></a>';
        $html .= '</div>';
        
        $html .= '</div>'; // ปิด container
        $html .= '</section>'; // ปิด facilities-section
        
        return $html;
    }
}
?>