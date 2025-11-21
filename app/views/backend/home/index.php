<?php Yo_View::getInstance()->render('backend/_header', array(
    'title' => "Yophp Backend",
)); ?>

<h1>Yophp Backend</h1>

<div>
<?php if($users):?>
<ul>
    <?php foreach ($users as $key=>$value):?>
    <li><?php echo $key?> : <?php echo $value ?></li>
    <?php endforeach;?>
</ul>
<?php endif;?>
</div>
<div>
    <p><a href="<?php echo APP_URL ?>/">Back Home</a></p>
</div>
<?php view()->render('backend/_footer');?>