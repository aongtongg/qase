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
        <h1>แก้ไขเกณฑ์ของบทบาท "<?php echo $data->role_name; ?>"</h1>
    </div>
</div>
<div class="row">
      <div class="col-md-6">
          <form id="course_form" data-toggle="validator" role="form" method="post">
              <div class="form-group">
                  <label for="course_name">เกณฑ์</label>
                  <?php if (isset($rules) && is_array($rules)): ?>
                  <?php foreach ($rules as $value): ?>
                  <div class="checkbox">
                    <label>
                      <input name="rules[]" type="checkbox" value="<?php echo $value->rule_id; ?>" <?php echo isset($ruleSelected[$value->rule_id]) && $ruleSelected[$value->rule_id] ? 'checked' : ''; ?>>
                      <?php echo $value->rule_name; ?>
                    </label>
                  </div>
                  <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <div class="pull-right">
                  <a class="btn btn-default" href="<?php echo base_url('admin/roles'); ?>">ยกเลิก</a>
                  <button type="submit" class="btn btn-primary">แก้ไขเกณฑ์</button>
              </div>
          </form>
      </div>
</div>
