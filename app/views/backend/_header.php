<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            /* 基础样式和字体 */
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #e9ecef; /* 浅灰色背景 */
                color: #343a40; /* 深灰色文字 */
            }

            /* 容器样式，居中和卡片效果 */
            .main-content {
                max-width: 600px;
                margin: 50px auto;
                padding: 40px;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.15);
                text-align: center;
            }

            /* 主标题样式 */
            h1 {
                color: #343a40;
                margin-top: 0;
                margin-bottom: 10px;
                font-size: 28px;
                border-bottom: 2px solid #343a40;
                display: inline-block; /* 让底边只跟随文字宽度 */
                padding-bottom: 5px;
            }

            /* 标语/描述样式 */
            .slogan {
                margin-bottom: 40px;
                color: #6c757d;
                font-style: italic;
            }

            /* 返回链接区域容器 */
            .backend-area p {
                margin: 0; /* 移除段落默认外边距 */
            }

            /* 返回链接按钮样式 */
            .backend-area a {
                text-decoration: none;
                padding: 12px 25px;
                background-color: #007bff; /* 蓝色按钮 */
                color: white;
                border-radius: 5px;
                font-weight: bold;
                display: inline-block; /* 使 padding 生效 */
                transition: background-color 0.3s ease;
            }


        </style>
    </head>
    <body>
    <div class="main-content">
