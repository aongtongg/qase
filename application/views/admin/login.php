<div class="row">
    <?php if ($message): ?>
    <div id="message" class="alert alert-danger" onclick="HideMessage();">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    <div class="col-md-12">
        <h1>เข้าสู่ระบบ</h1>
    </div>
    <div class="col-md-12">
        <form id="loginForm" data-toggle="validator" role="form" method="post">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="ชื่อผู้ใช้" required="">
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" class="form-control" id="passowrd" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>" placeholder="รหัสผ่าน" required="">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<script>
    var HideMessage = function() {
        $('#message').fadeOut();
    }
</script>
