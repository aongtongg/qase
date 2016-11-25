<div class="row">
    <?php if (validation_errors()): ?>
    <div id="message" class="alert alert-danger" onclick="HideMessage();">
        <?php echo validation_errors(); ?>
    </div>
    <?php endif; ?>
    <div class="col-md-12">
        <h1>เพิ่มหลักสูตร</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <form id="course_form" data-toggle="validator" role="form" method="post">
            <?php if (!$course_year && !$course_id): ?>
            <div class="form-group">
                <label for="course_year">ปีการศึกษา</label>
                <select class="form-control" id="course_year" name="course_year" required="">
                    <option value="">กรุณาเลือกปีการศึกษา</option>
                    <?php foreach ($courseYearLists as $key => $value): ?>
                    <option value="<?php echo $key ?>" <?php echo isset($_POST['course_year']) && $_POST['course_year'] == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="course_id">หลักสูตร</label>
                <select class="form-control" id="course_id" name="course_id" required="">
                    <option value="">กรุณาเลือกหลักสูตร</option>
                    <?php foreach ($courseLists as $key => $value): ?>
                    <option value="<?php echo $key ?>" <?php echo isset($_POST['course_id']) && $_POST['course_id'] == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif;?>
            <div class="form-group">
                <label for="teacher_id">อาจารย์</label>
                <select class="form-control" id="teacher_id" name="teacher_id" required="">
                    <option value="">กรุณาเลือกอาจารย์</option>
                    <?php foreach ($teacherLists as $key => $value): ?>
                    <option value="<?php echo $key ?>" <?php echo isset($_POST['teacher_id']) && $_POST['teacher_id'] == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="role_id">บทบาท</label>
                <select class="form-control" id="role_id" name="role_id" required="">
                    <option value="">กรุณาเลือกบทบาท</option>
                    <?php foreach ($roleLists as $key => $value): ?>
                      <option value="<?php echo $key ?>" <?php echo isset($_POST['role_id']) && $_POST['role_id'] == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="pull-right">
                <?php if ($course_year && $course_id): ?>
                <a class="btn btn-default" href="<?php echo base_url('admin/teacher_has_courses/'.$course_year.'/'.$course_id); ?>">ยกเลิก</a>
                <?php else: ?>
                <a class="btn btn-default" href="<?php echo base_url('admin/teacher_has_courses/'); ?>">ยกเลิก</a>
                <?php endif; ?>
                <button type="submit" name="save" class="btn btn-primary">เพิ่ม</button>
            </div>
        </form>
    </div>
</div>
