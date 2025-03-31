<?php
/**
 * Widget แสดงสไลด์หลัก
 * สำหรับหน้าแรกของเว็บไซต์
 */
class SlideWidget {
    private $db;
    private $slide;
    
    /**
     * Constructor
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
        
        // โหลดคลาส Slide หากยังไม่มีการโหลด
        if (!class_exists('Slide')) {
            require_once 'admin/classes/Slide.php';
        }
        
        $this->slide = new Slide($db);
    }
    
    /**
     * ดึงข้อมูลสไลด์จากฐานข้อมูล
     * 
     * @param int|string $category_id รหัสหมวดหมู่หรือ slug
     * @return array ข้อมูลสไลด์
     */
    private function getSlides($category_id) {
        return $this->slide->get_active_slides($category_id);
    }
    
    /**
     * เรนเดอร์ HTML ของสไลด์
     * 
     * @param array $slide ข้อมูลสไลด์
     * @param int $index ลำดับของสไลด์
     * @param bool $active กำหนดให้เป็นสไลด์แรกที่แสดงหรือไม่
     * @return string HTML ของสไลด์
     */
    private function renderSlide($slide, $index, $active = false) {
        $active_class = $active ? ' active' : '';
        
        $html = '<div class="slide' . $active_class . '">';
        $html .= '<img src="' . htmlspecialchars($slide['image_path']) . '" alt="' . htmlspecialchars($slide['title'] ?: 'ภาพสไลด์ ' . ($index + 1)) . '">';
        
        // เพิ่มคำอธิบายหากมีข้อมูล
        if (!empty($slide['title']) || !empty($slide['description'])) {
            $html .= '<div class="slide-caption">';
            $html .= '<div class="thai-frame">';
            $html .= '<div class="thai-motif thai-motif-top-left"></div>';
            $html .= '<div class="thai-motif thai-motif-top-right"></div>';
            $html .= '<div class="thai-motif thai-motif-bottom-left"></div>';
            $html .= '<div class="thai-motif thai-motif-bottom-right"></div>';
            
            if (!empty($slide['title'])) {
                $html .= '<h2>' . htmlspecialchars($slide['title']) . '</h2>';
            }
            
            if (!empty($slide['description'])) {
                $html .= '<p>' . htmlspecialchars($slide['description']) . '</p>';
            }
            
            $html .= '</div>'; // .thai-frame
            $html .= '</div>'; // .slide-caption
        }
        
        // เพิ่มลิงก์หากมีการกำหนด
        if (!empty($slide['link'])) {
            $html .= '<a href="' . htmlspecialchars($slide['link']) . '" class="slide-link"></a>';
        }
        
        $html .= '</div>'; // .slide
        
        return $html;
    }
    
    /**
     * เรนเดอร์ HTML ของ dot navigation
     * 
     * @param int $count จำนวนสไลด์
     * @param int $active_index ลำดับสไลด์ที่ active
     * @return string HTML ของ dot navigation
     */
    private function renderDots($count, $active_index = 0) {
        $html = '<div class="slider-dots">';
        
        for ($i = 0; $i < $count; $i++) {
            $active_class = ($i == $active_index) ? ' active' : '';
            $html .= '<span class="dot' . $active_class . '" data-slide="' . $i . '"></span>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * แสดง widget
     * 
     * @param int|string $category_id รหัสหมวดหมู่หรือ slug
     * @param int $active_index ลำดับสไลด์ที่ active เริ่มต้น
     * @return string HTML ของ widget
     */
    public function render($category_id = 1, $active_index = 0) {
        // ดึงข้อมูลสไลด์
        $slides = $this->getSlides($category_id);
        
        // ถ้าไม่มีสไลด์ ให้ส่งค่าว่างกลับไป
        if (empty($slides)) {
            return '';
        }
        
        // กำหนดค่า active_index ใหม่หากเกินจำนวนสไลด์
        if ($active_index >= count($slides)) {
            $active_index = 0;
        }
        
        // เริ่มสร้าง HTML ของ widget
        $html = '<section class="hero-slider">';
        $html .= '<div class="slider-container">';
        
        // เพิ่มสไลด์แต่ละอัน
        foreach ($slides as $index => $slide) {
            $html .= $this->renderSlide($slide, $index, $index == $active_index);
        }
        
        // เพิ่มปุ่มเลื่อนสไลด์
        $html .= '<button class="slider-btn prev" aria-label="ก่อนหน้า"><i class="fas fa-chevron-left"></i></button>';
        $html .= '<button class="slider-btn next" aria-label="ถัดไป"><i class="fas fa-chevron-right"></i></button>';
        
        // เพิ่ม dot navigation
        $html .= $this->renderDots(count($slides), $active_index);
        
        $html .= '</div>'; // .slider-container
        $html .= '</section>'; // .hero-slider
        
        return $html;
    }
    
    /**
     * แสดง widget ในรูปแบบง่าย (โดยไม่มี section wrapper)
     * 
     * @param int|string $category_id รหัสหมวดหมู่หรือ slug
     * @param int $active_index ลำดับสไลด์ที่ active เริ่มต้น
     * @return string HTML ของ widget
     */
    public function renderSimple($category_id = 1, $active_index = 0) {
        // ดึงข้อมูลสไลด์
        $slides = $this->getSlides($category_id);
        
        // ถ้าไม่มีสไลด์ ให้ส่งค่าว่างกลับไป
        if (empty($slides)) {
            return '';
        }
        
        // กำหนดค่า active_index ใหม่หากเกินจำนวนสไลด์
        if ($active_index >= count($slides)) {
            $active_index = 0;
        }
        
        // เริ่มสร้าง HTML ของ widget
        $html = '<div class="slider-container">';
        
        // เพิ่มสไลด์แต่ละอัน
        foreach ($slides as $index => $slide) {
            $html .= $this->renderSlide($slide, $index, $index == $active_index);
        }
        
        // เพิ่มปุ่มเลื่อนสไลด์
        $html .= '<button class="slider-btn prev" aria-label="ก่อนหน้า"><i class="fas fa-chevron-left"></i></button>';
        $html .= '<button class="slider-btn next" aria-label="ถัดไป"><i class="fas fa-chevron-right"></i></button>';
        
        // เพิ่ม dot navigation
        $html .= $this->renderDots(count($slides), $active_index);
        
        $html .= '</div>'; // .slider-container
        
        return $html;
    }
}
?>