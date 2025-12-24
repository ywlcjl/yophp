<?php view()->loadView('_header'); ?>

    <h1 class="main-title"><?php echo $title; ?></h1>
<?php if (isset($message) && $message != ''): ?>
    <div class="alert alert<?php if(isset($success) && $success):?>-success<?php else:?>-error<?php endif?>"><?php echo $message ?></div>
<?php endif; ?>
    <div class="data-table-container">
        <table class="data-table">
            <thead>
            <tr>
                <th>条目</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Image:</td>
                <td><?php if(isset($uploadData) && $uploadData): ?>
                    <a href="/<?php echo $uploadData['relativePath'] ?>" target="_blank">
                        <img src="/<?php echo $uploadData['relativePath'] ?>" style="max-width: 480px;" >
                    </a>
                    <?php endif?>
                </td>
            </tr>
            <tr>
                <td>Image thumb:</td>
                <td>
                    <?php if(isset($thumbPath) && $thumbPath): ?>
                    <a href="/<?php echo $thumbPath ?>" target="_blank">
                        <img src="/<?php echo $thumbPath ?>" >
                    </a>
                    <?php endif?>
                </td>
            </tr>
            <tr>
                <td>Image data:</td>
                <td><?php if(isset($uploadData) && $uploadData){print_r($uploadData);} ?></td>
            </tr>
            <tr>
                <td>Upload:</td>
                <td>
                    <form method="post" action="/example/upload" enctype="multipart/form-data">
                        <input type="file" name="file">
                        <input type="submit" name="submit" value="upload">
                    </form>
                    <p>Allow png,jpg,jpeg, filesize limit 10m</p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="footer-area">
        <div class="current-status"></div>
        <div>
            <a href="/" class="back-link">← Back</a>
        </div>
    </div>

<?php view()->loadView('_footer'); ?>