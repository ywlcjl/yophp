<?php view()->render('_header', array(
        'title' => $title
));
?>

    <h1 class="main-title"><?php echo $title; ?></h1>
<?php if (isset($message) && $message != ''): ?>
    <div class="alert alert-error"><?php echo $message ?></div>
<?php endif; ?>
    <div class="form-container">
        <form method="post" action="/example/save">
            <input type="hidden" name="id" value="<?php echo $detail['id']??'' ?>">
            <div class="form-field-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $detail['name']??'' ?>" required placeholder="输入名称"></li>
            </div>

            <div class="form-field-group">
                <label for="desc_txt">DescTxt:</label>
                <textarea id="desc_txt" name="desc_txt" rows="5"  placeholder="输入描述"><?php echo $detail['desc_txt']??'' ?></textarea>
            </div>

            <div class="form-field-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="" disabled<?php if(isset($id) && !$id):?>selected<?php endif;?>>请选择</option>
                    <?php if ($statuss) : ?>
                        <?php foreach ($statuss as $k => $v): ?>
                            <option value="<?php echo $k ?>" <?php if (isset($detail['status']) &&  $detail['status'] === $k) echo 'selected' ?>><?php echo $v ?></option>
                        <?php endforeach; ?>
                    <?php endif ?>
                </select>
            </div>
            <div class="submit-container">
                <button type="submit">提交</button>
            </div>
        </form>
    </div>

    <div class="footer-area">
        <div class="current-status"></div>
        <div>
            <a href="/example/page" class="back-link">← Back</a>
        </div>
    </div>

<?php view()->render('_footer'); ?>