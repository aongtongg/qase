<?php
$allPass = true;
?>
<div id="kpi" class="row">
    <div class="col-xs-12 text-right">
        <p>
            <div class="kpi-number"><?php echo $code; ?></div>
        </p>
    </div>
    <div class="col-xs-12">
        <h3 class="text-center">
            <p>ผลการประเมินคุณภาพการศึกษาภายในตามตัวบ่งชี้ ระดับหลักสูตร</p>
            <p>หลักสูตร<?php echo $data->course_name; ?> ประจำปีการศึกษาที่ <?php echo $data->course_year + 543; ?></p>
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
                <td class="text-center"><?php echo $value_1['pass'] && !$value_1['fail'] ? 'ผ่าน' : !$value_1['pass'] && !$value_1['fail'] ? 'ยังไม่ได้ประเมิน' : 'ไม่ผ่าน'; ?></td>
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
                    <td class="text-center"><?php echo $value_2['pass'] && !$value_2['fail'] ? 'ผ่าน' : !$value_2['pass'] && !$value_2['fail'] ? 'ยังไม่ได้ประเมิน' : 'ไม่ผ่าน'; ?></td>
                    <?php else: ?>
                    <td colspan="2"><?php echo $value_2['title']; ?></td>
                    <?php endif; ?>
                    </tr>
                        <?php if (isset($value_2['child'])): ?>
                        <!-- Strat Level 3 -->
                        <?php $i = 1; ?>
                        <?php foreach ($value_2['child'] as $key => $value_3): ?>
                        <tr class="level-3">
                        <?php if (isset($value_3['pass'])): ?>
                        <td><?php echo $i++.'. '.$value_3['title']; ?></td>
                        <?php
                        if ($value_3['pass'] && !$value_3['fail']) {
                            $status = 'ผ่าน';
                            $pass = true;
                        } elseif (!$value_3['pass'] && !$value_3['fail']) {
                            $status = 'ยังไม่ได้ประเมิน';
                            $pass = false;
                            $allPass = false;
                        } else {
                            $status = 'ไม่ผ่าน';
                            $pass = false;
                            $allPass = false;
                        }
                        ?>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" data-target="#kpi<?php echo $key; ?>"><?php echo $status; ?></a>
                            <!-- Modal -->
                            <div id="kpi<?php echo $key; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><?php echo $value_3['title']; ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php $details = $controller->_SarDetails($value_3['pass'], $value_3['fail']); ?>
                                            <?php if ($details): ?>
                                            <table class="table table-kpi">
                                                <thead>
                                                  <tr>
                                                    <th>เกณฑ์การวัดผลคุณสมบัติ</th>
                                                    <th>ผลประเมิน</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($details as $teacher): ?>
                                                <tr class="level-3">
                                                    <td colspan="2"><h4><?php echo $teacher['name']; ?></h4></td>
                                                </tr>
                                                <?php if (isset($teacher['pass']['rule_id'])): ?>
                                                <?php foreach ($teacher['pass']['rule_id'] as $pass): ?>
                                                <tr class="kpi-pass">
                                                    <td class="text-left"><?php echo $pass['rule_name']; ?></td>
                                                    <td>ผ่าน</td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php if (isset($teacher['fail']['rule_id'])): ?>
                                                <?php foreach ($teacher['fail']['rule_id'] as $fail): ?>
                                                <tr class="kpi-fail">
                                                    <td class="text-left"><?php echo $fail['rule_name']; ?></td>
                                                    <td>ไม่ผ่าน</td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php else: ?>
                                            <p>ไม่ได้ประเมิน</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
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
    <div class="col-xs-12">
        <h4>สรุปผลการประเมิน</h4>
        <table class="table table-kpi-sum">
            <thead>
                <tr>
                    <th>องค์ประกอบ</th>
                    <th colspan="2">ผลการประเมิน</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="2">องค์ประกอบที่ 1 การกำกับมาตรฐาน</td>
                    <td class="text-center col-25">ผ่าน</td>
                    <td class="text-center col-25">ไม่ผ่าน</td>
                </tr>
                <tr>
                    <td class="text-center"><?php echo $allPass?'/':''; ?></td>
                    <td class="text-center"><?php echo $allPass?'':'/'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
