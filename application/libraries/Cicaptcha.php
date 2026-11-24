<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Cicaptcha
{
  function __construct() {
    $this->ci =& get_instance();
  }

  /**
   * Generate standard CAPTCHA menggunakan inline SVG murni (tanpa butuh ekstensi GD
   * di php.ini dan tanpa butuh izin tulis folder), sehingga langsung berjalan normal
   * di semua versi XAMPP / PHP (7.4 - 8.x) tanpa setting ulang.
   */
  function show() {
    $word = '';
    for ($i = 0; $i < 5; $i++) {
      $word .= (string) mt_rand(0, 9);
    }

    $now = time();
    $ip  = substr((string) $this->ci->input->ip_address(), 0, 16);

    $data = array(
      'captcha_time' => $now,
      'ip_address'   => $ip,
      'word'         => $word
    );

    $query = $this->ci->db->insert_string('captcha', $data);
    $this->ci->db->query($query);

    $width  = 110;
    $height = 32;

    // Garis pengacak latar (anti-bot)
    $noise = '';
    for ($l = 0; $l < 5; $l++) {
      $x1 = mt_rand(0, $width);
      $y1 = mt_rand(0, $height);
      $x2 = mt_rand(0, $width);
      $y2 = mt_rand(0, $height);
      $noise .= '<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" stroke="#cbd5e1" stroke-width="1"/>';
    }
    for ($d = 0; $d < 18; $d++) {
      $cx = mt_rand(4, $width - 4);
      $cy = mt_rand(4, $height - 4);
      $noise .= '<circle cx="'.$cx.'" cy="'.$cy.'" r="1" fill="#94a3b8"/>';
    }

    // Angka captcha miring acak
    $chars = '';
    for ($i = 0; $i < strlen($word); $i++) {
      $x   = 14 + ($i * 18);
      $y   = mt_rand(22, 26);
      $rot = mt_rand(-14, 14);
      $chars .= '<text x="'.$x.'" y="'.$y.'" fill="#d92626" font-family="Arial,Helvetica,sans-serif" font-size="20" font-weight="bold" transform="rotate('.$rot.' '.$x.','.$y.')">'.$word[$i].'</text>';
    }

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">'
         . '<rect width="'.$width.'" height="'.$height.'" fill="#ffffff"/>'
         . $noise
         . $chars
         . '</svg>';

    return '<img src="data:image/svg+xml;base64,'.base64_encode($svg).'" width="'.$width.'" height="'.$height.'" style="display:block;border:1px solid #cbd5e1;border-radius:2px;" alt="Captcha" />';
  }
}
