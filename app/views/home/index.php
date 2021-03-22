<?php View::getInstance()->render('_header', array(
    'title' => 'Yophp home'
)); ?>

<h3>Hello Yophp</h3>
<div>test data:</div>

<?php if($test):?>
<ul>
    <?php foreach ($test as $key=>$value):?>
    <li><?php echo $value['name']?> - <?php echo $value['age'] ?>岁</li>
    <?php endforeach;?>
</ul>
<?php endif;?>

<div><?php echo $var ?></div>

<?php View::getInstance()->render('_footer');?>