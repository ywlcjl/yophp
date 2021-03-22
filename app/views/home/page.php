<?php view()->render('_header', array(
    'title' => 'Yo page'
)); ?>

<h3>Page Test</h3>
<div>test data:</div>

<?php if($tests):?>
<ul>
    <?php foreach ($tests as $key=>$value):?>
    <li><?php echo $value['name']?> - <?php echo $value['age'] ?>岁</li>
    <?php endforeach;?>
</ul>
<?php endif;?>

<div><?php echo view()->getPage() ?></div>

<?php view()->render('_footer');?>