<?php view()->render('_header', array(
        'title' => $title
));
?>
    <h1 class="main-title"><?php echo $title; ?></h1>
<?php if (isset($sql)) : ?>
    <div class="slogan">SQL Query: <?php echo $sql ?></div>
<?php endif; ?>
    <div class="data-table-container">
        <?php if ($examples): ?>
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>描述</th>
                    <th>状态</th>
                    <th>创建时间</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($examples as $key => $value): ?>
                    <tr>
                        <td><?php echo $value['id'] ?></td>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['desc_txt'] ?></td>
                        <td class="status-enabled"><?php echo $statuss[$value['status']]; ?></td>
                        <td class="time-text"><?php echo $value['create_time'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <? else: ?>
            no result.
        <?php endif; ?>
    </div>

    <div class="pagination-container"><?php echo view()->getPageNav() ?></div>

    <div class="footer-area">
        <div class="current-status">Current status: <?php echo $status; ?> <a href="<?php echo $pageUrl ?>/?status=1">status=1</a>
            <a href="<?php echo $pageUrl ?>/?status=0">status=0</a></div>
        <div>
            <a href="/" class="back-link">← Back</a>
        </div>
    </div>

<?php view()->render('_footer'); ?>