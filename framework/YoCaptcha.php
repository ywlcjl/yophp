<?php
class YoCaptcha {
    private $width = 120;
    private $height = 40;
    private $codeNum = 4;
    private $checkCode = '';
    private $img;

    public function __construct($width = 120, $height = 40, $codeNum = 4) {
        $this->width = $width;
        $this->height = $height;
        $this->codeNum = $codeNum;
    }

    // 生成随机代码
    private function createCode() {
        $pool = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ'; // 去掉易混淆的 0,1,o,l
        $this->checkCode = '';
        for ($i = 0; $i < $this->codeNum; $i++) {
            $this->checkCode .= $pool[mt_rand(0, strlen($pool) - 1)];
        }
    }

    // 获取生成的验证码（用于存 Session）
    public function getCode() {
        return strtolower($this->checkCode);
    }

    // 核心渲染方法
    public function doImage() {
        $this->createCode();

        $this->img = imagecreatetruecolor($this->width, $this->height);

        // 背景色
        $bgColor = imagecolorallocate($this->img, 255, 255, 255);
        imagefill($this->img, 0, 0, $bgColor);

        // 随机干扰点（噪点）
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(150, 220), mt_rand(150, 220), mt_rand(150, 220));
            imagesetpixel($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }

        // 随机干扰线（不规则）
        for ($i = 0; $i < 5; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(100, 200), mt_rand(100, 200), mt_rand(100, 200));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }

        // 绘制文字
        for ($i = 0; $i < $this->codeNum; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
            $fontSize = 5; // 使用内置字体（无需额外加载字体文件，速度最快）
            $x = ($this->width / $this->codeNum) * $i + mt_rand(5, 10);
            $y = mt_rand(5, $this->height / 2);
            imagechar($this->img, $fontSize, $x, $y, $this->checkCode[$i], $color);
        }

        // 直接输出（符合你之前讨论的直接输出场景）
        header("Content-Type: image/png");
        ob_clean();
        imagepng($this->img);
        imagedestroy($this->img);
    }
}