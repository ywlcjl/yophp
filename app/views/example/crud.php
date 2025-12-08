<?php view()->render('_header', array(
        'title' => 'Yophp User crud'
));
$userModel = UserModel::getInstance();
?><h1>Test Crud:</h1>

    <div class="slogan">getRow: <?php echo $user['name']; ?></div>

    <div>
        getResult: <?php echo $user['name']; ?>
    </div>
<?php if ($result): ?>
    <div>
        <ul>
            <?php foreach ($result as $key => $value): ?>
                <li><?php echo $value['name'] ?> - <?php echo $value['age'] ?>
                    岁- <?php echo $userModel->getSexName($value['sex']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
    <div>count: <?php echo $count; ?></div>
    <div>query SQL: <?php echo $sql; ?></div>
    <div>query Data: <?php echo $queryData[0]['name']; ?></div>
    <div>insert id: <?php echo $insertId; ?></div>
    <div>update row: <?php echo $update; ?></div>

    <div><?php echo $var ?></div>
    <div><a href="<?php echo APP_URL ?>/">Back Home</a></div>

<?php view()->render('_footer'); ?>