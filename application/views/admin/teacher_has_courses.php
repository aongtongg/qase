<?php if (isset($course_year)): ?>
  <div class="row">
      <div class="col-xs-12">
          <h1>หลักสูตร "<?php echo $course_year[0]->course_name; ?>" ปี <?php echo $course_year[0]->course_year + 543; ?>
              <a class="btn btn-primary" href="<?php echo base_url('admin/teacher_has_course_add/'.$course_year[0]->course_year.'/'.$course_year[0]->course_id); ?>">เพิ่ม</a>
          </h1>
      </div>
      <div class="col-md-12">
          <table id="routeTable" class="table">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>บทบาท</th>
                      <th>อาจารย์</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  if ($course_year):
                      $i = 0;
                      foreach ($course_year as $key => $value) :
                          $i++;
                  ?>
                  <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value->role_name; ?></td>
                      <td><?php echo $value->first_name.' '.$value->last_name; ?></td>
                      <td><a class="btn btn-primary" href="<?php echo base_url('admin/teacher_has_course_edit/'.$value->course_year.'/'.$value->course_id.'/'.$value->role_id.'/'.$value->teacher_id); ?>">แก้ไข</a></td>
                  </tr>
                  <?php
                      endforeach;
                  endif;
                  ?>
              </tbody>
          </table>
      </div>
  </div>
<?php else: ?>
<div class="row">
    <div class="col-xs-12">
        <h1>ภาพรวมหลักสูตร
            <a class="btn btn-primary" href="<?php echo base_url('admin/teacher_has_course_add/'); ?>">เพิ่ม</a>
        </h1>
    </div>
    <div class="col-md-12">
        <table id="routeTable" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ปีการศึกษา</th>
                    <th>หลักสูตร</th>
                    <th>จำนวนอาจารย์</th>
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
                    <td><?php echo $value->teacher_no; ?></td>
                    <td><a class="btn btn-primary" href="<?php echo base_url('admin/teacher_has_courses/'.$value->course_year.'/'.$value->course_id); ?>">แก้ไข</a></td>
                </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
