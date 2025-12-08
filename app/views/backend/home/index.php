<?php view()->render('backend/_header', array(
        'title' => $title,
)); ?>

    <h1><?php echo $title ?></h1>

    <div class="slogan">
        Backend moudle index show.
    </div>

    <div class="backend-area">
        <p>
            <a href="/">
                ➡️ Back Home
            </a>
        </p>
    </div>
<?php view()->render('backend/_footer'); ?>