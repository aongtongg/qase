<?php if (isset($course_id) && $course_id): ?>
<div class="row">
    <div class="col-xs-12 text-center">
        <h3>
            <p>ประวัติการประเมินคุณภาพการศึกษาภายในตามตัวบ่งชี้ ระดับหลักสูตร</p>
            <p><?php echo $title; ?></p>
        </h3>
    </div>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ประวัติการประมวลผล</th>
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
                    <td><?php echo $value->sar_date; ?></td>
                    <td>
                      <a class="btn btn-primary" href="<?php echo base_url('admin/sar/'.$value->course_id.'/'.$value->sar_id); ?>">ผลการประเมินคุณภาพ</a>
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
<?php else: ?>
<div class="row">
    <div class="col-xs-12">
        <h1>ผลการประเมินคุณภาพ</h1>
    </div>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ปีการศึกษา</th>
                    <th>หลักสูตร</th>
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
                    <td>
                      <a class="btn btn-primary" href="<?php echo base_url('admin/sar/'.$value->course_id); ?>">ผลการประเมินล่าสุด</a>
                      <a class="btn btn-primary" href="<?php echo base_url('admin/sars/'.$value->course_id); ?>">ประวัติการประเมิน</a>
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
<?php endif; ?>
