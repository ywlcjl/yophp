<?php
/**
 * YoImage 图像处理类
 */
class YoImage {
    private $sourceFile;
    private $info;
    private $error = '';

    public function __construct($sourceFile = '') {
        if ($sourceFile) {
            $this->setSource($sourceFile);
        }
    }

    /**
     * 设置源文件并读取属性
     */
    public function setSource($file) {
        if (!file_exists($file)) {
            $this->error = '源文件不存在';
            return false;
        }
        $this->sourceFile = $file;
        $this->info = getimagesize($file); // [0]width, [1]height, [2]type
        return true;
    }

    /**
     * 生成缩略图
     * @param int $width 目标宽度
     * @param int $height 目标高度
     * @param string $savePath 保存路径 (为空则直接输出到浏览器)
     * @param bool $maintainRatio 是否保持比例
     */
    public function thumb($width, $height, $thumbName = 'thumb', $quality=100, $maintainRatio = true) {
        if (!$this->sourceFile) return false;

        $srcW = $this->info[0];
        $srcH = $this->info[1];
        $type = $this->info[2];

        // 计算缩放后的尺寸
        if ($maintainRatio) {
            $ratio = min($width / $srcW, $height / $srcH);
            $dstW = round($srcW * $ratio);
            $dstH = round($srcH * $ratio);
        } else {
            $dstW = $width;
            $dstH = $height;
        }

        // 创建源图句柄 (利用类型常量)
        $srcImg = $this->createImageHandle($this->sourceFile, $type);
        if (!$srcImg) return false;

        // 创建真彩色目标画布
        $dstImg = imagecreatetruecolor($dstW, $dstH);

        // 处理透明度 (针对 PNG)
        if ($type == IMAGETYPE_PNG) {
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
        }

        // 核心：重采样缩放 (比 imagecopyresized 质量更高)
        imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        if($thumbName) {
            //获取完整的缩略图文件路径 例如 /uploads/example/2025/12/23/xxx_thumb.png
            $finalSavePath = getImgPath($this->sourceFile, $thumbName);
        } else {
            //缩放后保存到原始图片
            $finalSavePath = $this->sourceFile;
        }

        // 保存或输出
        $result = $this->output($dstImg, $finalSavePath, $type, $quality);

        // 销毁句柄释放内存 (关键)
        imagedestroy($srcImg);
        imagedestroy($dstImg);

        return $result;
    }

    private function createImageHandle($file, $type) {
        switch ($type) {
            case IMAGETYPE_GIF:  return imagecreatefromgif($file);
            case IMAGETYPE_JPEG: return imagecreatefromjpeg($file);
            case IMAGETYPE_PNG:  return imagecreatefrompng($file);
            default:
                $this->error = '不支持的图片格式';
                return false;
        }
    }

    private function output($img, $path, $type, $quality = 100) {
//        if (!$path && !headers_sent()) {
//            header('Content-Type: ' . image_type_to_mime_type($type));
//        }

        switch ($type) {
            case IMAGETYPE_GIF:  return imagegif($img, $path);
            case IMAGETYPE_JPEG: return imagejpeg($img, $path, $quality);
            case IMAGETYPE_PNG:  return imagepng($img, $path, round(9 - $quality/10)); // PNG 压缩级别 0-9
        }
        return false;
    }

    public function getError() { return $this->error; }
}