<?php view()->render('_header', array(
        'title' => $title
));
?>
    <h1 class="main-title"><?php echo $title; ?></h1>
<?php if (isset($sql)) : ?>
    <div class="slogan">SQL Query: <?php echo $sql ?></div>
<?php endif; ?>
<?php if(isset($message) && $message != ''):?>
    <div class="alert<?php if($success == '1') :?> alert-success<?php else:?> alert-error<?php endif;?>"><?php echo $message?></div>
<?php endif;?>

    <div class="data-table-container">
        <?php if (isset($examples) && $examples): ?>
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>描述</th>
                    <th>状态</th>
                    <th>更新时间</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($examples as $key => $value): ?>
                    <tr>
                        <td><?php echo $value['id'] ?></td>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['desc_txt'] ?></td>
                        <td class="status-enabled"><?php echo $statuss[$value['status']]; ?></td>
                        <td class="time-text"><?php echo $value['update_time'] ?></td>
                        <td class="time-text"><?php echo $value['create_time'] ?></td>
                        <td>
                            <a href="/example/edit/?id=<?php echo $value['id'] ?>">编辑</a>
                            <form action="/example/delete/" method="POST" onsubmit="return confirm('确定要删除吗？')">
                                <input type="hidden" name="id" value="<?php echo $value['id'] ?>">
                                <button type="submit">删除</button>
                            </form>
                        </td>
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
        <div class="current-status">
            <a href="/example/edit" class="back-link">Add</a>
            Current status: <?php echo $status; ?> <a href="<?php echo $pageUrl ?>/?status=1">status=1</a>
            <a href="<?php echo $pageUrl ?>/?status=0">status=0</a></div>
        <div>
            <a href="/" class="back-link">← Back</a>
        </div>
    </div>

<?php view()->render('_footer'); ?>