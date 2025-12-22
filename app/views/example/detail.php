<?php view()->render('_header', array(
        'title' => $title
));
?>

    <h1 class="main-title"><?php echo $title; ?></h1>

    <div class="data-table-container">
        <?php if ($detail): ?>
            <table class="data-table">
                <thead>
                <tr>
                    <th>键</th>
                    <th>值</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>ID:</td>
                    <td><?php echo $detail['id'] ?></td>
                </tr>
                <tr>
                    <td>名称:</td>
                    <td><?php echo $detail['name'] ?></td>
                </tr>
                <tr>
                    <td>明细:</td>
                    <td><?php echo $detail['desc_txt'] ?></td>
                </tr>
                <tr>
                    <td>状态:</td>
                    <td class="status-enabled"><?php echo $statuss[$detail['status']]; ?></td>
                </tr>
                <tr>
                    <td>更新:</td>
                    <td class="time-text"><?php echo $detail['update_time'] ?></td>
                </tr>
                <tr>
                    <td>创建:</td>
                    <td class="time-text"><?php echo $detail['create_time'] ?></td>
                </tr>
                </tbody>
            </table>
        <? else: ?>
            no result.
        <?php endif; ?>
    </div>

    <div class="footer-area">
        <div class="current-status"><a href="/example/edit/?id=<?php echo $detail['id']??'' ?>" class="back-link">Edit</a></div>
        <div>
            <a href="/" class="back-link">← Back</a>
        </div>
    </div>

<?php view()->render('_footer'); ?>