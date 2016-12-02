<div class="row">
    <?php if (validation_errors()): ?>
    <div id="message" class="alert alert-danger" onclick="HideMessage();">
        <?php echo validation_errors(); ?>
    </div>
    <?php endif; ?>
    <div class="col-md-12">
        <h1>แก้ไขหลักกฎบทบาท "<?php echo $data->role_name; ?>"</h1>
    </div>
</div>
<div class="row">
      <div class="col-md-6">
          <form id="course_form" data-toggle="validator" role="form" method="post">
              <div class="form-group">
                  <label for="course_name">กฎ</label>
                  <?php foreach ($rules as $value): ?>
                  <div class="checkbox">
                    <label>
                      <input name="rules[]" type="checkbox" value="<?php echo $value->rule_id; ?>" <?php echo isset($ruleSelected[$value->rule_id]) && $ruleSelected[$value->rule_id] ? 'checked' : ''; ?>>
                      <?php echo $value->rule_name; ?>
                    </label>
                  </div>
                  <?php endforeach; ?>
              </div>
              <div class="pull-right">
                  <a class="btn btn-default" href="<?php echo base_url('admin/roles'); ?>">ยกเลิก</a>
                  <button type="submit" class="btn btn-primary">แก้ไขกฎ</button>
              </div>
          </form>
      </div>
</div>
