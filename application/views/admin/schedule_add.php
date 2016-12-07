<div class="row">
    <?php if (validation_errors()): ?>
    <div id="message" class="alert alert-danger" onclick="HideMessage();">
        <?php echo validation_errors(); ?>
    </div>
    <?php elseif (isset($message) && $message): ?>
    <div class="col-md-12">
        <div id="message" class="alert alert-danger" onclick="HideMessage();">
            <?php echo $message; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-md-12">
        <h1>เพิ่มการตรวจสอบ</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <form id="course_form" data-toggle="validator" role="form" method="post">
            <div class="form-group">
                <label for="course_year">ปีการศึกษา</label>
                <select class="form-control" id="course_year" name="course_year" required="">
                    <option value="">กรุณาเลือกปีการศึกษา</option>
                    <?php if (isset($courseYearLists) && is_array($courseYearLists)): ?>
                    <?php foreach ($courseYearLists as $key => $value): ?>
                    <option value="<?php echo $key ?>" <?php echo isset($_POST['course_year']) && $_POST['course_year'] == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="course_id">หลักสูตร</label>
                <select class="form-control" id="course_id" name="course_id" required="">
                    <option value="">กรุณาเลือกหลักสูตร</option>
                </select>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="execute_day">วันตรวจสอบ</label>
                        <?php if (isset($executeDays) && is_array($executeDays)): ?>
                        <?php foreach ($executeDays as $key => $value): ?>
                        <div class="checkbox">
                          <label>
                            <input name="execute_day[]" type="checkbox" value="<?php echo $key; ?>" <?php echo isset($daySelects[$key]) ? 'checked' : ''; ?>>
                            <?php echo $value; ?>
                          </label>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="execute_month">เดือนตรวจสอบ</label>
                        <?php if (isset($executeMonths) && is_array($executeMonths)): ?>
                        <?php foreach ($executeMonths as $key => $value): ?>
                        <div class="checkbox">
                          <label>
                            <input name="execute_month[]" type="checkbox" value="<?php echo $key; ?>" <?php echo isset($monthSelects[$key]) ? 'checked' : ''; ?>>
                            <?php echo $value; ?>
                          </label>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="execute_time">เวลา</label>
                <select class="form-control" id="execute_time" name="execute_time" required="">
                    <?php for ($i = 0;$i <= 23; ++$i): ?>
                    <?php
                    if ($i < 10) {
                        $time = '0'.$i.':00';
                    } else {
                        $time = $i.':00';
                    }
                    ?>
                    <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="schedule_active">สถานะ</label>
                <select class="form-control" id="schedule_active" name="schedule_active" required="">
                    <option value="1">ใช้งาน</option>
                    <option value="0">ไม่ใช้งาน</option>
                </select>
            </div>
            <div class="pull-right">
                <a class="btn btn-default" href="<?php echo base_url('admin/schedules'); ?>">ยกเลิก</a>
                <button type="submit" class="btn btn-primary">เพิ่ม</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#course_year').change(function(){
            getCourseYear();
        });
    });
    var getCourseYear = function() {
        var param = {
            course_year: $('#course_year').val()
        }
        var options = {
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded',
            type: 'POST',
            url: BASE_URL + 'api/getCourseYear',
            data: param
        }
        $.ajax(options).done(function(res) {
            //console.log('result', res);
            var courses = '';
            if (res.result == 1 && res.data) {
                courses = '<option value="">กรุณาเลือกหลักสูตร</option>';
                $.each(res.data, function(key, value) {
                    courses += '<option value="' + value.course_id + '">' + value.course_name + '</option>';
                });
            } else {
              courses = '<option value="">ยังไม่มีหลักสูตรในปีการศึกษา ' + (parseInt($('#course_year').val()) + 543) + '</option>';
            }
            $('#course_id').html(courses);
        }).error(function(err) {
            //console.log('error ', err);
            var courses = '<option value="">ยังไม่มีหลักสูตรในปีการศึกษา ' + (parseInt($('#course_year').val()) + 543) + '</option>';
            $('#course_id').html(courses);
        });
    };
</script>
