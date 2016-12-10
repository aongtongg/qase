<div class="row">
    <div class="col-md-12">
        <h1>Quality Assurance</h1>
        <ul class="list-unstyled">
            <?php if (isset($_SESSION['members_class']) && $_SESSION['members_class'] == 2): ?>
            <li><hr></li>
            <li class=""><a href="<?php echo base_url('admin/courses'); ?>" title="หลักสูตรที่เปิดสอน">หลักสูตรที่เปิดสอน</a></li>
            <li class=""><a href="<?php echo base_url('admin/teacher_has_courses'); ?>" title="หลักสูตร และบทบาท">หลักสูตร และบทบาท</a></li>
            <?php endif; ?>
            <li><hr></li>
            <li class=""><a href="<?php echo base_url('admin/sars'); ?>" title="ผลการประเมินคุณภาพ">ผลการประเมินคุณภาพ</a></li>
            <?php if (isset($_SESSION['members_class']) && $_SESSION['members_class'] == 2): ?>
            <li class=""><a href="<?php echo base_url('admin/roles'); ?>" title="กำหนดเกณฑ์">กำหนดเกณฑ์</a></li>
            <li class=""><a href="<?php echo base_url('admin/schedules'); ?>" title="การตรวจสอบ">การตรวจสอบ</a></li>
            <?php endif; ?>
            <li><hr></li>
        </ul>
    </div>
</div>
