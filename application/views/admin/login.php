<div class="row">
    <div class="col-md-12">
        <h1>เข้าสู่ระบบ</h1>
    </div>
    <?php if ($message): ?>
    <div class="col-md-12">
        <div id="message" class="alert alert-danger" onclick="HideMessage();">
            <?php echo $message; ?>
        </div>
    </div>
    <?php endif; ?>
    <div id="alert_message" class="col-md-12"></div>
    <div class="col-md-12">
        <form id="loginForm" data-toggle="validator" role="form" method="post">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="ชื่อผู้ใช้" required="">
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>" placeholder="รหัสผ่าน" required="">
            </div>
            <button class="btn btn-primary" id="submit" type="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> กำลังโหลด..">เข้าสู่ระบบ</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#loginForm").submit(function(event) {
            if ($('#username').val() && $('#password').val()) {
                $('#submit').button('loading');
                var param = {
                    username: $('#username').val(),
                    password: $('#password').val()
                }
                var options = {
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded',
                    type: 'POST',
                    url: BASE_URL + 'api/login',
                    data: param
                }
                $.ajax(options).done(function(res) {
                    //console.log('result submit', res);
                    if (res.result) {
                        location.reload();
                    } else {
                        $('#alert_message').html('<div id="message" class="alert alert-danger" onclick="HideMessage();">'+res.message+'</div>');
                        $('#username').parents('.form-group').addClass('has-error');
                        $('#password').parents('.form-group').addClass('has-error');
                        $('#submit').button('reset');
                    }
                }).error(function(err) {
                    //console.log('error ', err);
                    $('#username').parents('.form-group').addClass('has-error');
                    $('#password').parents('.form-group').addClass('has-error');
                    $('#submit').button('reset');
                });
            }
            event.preventDefault();
        });
    });
    var HideMessage = function() {
        $('#message').fadeOut();
    }
</script>
