<div class="row">
    <div class="col-xs-12">
        <h1>กำหนดเกณฑ์</h1>
    </div>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>บทบาท</th>
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
                    <td><?php echo $value->role_name; ?></td>
                    <td>
                      <a class="btn btn-primary" href="<?php echo base_url('admin/role_edit/'.$value->role_id); ?>">แก้ไขเกณฑ์</a>
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
