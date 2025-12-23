<?php

/**
 * YoUpload - YoPHP 极简上传类
 * 特点：按需加载、MIME 真伪校验、日期子目录自动创建
 */
class YoUpload
{
    private $basePath;      // 基础上传根目录
    private $subPath;       // 日志生成的子目录 (如 2025/12/23)
    private $allowedTypes;  // 允许的后缀
    private $maxSize;       // 最大限制 (KB)
    private $encryptName;   // 是否加密文件名

    private $error = '';
    private $fileData = [];

    public function __construct($config = [])
    {
        $this->basePath = isset($config['upload_path']) ? rtrim($config['upload_path'], '/') . '/' : './uploads/';
        $this->allowedTypes = isset($config['allowed_types']) ? explode('|', strtolower($config['allowed_types'])) : ['jpg', 'png', 'gif', 'jpeg'];
        $this->maxSize = isset($config['max_size']) ? (int)$config['max_size'] : 2048;
        $this->encryptName = isset($config['encrypt_name']) ? (bool)$config['encrypt_name'] : true;
    }

    /**
     * 执行上传
     */
    public function doUpload($field = 'file')
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            $this->error = '请选择上传文件';
            return false;
        }

        $file = $_FILES[$field];

        // 1. 基础 PHP 错误检查
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error = '上传错误代码: ' . $file['error'];
            return false;
        }

        // 2. 动态计算日期子目录并创建
        $this->subPath = date('Y/m/d') . '/';
        $fullPath = $this->basePath . $this->subPath;

        if (!is_dir($fullPath)) {
            // 第三个参数 true 表示递归创建 uploads/2025/12/23/
            if (!mkdir($fullPath, 0777, true)) {
                $this->error = '无法创建上传目录：' . $fullPath;
                return false;
            }
        }

        // 3. 后缀名初步过滤
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedTypes)) {
            $this->error = '不允许的文件后缀';
            return false;
        }

        // 4. MIME 类型真伪校验 (深度防伪)
        if (class_exists('finfo')) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $realMime = $finfo->file($file['tmp_name']);
            if (!$this->isMimeAllowed($ext, $realMime)) {
                $this->error = '文件内容与后缀不匹配，上传被拦截';
                return false;
            }
        }

        // 5. 验证大小
        if ($file['size'] > ($this->maxSize * 1024)) {
            $this->error = '文件大小超出限制';
            return false;
        }

        // 6. 生成安全文件名
        $newName = $this->encryptName
            ? md5(uniqid(mt_rand(), true)) . '.' . $ext
            : $this->sanitizeFilename($file['name']);

        // 7. 移动文件
        $targetFile = $fullPath . $newName;
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $this->fileData = [
                'file_name' => $newName,
                'sub_path' => $this->subPath,           // 2025/12/23/
                'full_path' => $targetFile,              // ./uploads/2025/12/23/xxx.jpg
                'relative_path' => $this->subPath . $newName, // 存入数据库推荐此字段
                'file_ext' => '.' . $ext,
                'file_size' => round($file['size'] / 1024, 2),
                'file_mime' => isset($realMime) ? $realMime : $file['type']
            ];
            return true;
        }

        $this->error = '文件保存失败';
        return false;
    }

    /**
     * MIME 映射表
     */
    private function isMimeAllowed($ext, $realMime)
    {
        $mimes = [
            'jpg' => ['image/jpeg', 'image/pjpeg'],
            'jpeg' => ['image/jpeg', 'image/pjpeg'],
            'png' => ['image/png', 'image/x-png'],
            'gif' => ['image/gif'],
            'pdf' => ['application/pdf'],
            'zip' => ['application/zip', 'application/x-zip-compressed'],
            'txt' => ['text/plain'],
        ];
        return isset($mimes[$ext]) && in_array($realMime, $mimes[$ext]);
    }

    /**
     * 清洗非加密文件名中的特殊字符
     */
    private function sanitizeFilename($filename)
    {
        return preg_replace('/[^a-zA-Z0-9\._-]/', '', $filename);
    }

    public function getError()
    {
        return $this->error;
    }

    public function data()
    {
        return $this->fileData;
    }
}