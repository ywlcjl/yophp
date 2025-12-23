<?php view()->render('_header', array(
    'title' => $title
));
?>

    <h1 class="main-title"><?php echo $title; ?></h1>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
            <tr>
                <th>条目</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Cookie Full Name:</td>
                <td><?php echo $cookieName??'' ?></td>
            </tr>
            <tr>
                <td>添加Name的Cookie:</td>
                <td><a href="/example/addCookie" target="_blank">添加cookie</a></td>
            </tr>
            <tr>
                <td>删除Name的Cookie:</td>
                <td><a href="/example/delCookie" target="_blank">删除cookie</a></td>
            </tr>
            <tr>
                <td>清空网站Cookie:</td>
                <td><a href="/example/clearCookie" target="_blank">清空cookie</a></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="footer-area">
        <div class="current-status"></div>
        <div>
            <a href="/" class="back-link">← Back</a>
        </div>
    </div>

<?php view()->render('_footer'); ?>