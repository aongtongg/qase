<div class="row">
    <div class="col-xs-12">
        <h1>การตรวจสอบ
            <a class="btn btn-primary" href="<?php echo base_url('admin/schedule_add/'); ?>">เพิ่ม</a>
            <button id="execute_schedules" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Executing..">Execute</button>
        </h1>
    </div>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ปีการศึกษา</th>
                    <th>หลักสูตร</th>
                    <th>ตรวจวัน</th>
                    <th>ตรวจเดือน</th>
                    <th class="text-center">เวลา</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">อีเมล์</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($data):
                    $i = 0;
                    foreach ($data as $key => $value) :
                        $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value->course_year + 543; ?></td>
                    <td><?php echo $value->course_name; ?></td>
                    <td><?php echo $controller->_GenDays($value->execute_day); ?></td>
                    <td><?php echo $controller->_GenMonth($value->execute_month); ?></td>
                    <td class="text-center"><?php echo str_replace('00:00', '00', $value->execute_time); ?></td>
                    <td class="text-center"><?php echo $value->schedule_active ? 'ใช้งาน' : 'ไม่ใช้งาน'; ?></td>
                    <td class="text-center"><?php echo $value->email ? '/' : ''; ?></td>
                    <td>
                      <a class="btn btn-primary" href="<?php echo base_url('admin/schedule_edit/'.$value->schedule_id); ?>">แก้ไข</a>
                      <a class="btn btn-danger" href="<?php echo base_url('admin/schedule_delete/'.$value->schedule_id); ?>">ลบ</a>
                    </td>
                </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#execute_schedules").click(function(event) {
              $('#execute_schedules').button('loading');
              var param = {}
              var options = {
                  dataType: 'json',
                  contentType: 'application/x-www-form-urlencoded',
                  type: 'POST',
                  url: BASE_URL + 'api/execute',
                  data: param
              }
              $.ajax(options).done(function(res) {
                  //console.log('result submit', res);
                  setTimeout(function () {
                      $('#execute_schedules').button('reset');
                  }, 2000);
              }).error(function(err) {
                  //console.log('error ', err);
                  setTimeout(function () {
                      $('#execute_schedules').button('reset');
                  }, 2000);
              });
        });
        $('.btn-danger').on('click', function(e) {
            if(!confirm('คุณต้องการที่จะลบการตรวจสอบนี้?')){
                 event.preventDefault();
             }
        });
    });
</script>
