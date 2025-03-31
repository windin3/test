<?php
/**
 * คลาสจัดการ Widget การตั้งค่าเว็บไซต์
 */
class SettingsWidget {
    private $db;
    private $settings_cache = [];
    
    /**
     * Constructor
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
        $this->loadSettings();
    }
    
    /**
     * โหลดการตั้งค่าทั้งหมดมาเก็บไว้ใน cache
     */
    private function loadSettings() {
        try {
            $stmt = $this->db->prepare("
                SELECT setting_key, setting_value, is_serialized 
                FROM website_settings
            ");
            
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $value = $row['setting_value'];
                
                // ถ้าเป็นข้อมูลที่ถูก serialize
                if ($row['is_serialized']) {
                    $value = unserialize($value);
                }
                
                $this->settings_cache[$row['setting_key']] = $value;
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * รับค่าการตั้งค่า
     * @param string $key
     * @param mixed $default ค่าเริ่มต้นหากไม่พบการตั้งค่า
     * @return mixed
     */
    public function get($key, $default = '') {
        return isset($this->settings_cache[$key]) ? $this->settings_cache[$key] : $default;
    }
    
    /**
     * รับค่าการตั้งค่าตามกลุ่ม
     * @param string $group
     * @return array
     */
    public function getByGroup($group) {
        $result = [];
        
        try {
            $stmt = $this->db->prepare("
                SELECT setting_key, setting_value, is_serialized 
                FROM website_settings 
                WHERE setting_group = :group
            ");
            
            $stmt->bindParam(':group', $group);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $value = $row['setting_value'];
                
                // ถ้าเป็นข้อมูลที่ถูก serialize
                if ($row['is_serialized']) {
                    $value = unserialize($value);
                }
                
                $result[$row['setting_key']] = $value;
                $this->settings_cache[$row['setting_key']] = $value;
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * รวม widget แถบด้านบนสุด
     * @return string
     */
    public function renderTopBarWidget() {
        // ดึงข้อมูลจากกลุ่มการตั้งค่าที่เกี่ยวข้อง
        $contact_settings = $this->getByGroup('contact');
        $social_settings = $this->getByGroup('social');
        
        $contact_phone = isset($contact_settings['contact_phone']) ? $contact_settings['contact_phone'] : '';
        $contact_email = isset($contact_settings['contact_email']) ? $contact_settings['contact_email'] : '';
        
        $facebook_url = isset($social_settings['social_facebook']) ? $social_settings['social_facebook'] : '#';
        $twitter_url = isset($social_settings['social_twitter']) ? $social_settings['social_twitter'] : '#';
        $instagram_url = isset($social_settings['social_instagram']) ? $social_settings['social_instagram'] : '#';
        $youtube_url = isset($social_settings['social_youtube']) ? $social_settings['social_youtube'] : '#';
        $line_url = isset($social_settings['social_line']) ? $social_settings['social_line'] : '#';
        
        // สร้าง HTML สำหรับ widget
        $html = '<div class="top-bar">
            <div class="container">
                <div class="contact-info">';
        
        if (!empty($contact_phone)) {
            $html .= '<span><i class="fas fa-phone"></i> ' . htmlspecialchars($contact_phone) . '</span>';
        }
        
        if (!empty($contact_email)) {
            $html .= '<span><i class="fas fa-envelope"></i> ' . htmlspecialchars($contact_email) . '</span>';
        }
        
        $html .= '</div>
                <div class="social-icons">';
        
        if (!empty($facebook_url)) {
            $html .= '<a href="' . htmlspecialchars($facebook_url) . '" aria-label="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>';
        }
        
        if (!empty($youtube_url)) {
            $html .= '<a href="' . htmlspecialchars($youtube_url) . '" aria-label="YouTube" target="_blank"><i class="fab fa-youtube"></i></a>';
        }
        
        if (!empty($instagram_url)) {
            $html .= '<a href="' . htmlspecialchars($instagram_url) . '" aria-label="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>';
        }
        
        if (!empty($line_url)) {
            $html .= '<a href="' . htmlspecialchars($line_url) . '" aria-label="Line" target="_blank"><i class="fab fa-line"></i></a>';
        }
        
        $html .= '</div>
                <div class="accessibility">
                    <button class="font-size-btn" id="font-decrease" aria-label="ลดขนาดตัวอักษร">A-</button>
                    <button class="font-size-btn" id="font-default" aria-label="ขนาดตัวอักษรปกติ">A</button>
                    <button class="font-size-btn" id="font-increase" aria-label="เพิ่มขนาดตัวอักษร">A+</button>
                    <button class="color-mode-btn" id="color-normal" aria-label="โหมดสีปกติ"><i class="fas fa-sun"></i></button>
                    <button class="color-mode-btn" id="color-dark" aria-label="โหมดกลางคืน"><i class="fas fa-moon"></i></button>
                </div>
            </div>
        </div>';
        
        return $html;
    }
    
    /**
     * รวม widget โลโก้และชื่อโรงเรียน
     * @return string
     */
    public function renderLogoWidget() {
        // ดึงข้อมูลจากกลุ่มการตั้งค่าที่เกี่ยวข้อง
        $general_settings = $this->getByGroup('general');
        
        $site_title_th = isset($general_settings['site_title_th']) ? $general_settings['site_title_th'] : 'โรงเรียนเทพปัญญา';
        $site_title_en = isset($general_settings['site_title_en']) ? $general_settings['site_title_en'] : 'THEP PANYA SCHOOL';
        $site_logo = isset($general_settings['site_logo']) ? $general_settings['site_logo'] : 'images/school-logo.png';
        
        // สร้าง HTML สำหรับ widget
        $html = '<div class="logo-section">
            <div class="container">
                <div class="logo-container">';
        
        if (!empty($site_logo)) {
            $html .= '<img src="' . htmlspecialchars($site_logo) . '" alt="โลโก้' . htmlspecialchars($site_title_th) . '" class="logo">';
        }
        
        $html .= '<div class="site-title">
                        <h1>' . htmlspecialchars($site_title_th) . '</h1>
                        <h2>' . htmlspecialchars($site_title_en) . '</h2>
                    </div>
                </div>
                <div class="search-container">
                    <form action="search.php" method="get" class="search-form">
                        <input type="text" name="search" placeholder="ค้นหาข้อมูล...">
                        <button type="submit" aria-label="ค้นหา"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>';
        
        return $html;
    }
    
    /**
     * รวม widget ส่วนล่างสำหรับติดต่อ
     * @return string
     */
    public function renderContactWidget() {
        // ดึงข้อมูลจากกลุ่มการตั้งค่าที่เกี่ยวข้อง
        $contact_settings = $this->getByGroup('contact');
        
        $contact_address = isset($contact_settings['contact_address']) ? $contact_settings['contact_address'] : '';
        $contact_phone = isset($contact_settings['contact_phone']) ? $contact_settings['contact_phone'] : '';
        $contact_email = isset($contact_settings['contact_email']) ? $contact_settings['contact_email'] : '';
        
        // สร้าง HTML สำหรับ widget
        $html = '<div class="footer-contact">';
        
        if (!empty($contact_address)) {
            $html .= '<p><i class="fas fa-map-marker-alt"></i> ' . nl2br(htmlspecialchars($contact_address)) . '</p>';
        }
        
        if (!empty($contact_phone)) {
            $html .= '<p><i class="fas fa-phone"></i> ' . htmlspecialchars($contact_phone) . '</p>';
        }
        
        //$html .= '<p><i class="fas fa-fax"></i> 02-XXX-XXXX</p>';
        
        if (!empty($contact_email)) {
            $html .= '<p><i class="fas fa-envelope"></i> ' . htmlspecialchars($contact_email) . '</p>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * รวม widget ลิขสิทธิ์
     * @return string
     */
    public function renderCopyrightWidget() {
        // ดึงข้อมูลจากกลุ่มการตั้งค่าที่เกี่ยวข้อง
        $footer_settings = $this->getByGroup('footer');
        
        $footer_copyright = isset($footer_settings['footer_copyright']) ? $footer_settings['footer_copyright'] : '© ' . date('Y') . ' โรงเรียนเทพปัญญา. สงวนลิขสิทธิ์.';
        $footer_credits = isset($footer_settings['footer_credits']) ? $footer_settings['footer_credits'] : '';
        
        // สร้าง HTML สำหรับ widget
        $html = '<div class="footer-copyright">';
        $html .= '<p>' . htmlspecialchars($footer_copyright) ;
        
        if (!empty($footer_credits)) {
            $html .= ' '. htmlspecialchars($footer_credits) ;
        }
        
        $html .= '</p></div>';
        
        return $html;
    }
}
?>