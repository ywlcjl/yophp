<?php view()->render('_header', array(
        'title' => 'Yophp User crud'
));
$userModel = UserModel::getInstance();
?><h1>Sql Query Test:</h1>

<?php if($queryData):?>
    <div>
        <ul>
            <?php foreach ($queryData as $key=>$value):?>
                <li><?php echo $value['name']?> - <?php echo $value['age'] ?>岁- <?php echo $userModel->getSexName($value['sex']);?></li>
            <?php endforeach;?>
        </ul>
    </div>
<?php endif;?>
    <div>query SQL: <?php echo $sql;?></div>

    <div><?php echo $var ?></div>
    <div><a href="<?php echo APP_URL ?>/">Back Home</a></div>

<?php view()->render('_footer');?>