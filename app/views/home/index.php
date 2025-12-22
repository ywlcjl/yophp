<?php view()->render('_header', array(
        'title' => $title.' Index',
)); ?>

        <h1 class="main-title">
            <?php echo $title?>
        </h1>

        <div class="slogan">
            A simple PHP framework with performance close to native PHP.
        </div>

        <h3 class="section-title">
            🚀 Test Examples
        </h3>

        <div class="link-group">
            <a href="/example/index">controller example index</a>
            <a href="/example/json" target="_blank">test json</a>
            <a href="/example/detail/1">test detail</a>
            <a href="/example/edit">test save</a>
            <a href="/example/sql">test sql</a>
            <a href="/example/cache" target="_blank">test cache</a>
            <a href="/example/cacheRedis" target="_blank">test redis cache</a>
            <a href="/example/page">test page</a>
            <a href="/example/pagesql">test pagesql</a>
            <a href="/e/2">test route</a>
            <a href="/e/2/jacky">test routeWithName</a>
        </div>

        <div class="backend-area">
            <p>
                <a href="/backend/home">
                    ➡️ Go Backend
                </a>
            </p>
        </div>

<?php view()->render('_footer'); ?>