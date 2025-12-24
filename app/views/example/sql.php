<?php view()->loadView('_header'); ?>

    <h1 class="main-title"><?php echo $title; ?></h1>
    <div class="slogan">SQL Query: <?php echo $sql ?></div>

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
                        <td class="time-text"><?php echo $value['create_time']?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <? else: ?>
            no result.
        <?php endif; ?>
    </div>

    <div class="footer-area">
        <div class="current-status"></div>
        <a href="/" class="back-link">← Back</a>
    </div>

<?php view()->loadView('_footer'); ?>