<?php
$year = $data->course_year + 543;
?>
<div id="kpi" class="row">
    <div class="col-xs-12 text-right">
        <p>
            <div class="kpi-number"><?php echo $data->course_code; ?>-<?php echo substr($year, -2); ?>-<?php echo $data->sar_id; ?></div>
        </p>
    </div>
    <div class="col-xs-12">
        <h3 class="text-center">
            <p>ผลการประเมินคุณภาพการศึกษาภายในตามตัวบ่งชี้ ระดับหลักสูตร</p>
            <p>หลักสูตร<?php echo $data->course_name; ?> ประจำปีการศึกษาที่ <?php echo $year; ?></p>
        </h3>
    </div>
    <div class="col-xs-12 text-right">
        <p>
            ข้อมูล ณ วันที่ <?php echo $controller->_DateThai($data->sar_date, true); ?>
        </p>
        <p>
            รายงาน ณ วันที่ <?php echo $controller->_DateThai(date('Y-m-d H:i:s'), true); ?>
        </p>
    </div>
    <div class="col-md-12">
        <table class="table table-kpi">
            <thead>
                <tr>
                    <th>องค์ประกอบ</th>
                    <th>ผลประเมิน</th>
                </tr>
            </thead>
            <tbody>
                <!-- Strat Level 1 -->
                <?php foreach ($data->kpi as $value_1): ?>
                <tr class="level-1">
                <?php if (isset($value_1['pass'])): ?>
                <td><?php echo $value_1['title']; ?></td>
                <td>/</td>
                <?php else: ?>
                <td colspan="2"><?php echo $value_1['title']; ?></td>
                <?php endif; ?>
                </tr>
                    <?php if (isset($value_1['child'])): ?>
                    <!-- Strat Level 2 -->
                    <?php foreach ($value_1['child'] as $value_2): ?>
                    <tr class="level-2">
                    <?php if (isset($value_2['pass'])): ?>
                    <td><?php echo $value_2['title']; ?></td>
                    <td>/</td>
                    <?php else: ?>
                    <td colspan="2"><?php echo $value_2['title']; ?></td>
                    <?php endif; ?>
                    </tr>
                        <?php if (isset($value_2['child'])): ?>
                        <!-- Strat Level 3 -->
                        <?php $i = 1; ?>
                        <?php foreach ($value_2['child'] as $value_3): ?>
                        <tr class="level-3">
                        <?php if (isset($value_3['pass'])): ?>
                        <td><?php echo $i++.'. '.$value_3['title']; ?></td>
                        <td class="text-center">/</td>
                        <?php else: ?>
                        <td colspan="2"><?php echo $value_3['title']; ?></td>
                        <?php endif; ?>
                        </tr>
                        <!-- End Level 3 -->
                        <?php endforeach; ?>
                        <?php endif; ?>
                    <!-- End Level 2 -->
                    <?php endforeach; ?>
                    <?php endif; ?>
                <!-- End Level 1 -->
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
