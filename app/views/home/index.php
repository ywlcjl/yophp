<?php view()->render('_header', array(
        'title' => 'Yophp home'
)); ?>

    <h1>Yophp Index</h1>
    <div>User data:</div>
    <div>
        <?php if ($users): ?>
            <ul>
                <?php foreach ($users as $key => $value): ?>
                    <li><?php echo $value['name'] ?> - <?php echo $value['age'] ?>岁
                        - <?php echo $sexNames[$value['sex']] ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div><?php echo $var ?></div>

    <h3>Test User</h3>
    <div>
        <a href="<?php echo APP_URL ?>/user/index">controller user index</a>&nbsp;
        <a href="<?php echo APP_URL ?>/user/crud">test crud</a>&nbsp;
        <a href="<?php echo APP_URL ?>/user/sql">test sql</a>&nbsp;
        <a href="<?php echo APP_URL ?>/user/cache">test cache</a>&nbsp;
        <a href="<?php echo APP_URL ?>/user/cacheRedis">test cacheRedis</a>&nbsp;
        <a href="<?php echo APP_URL ?>/user/page">test page</a>&nbsp;
        <a href="<?php echo APP_URL ?>/user/pagesql/?sex=1">test pagesql</a>&nbsp;
        <a href="<?php echo APP_URL ?>/u">test router</a>&nbsp;
    </div>
    <div>
        <p>&nbsp;</p>
        <p><a href="<?php echo APP_URL ?>/backend/home">go backend</a></p>
        <p>&nbsp;</p>
    </div>

<?php view()->render('_footer'); ?>