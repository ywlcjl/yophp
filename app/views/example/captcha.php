<?php view()->loadView('_header'); ?>

    <h1 class="main-title"><?php echo $title; ?></h1>
    <?php if (isset($message) && $message != ''): ?>
    <div class="alert alert<?php if(isset($success) && $success):?>-success<?php else:?>-error<?php endif?>"><?php echo $message ?></div>
    <?php endif; ?>
    <div class="form-container">
        <form method="post" action="/example/captcha">
            <div class="form-field-group">
                Captcha Image:
                <img src="/example/getCaptchaImage" >
            </div>

            <div class="form-field-group">
                <label for="captcha">Captcha:</label>
                <input type="text" id="captcha" name="captcha" value="" required maxlength="5" placeholder="Input image captcha">
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

<?php view()->loadView('_footer'); ?>