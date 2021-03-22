<?php Yo_View::getInstance()->render('backend/_header', array(
    
)); ?>

<h3>Yophp Backend</h3>

<?php if($tests):?>
<ul>
    <?php foreach ($tests as $key=>$value):?>
    <li><?php echo $key?> : <?php echo $value ?></li>
    <?php endforeach;?>
</ul>
<?php endif;?>

<?php view()->render('backend/_footer');?>