<div class="row">
    <div class="col-sm-12">
        <?php if ($teachers): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                <?php foreach ($teachers as $value): ?>
                <?php ++$i; ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value->first_name; ?></td>
                    <td><?php echo $value->last_name; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
