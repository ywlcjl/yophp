<?php view()->render('_header', array(
        'title' => 'Yo page'
));
$userModel = UserModel::getInstance();
?>

    <h1>User Page Test</h1>

<?php if($users):?>
    <ul>
        <?php foreach ($users as $key=>$value):?>
            <li><?php echo $value['name']?> - <?php echo $value['age'] ?>岁 - <?php echo $userModel->getSexName($value['sex']);?></li>
        <?php endforeach;?>
    </ul>
<?php endif;?>

    <div><?php echo view()->getPage() ?></div>
    <div><a href="<?php echo APP_URL ?>/">Back Home</a></div>

<?php view()->render('_footer');?>