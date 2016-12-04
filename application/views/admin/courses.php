<div class="row">
    <div class="col-xs-12">
        <h1>หลักสูตร
            <a class="btn btn-primary" href="<?php echo base_url('admin/course_add/'); ?>">เพิ่ม</a>
        </h1>
    </div>
    <div class="col-md-12">
        <table id="routeTable" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อหลักสูตร</th>
                    <th>วันที่เริ่มหลักสูตร</th>
                    <th>วันกำหนดประเมินหลักสูตร</th>
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
                    <td><?php echo $value->course_name; ?></td>
                    <td><?php echo $controller->_DateThai($value->course_start_date); ?></td>
                    <td><?php echo $controller->_DateThai($value->course_estimate_date); ?></td>
                    <td>
                      <a class="btn btn-primary" href="<?php echo base_url('admin/course_edit/'.$value->course_id); ?>">แก้ไข</a>
                      <?php if (!$value->teacher_has_courses): ?>
                      <a class="btn btn-danger" href="<?php echo base_url('admin/course_delete/'.$value->course_id); ?>">ลบ</a>
                      <?php endif; ?>
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
    $('.btn-danger').on('click', function(e) {
        if(!confirm('คุณต้องการที่จะลบหลักสูตรนี้?')){
             event.preventDefault();
         }
    });
</script>
