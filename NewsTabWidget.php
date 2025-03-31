<?php
/**
 * Widget แสดงข่าวในรูปแบบแท็บ
 * สำหรับหน้าแรกของเว็บไซต์
 */
class NewsTabWidget {
    private $db;
    private $news;
    private $categories = [];
    
    /**
     * Constructor
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
        $this->news = new News($db);
        $this->loadNewsCategories();
    }
    
    /**
     * โหลดหมวดหมู่ข่าวทั้งหมด
     */
    private function loadNewsCategories() {
        $stmt = $this->db->prepare("
            SELECT id, name, slug 
            FROM categories 
            WHERE type = 'news' 
            ORDER BY id ASC
        ");
        $stmt->execute();
        $this->categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * รับข่าวล่าสุดตามหมวดหมู่
     * @param int $category_id
     * @param int $limit
     * @return array
     */
    private function getLatestNewsByCategory($category_id, $limit = 4) {
        return $this->news->get_all($limit, 0, 'publish_date DESC', 'published', $category_id);
    }
    
    /**
     * สร้าง HTML สำหรับปุ่มแท็บ
     * @param string $active_tab
     * @return string
     */
    private function renderTabButtons($active_tab = null) {
        $html = '<div class="tabs">';
        
        foreach ($this->categories as $index => $category) {
            $active_class = ($index === 0 && $active_tab === null) || $active_tab === $category['slug'] ? 'active' : '';
            $data_tab = htmlspecialchars($category['slug']);
            $html .= '<button class="tab-btn ' . $active_class . '" data-tab="' . $data_tab . '">' . 
                    htmlspecialchars($category['name']) . '</button>';
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * สร้าง HTML สำหรับการ์ดข่าว
     * @param array $news_item
     * @return string
     */
    private function renderNewsCard($news_item) {
        $html = '<div class="news-card">';
        
        // รูปภาพ
        $html .= '<div class="news-image">';
        if (!empty($news_item['cover_image'])) {
            $html .= '<img src="' . htmlspecialchars($news_item['cover_image']) . '" alt="' . 
                    htmlspecialchars($news_item['title']) . '">';
        } else {
            $html .= '<img src="assets/images/no-image.jpg" alt="No Image">';
        }
        $html .= '<span class="news-date">' . date('d M Y', strtotime($news_item['publish_date'])) . '</span>';
        $html .= '</div>';
        
        // เนื้อหา
        $html .= '<div class="news-body">';
        $html .= '<h3>' . htmlspecialchars($news_item['title']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($news_item['excerpt']) . '</p>';
        $html .= '<div class="news-meta">';
        $html .= '<a href="news/view.php?slug=' . htmlspecialchars($news_item['slug']) . 
                '" class="read-more">อ่านเพิ่มเติม <i class="fas fa-arrow-right"></i></a>';
        $html .= '</div>'; // news-meta
        $html .= '</div>'; // news-body
        
        $html .= '</div>'; // news-card
        return $html;
    }
    
    /**
     * สร้าง HTML สำหรับแท็บเนื้อหาข่าว
     * @return string
     */
    private function renderTabContent() {
        $html = '<div class="tab-content">';
        
        foreach ($this->categories as $index => $category) {
            $active_class = $index === 0 ? 'active' : '';
            $tab_id = htmlspecialchars($category['slug']) . '-tab';
            
            $html .= '<div class="tab-pane ' . $active_class . '" id="' . $tab_id . '">';
            
            // รับข่าวล่าสุดของหมวดหมู่นี้
            $news_items = $this->getLatestNewsByCategory($category['id']);
            
            if (!empty($news_items)) {
                $html .= '<div class="news-grid">';
                
                foreach ($news_items as $news_item) {
                    $html .= $this->renderNewsCard($news_item);
                }
                
                $html .= '</div>'; // news-grid
                
                // ปุ่มดูทั้งหมด
                $html .= '<div class="view-more-container">';
                $html .= '<a href="news/category.php?slug=' . htmlspecialchars($category['slug']) . 
                        '" class="btn-view-more">ดูข่าวทั้งหมด <i class="fas fa-long-arrow-alt-right"></i></a>';
                $html .= '</div>';
            } else {
                $html .= '<div class="no-content">ไม่มีข่าวในหมวดหมู่นี้</div>';
            }
            
            $html .= '</div>'; // tab-pane
        }
        
        $html .= '</div>'; // tab-content
        return $html;
    }
    
    /**
     * แสดง Widget
     * @param string $active_tab
     * @return string
     */
    public function render($active_tab = null) {
        $html = '<section class="news-section">';
        $html .= '<div class="container">';
        
        // หัวข้อส่วน
        $html .= '<div class="section-header with-decoration">';
        $html .= '<div class="decoration left"></div>';
        $html .= '<h2><i class="fas fa-newspaper"></i> ข่าวสารและกิจกรรม</h2>';
        $html .= '<div class="decoration right"></div>';
        $html .= '<a href="news/list.php" class="view-all">ดูทั้งหมด <i class="fas fa-arrow-right"></i></a>';
        $html .= '</div>';
        
        // แท็บคอนเทนเนอร์
        $html .= '<div class="tabs-container">';
        $html .= $this->renderTabButtons($active_tab);
        $html .= $this->renderTabContent();
        $html .= '</div>'; // tabs-container
        
        $html .= '</div>'; // container
        $html .= '</section>';
        
        return $html;
    }
}
?>