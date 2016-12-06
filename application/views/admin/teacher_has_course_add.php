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
        <h1>เพิ่มหลักสูตร</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <form id="course_form" data-toggle="validator" role="form" method="post">
            <?php if (!$course_id): ?>
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
            <?php endif;?>
            <div class="form-group">
                <label for="role_id">บทบาท</label>
                <select class="form-control" id="role_id" name="role_id" required="">
                    <option value="">กรุณาเลือกบทบาท</option>
                    <?php if (isset($roleLists) && is_array($roleLists)): ?>
                    <?php foreach ($roleLists as $key => $value): ?>
                    <option value="<?php echo $key ?>" <?php echo isset($_POST['role_id']) && $_POST['role_id'] == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="teacher_id">อาจารย์</label>
                <select class="form-control" id="teacher_id" name="teacher_id" required="">
                    <option value="">กรุณาเลือกอาจารย์</option>
                    <?php if (isset($teacherLists) && is_array($teacherLists)): ?>
                    <?php foreach ($teacherLists as $key => $value): ?>
                    <option value="<?php echo $key ?>" <?php echo isset($_POST['teacher_id']) && $_POST['teacher_id'] == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="pull-right">
                <?php if ($course_id): ?>
                <a class="btn btn-default" href="<?php echo base_url('admin/teacher_has_courses/'.$course_id); ?>">ยกเลิก</a>
                <?php else: ?>
                <a class="btn btn-default" href="<?php echo base_url('admin/teacher_has_courses/'); ?>">ยกเลิก</a>
                <?php endif; ?>
                <button type="submit" name="save" class="btn btn-primary">เพิ่ม</button>
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
