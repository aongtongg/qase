<div class="row">
    <?php if (validation_errors()): ?>
    <div id="message" class="alert alert-danger" onclick="HideMessage();">
        <?php echo validation_errors(); ?>
    </div>
    <?php endif; ?>
    <div class="col-md-12">
        <h1>แก้ไขหลักสูตร "<?php echo $data->course_name; ?>"</h1>
    </div>
</div>
<div class="row">
      <div class="col-md-6">
          <form id="course_form" data-toggle="validator" role="form" method="post">
              <div class="form-group">
                  <label for="course_name">ชื่อหลักสูตร</label>
                  <input type="text" class="form-control" id="course_name" name="course_name" placeholder="ชื่อหลักสูตร" value="<?php echo isset($_POST['course_name']) ? $_POST['course_name'] : $data->course_name; ?>" required="">
              </div>
              <div class="form-group">
                  <label for="course_start_date">วันที่เริ่มหลักสูตร</label>
                  <div class="input-group date">
                      <input type="text" class="form-control" id="course_start_date" name="course_start_date" value="<?php echo isset($_POST['course_start_date']) ? $_POST['course_start_date'] : $data->course_start_date; ?>" data-date-format="yyyy-mm-dd" required="">
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
              </div>
              <div class="form-group">
                  <label for="course_estimate_date">วันกำหนดประเมินหลักสูตร</label>
                  <div class="input-group date">
                      <input type="text" class="form-control" id="course_estimate_date" name="course_estimate_date" value="<?php echo isset($_POST['course_estimate_date']) ? $_POST['course_estimate_date'] : $data->course_estimate_date; ?>" data-date-format="yyyy-mm-dd" required="">
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
              </div>
              <div class="pull-right">
                  <a class="btn btn-default" href="<?php echo base_url(); ?>admin/courses">ยกเลิก</a>
                  <button type="submit" class="btn btn-primary">แก้ไข</button>
              </div>
          </form>
      </div>
</div>
<script>
    $(document).ready(function(){
        $('#course_start_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $('#course_estimate_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
