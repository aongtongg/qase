<div class="row">
    <?php if (validation_errors()): ?>
    <div id="message" class="alert alert-danger" onclick="HideMessage();">
        <?php echo validation_errors(); ?>
    </div>
    <?php endif; ?>
    <div class="col-md-12">
        <h1>แก้ไขหลักสูตร</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <form id="course_form" data-toggle="validator" role="form" method="post">
            <div class="form-group">
                <label for="teacher_id">อาจารย์</label>
                <select class="form-control" id="teacher_id" name="teacher_id" required="">
                    <option value="">กรุณาเลือกอาจารย์</option>
                    <?php foreach ($teacherLists as $key => $value): ?>
                    <option value="<?php echo $key ?>" <?php echo $teacher_id == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="role_id">บทบาท</label>
                <select class="form-control" id="role_id" name="role_id" required="">
                    <option value="">กรุณาเลือกบทบาท</option>
                    <?php foreach ($roleLists as $key => $value): ?>
                      <option value="<?php echo $key ?>" <?php echo $role_id == $key ? 'selected' : '';  ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="pull-right">
                <a class="btn btn-default" href="<?php echo base_url('admin/teacher_has_courses/'.$course_year.'/'.$course_id); ?>">ยกเลิก</a>
                <button type="submit" name="delete" class="btn btn-danger">ลบ</button>
                <button type="submit" name="save" class="btn btn-primary">แก้ไข</button>
            </div>
        </form>
    </div>
</div>
<script>
    $('.btn-danger').on('click', function(e) {
        if(!confirm('คุณต้องการที่จะลบบทบาทนี้?')){
             event.preventDefault();
         }
    });
</script>
