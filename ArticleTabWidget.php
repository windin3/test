<?php
/**
 * Widget แสดงบทความล่าสุด
 * สำหรับหน้าแรกของเว็บไซต์
 */
class ArticleTabWidget {
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
     * สร้าง HTML สำหรับการ์ดบทความ
     * @param array $article_item
     * @return string
     */
    private function renderArticleCard($article_item) {
        $html = '<div class="article-card">';
        
        // รูปภาพ
        if (!empty($article_item['cover_image'])) {
            $html .= '<div class="article-image">';
            $html .= '<img src="' . htmlspecialchars($article_item['cover_image']) . '" alt="' . 
                    htmlspecialchars($article_item['title']) . '">';
            $html .= '</div>';
        }
        
        // หมวดหมู่
        if (!empty($article_item['category_name'])) {
            $html .= '<div class="article-category">';
            $html .= '<a href="articles/category.php?slug=' . htmlspecialchars($article_item['category_slug']) . '">' . 
                    htmlspecialchars($article_item['category_name']) . '</a>';
            $html .= '</div>';
        }
        
        // เนื้อหา
        $html .= '<div class="article-body">';
        $html .= '<h3><a href="articles/view.php?slug=' . htmlspecialchars($article_item['slug']) . '">' . 
                htmlspecialchars($article_item['title']) . '</a></h3>';
        $html .= '<p>' . htmlspecialchars($article_item['excerpt']) . '</p>';
        
        // ข้อมูลเมตา
        $html .= '<div class="article-meta">';
        $html .= '<span class="date"><i class="far fa-calendar-alt"></i> ' . 
                date('d M Y', strtotime($article_item['publish_date'])) . '</span>';
        if (!empty($article_item['created_by_name'])) {
            $html .= '<span class="author"><i class="far fa-user"></i> ' . 
                    htmlspecialchars($article_item['created_by_name']) . '</span>';
        }
        $html .= '</div>'; // article-meta
        
        $html .= '<a href="articles/view.php?slug=' . htmlspecialchars($article_item['slug']) . 
                '" class="read-more">อ่านเพิ่มเติม <i class="fas fa-arrow-right"></i></a>';
        
        $html .= '</div>'; // article-body
        
        $html .= '</div>'; // article-card
        return $html;
    }
    
    /**
     * รับบทความที่เด่น
     * @param int $limit
     * @return array
     */
    private function getFeaturedArticles($limit = 1) {
        return $this->article->get_recent_articles($limit, true);
    }
    
    /**
     * รับบทความล่าสุด
     * @param int $limit
     * @return array
     */
    private function getLatestArticles($limit = 3) {
        return $this->article->get_recent_articles($limit);
    }
    
    /**
     * แสดงบทความเด่น
     * @return string
     */
    private function renderFeaturedArticle() {
        $featured_articles = $this->getFeaturedArticles(1);
        
        if (empty($featured_articles)) {
            return '';
        }
        
        $article = $featured_articles[0];
        
        $html = '<div class="featured-article">';
        
        if (!empty($article['cover_image'])) {
            $html .= '<div class="featured-image">';
            $html .= '<img src="' . htmlspecialchars($article['cover_image']) . '" alt="' . 
                    htmlspecialchars($article['title']) . '">';
            $html .= '</div>';
        }
        
        $html .= '<div class="featured-content">';
        
        if (!empty($article['category_name'])) {
            $html .= '<div class="article-category">';
            $html .= '<a href="articles/category.php?slug=' . htmlspecialchars($article['category_slug']) . '">' . 
                    htmlspecialchars($article['category_name']) . '</a>';
            $html .= '<span class="featured-badge">บทความแนะนำ</span>';
            $html .= '</div>';
        }
        
        $html .= '<h2><a href="articles/view.php?slug=' . htmlspecialchars($article['slug']) . '">' . 
                htmlspecialchars($article['title']) . '</a></h2>';
        $html .= '<p>' . htmlspecialchars($article['excerpt']) . '</p>';
        
        $html .= '<div class="article-meta">';
        $html .= '<span class="date"><i class="far fa-calendar-alt"></i> ' . 
                date('d M Y', strtotime($article['publish_date'])) . '</span>';
        if (!empty($article['created_by_name'])) {
            $html .= '<span class="author"><i class="far fa-user"></i> ' . 
                    htmlspecialchars($article['created_by_name']) . '</span>';
        }
        $html .= '</div>'; // article-meta
        
        $html .= '<a href="articles/view.php?slug=' . htmlspecialchars($article['slug']) . 
                '" class="btn-read-more">อ่านเพิ่มเติม <i class="fas fa-arrow-right"></i></a>';
        
        $html .= '</div>'; // featured-content
        
        $html .= '</div>'; // featured-article
        
        return $html;
    }
    
    /**
     * แสดง Widget
     * @return string
     */
    public function render() {
        $html = '<section class="articles-section">';
        $html .= '<div class="container">';
        
        // หัวข้อส่วน
        $html .= '<div class="section-header with-decoration">';
        $html .= '<div class="decoration left"></div>';
        $html .= '<h2><i class="fas fa-book-open"></i> บทความที่น่าสนใจ</h2>';
        $html .= '<div class="decoration right"></div>';
        $html .= '<a href="articles/list.php" class="view-all">ดูทั้งหมด <i class="fas fa-arrow-right"></i></a>';
        $html .= '</div>';
        
        // เนื้อหา
        $html .= '<div class="articles-container">';
        
        // บทความเด่น
        $featured_html = $this->renderFeaturedArticle();
        if (!empty($featured_html)) {
            $html .= $featured_html;
        }
        
        // บทความล่าสุด
        $latest_articles = $this->getLatestArticles(3);
        
        if (!empty($latest_articles)) {
            $html .= '<div class="latest-articles">';
            $html .= '<h3 class="sub-heading">บทความล่าสุด</h3>';
            $html .= '<div class="articles-grid">';
            
            foreach ($latest_articles as $article) {
                $html .= $this->renderArticleCard($article);
            }
            
            $html .= '</div>'; // articles-grid
            $html .= '</div>'; // latest-articles
        }
        
        $html .= '</div>'; // articles-container
        
        // ปุ่มดูทั้งหมด
        $html .= '<div class="view-more-container">';
        $html .= '<a href="articles/list.php" class="btn-view-more">ดูบทความทั้งหมด <i class="fas fa-long-arrow-alt-right"></i></a>';
        $html .= '</div>';
        
        $html .= '</div>'; // container
        $html .= '</section>';
        
        return $html;
    }
}
?>