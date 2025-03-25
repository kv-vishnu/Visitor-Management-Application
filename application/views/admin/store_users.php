<input type="hidden" id="base_url" value="<?php echo base_url();?>">
<link rel="shortcut icon" href="<?php echo base_url();?>assets/admin/images/favicon.ico">
<link href="<?php echo base_url();?>assets/admin/css/crm-responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/classic.min.css" rel="stylesheet" /> <!-- 'classic' theme -->
<link href="<?php echo base_url();?>assets/admin/fonts/css/all.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/icon.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

<div class="container-fluid">
    <div class="justify-content-end d-flex">
        <button class="btn6-small" data-bs-toggle="modal" data-bs-target="#adduser">Add User</button>
    </div>
    <table id="examplee" class="table table-striped mt-3" style="width:100%">
        <thead style="background: #e5e5e5;">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                       $count = 1;
                       foreach($listusers as $users){
                        ?>
            <tr>
                <td><?=$count;?></td>
                <td><?=$users['Name'];?></td>
                <td><?=$users['userName'];?></td>
                <td><?=$users['userEmail'];?></td>
                <td><?=$users['UserPhoneNumber'];?></td>
                <td> <?php
            if ($users['userroleid'] == 2) {
                echo "Shop Owner";
            } elseif ($users['userroleid'] == 3) {
                echo "Employee";
            }
            elseif ($users['userroleid'] == 4) {
                echo "Delivery Boy";
            }
            elseif ($users['userroleid'] == 5) {
                echo "Supplier";
            }
            elseif ($users['userroleid'] == 6) {
                echo "Kitchen";
            }
            else {
                echo "Unknown Role";
            }
            ?></td>
                <td>
                    <button class="btn tblEditBtn pl-0 pr-0 edit-user" type="submit" data-bs-toggle="tooltip"
                        data-id="<?=$users['userid'];?>" data-name="<?=$users['Name'];?>"
                        data-email="<?=$users['userEmail'];?>" data-phone="<?=$users['UserPhoneNumber'];?>"
                        data-role="<?=$users['userroleid'];?>" data-bs-original-title="Edit Country"><i
                            class="fa fa-edit" data-bs-target="#edituser" data-bs-toggle="modal"></i></button>
                    <button data-id="<?=$users['userid'];?>" class="btn tblDelBtn pl-0 pr-0 delete-user" type="submit"
                        data-bs-toggle="tooltip"><i class="fa fa-trash" data-bs-target="#deleteuser"
                            data-bs-toggle="modal"></i></button>
                    <button data-id="<?=$users['userid'];?>" class="btn tblLogBtn pl-0 pr-0 password-change"
                        type="submit" data-toggle="tooltip" data-placement="bottom" title="Password Change"><i
                            class="fa-solid fa-key" data-bs-target="#passwordchange"
                            data-bs-toggle="modal"></i></button>
                    <button data-id="<?=$users['userid'];?>" class="btn tblLogBtn pl-0 pr-0 assign-table" type="submit"
                        data-toggle="tooltip" data-placement="bottom" title="Assign Table"><i
                            class="fa-solid fa-table"></i></button>
                </td>
            </tr>
            <?php $count++; } ?>
        </tbody>
    </table>
</div>
<!-- add user -->
<div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span>Add User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="row bg-soft-light mb-3 border1 pt-2">
                    <form class="row mt-0 mb-0" id="addusers" method="post" enctype="multipart/form-data">


                        <div class="col-md-4">
                            <div class="mb-2 ">
                                <label class="form-label mx-2" for="default-input">Name</label>
                                <input type="text" class="form-control" required placeholder=" Name" name="user_name">
                                <span class="error errormsg mt-2 mx-2" id="user_name_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-2 focus">
                                <label class="form-label" for="default-input">Email</label>
                                <input class="form-control" value="" placeholder="Email" type="text" name="user_email">
                                <span class="error errormsg mt-2 mx-2" id="user_email_error"></span>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Address</label>
                                <input class="form-control" value="" placeholder="Address" type="text"
                                    name="user_address">
                                <span class="error errormsg mt-2 mx-2" id="user_address_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Phone No</label>
                                <input class="form-control" value="" placeholder="Phone No" type="text"
                                    name="user_phoneno">
                                <span class="error errormsg mt-2 mx-2" id="user_phoneno_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Username</label>
                                <input class="form-control" value="" placeholder="Username" type="text"
                                    name="user_username">
                                <span class="error errormsg mt-2 mx-2" id="user_username_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Password</label>
                                <input class="form-control" value="" placeholder="Password" type="text"
                                    name="user_password">
                                <span class="error errormsg mt-2 mx-2" id="user_password_error"></span>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Role</label>
                                <select class="form-control" name="role">
                                    <option value="">Select role</option>
                                    <option value="2" <?= set_select('role', '2') ?>>Shop Owner</option>
                                    <option value="3" <?= set_select('role', '3') ?>>Employee</option>
                                    <option value="4" <?= set_select('role', '4') ?>>Delivery Boy</option>
                                    <option value="5" <?= set_select('role', '5') ?>>Supplier</option>
                                    <option value="6" <?= set_select('role', '6') ?>>Kitchen</option>
                                </select>
                                <span class="error errormsg mt-2 mx-2" id="user_role_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mt-4">
                                <button class="btn btn-primary w-md" type="button" id="add_user">Save</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- edit user -->
<div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span>edit user</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="user_id" value="">
                <!-- if response within jquery -->
                <div class="message d-none" role="alert"></div>
                <!-- if response within jquery -->



                <div class="container">
                    <div class="row mb-5">
                        <div class="row bg-soft-light mb-3 border1 pt-2">
                            <form class="row mt-0 mb-0" id="editusers" method="post" enctype="multipart/form-data">


                                <div class="col-md-4">
                                    <div class="mb-2 ">
                                        <label class="form-label mx-2" for="default-input">Name</label>
                                        <input type="text" class="form-control" required placeholder=" Name"
                                            id="user_name" name="edit_user_name">
                                        <span class="error errormsg mt-2 mx-2" id="edit_user_name_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-2 focus">
                                        <label class="form-label" for="default-input">Email</label>
                                        <input class="form-control" value="" placeholder="Email" type="text"
                                            id="user_email" name="edit_user_email">
                                        <span class="error errormsg mt-2 mx-2" id="edit_user_email_error"></span>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="default-input">Phone No</label>
                                        <input class="form-control" value="" placeholder="Phone No" id="user_phoneno"
                                            type="text" name="edit_user_phoneno">
                                        <span class="error errormsg mt-2 mx-2" id="edit_user_phoneno_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="default-input">Role</label>
                                        <select class="form-control" name="edit_user_role" id="edit_user_role">
                                            <option value="">Select role</option>
                                            <option value="2" <?= set_select('role', '2') ?>>Shop Owner111</option>
                                            <option value="3" <?= set_select('role', '3') ?>>Employee</option>
                                            <option value="4" <?= set_select('role', '4') ?>>Delivery Boy</option>
                                            <option value="5" <?= set_select('role', '5') ?>>Supplier</option>
                                            <option value="6" <?= set_select('role', '6') ?>>Kitchen</option>
                                        </select>
                                        <span class="error errormsg mt-2 mx-2" id="user_role_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mt-4">
                                        <button class="btn btn-primary w-md" type="button"
                                            id="edit_user">UPDATE</button>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                </form>

            </div>

        </div>
    </div>
</div>


<div class="modal fade " id="Passwordchange" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> Change Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- if response within jquery -->
                <div class="message d-none" role="alert"></div>
                <form action="" method="post" id="passwordchange">
                    <input type="text" name="user_id_change" id="user_id_change" value="" />
                    <input type="text" class="form-control" id="password" name="password_changes"
                        placeholder="Change Password">
                    <span class="error errormsg mt-2 mx-2" id="password_change_error"></span>
                </form>

            </div>
            <div class=" text-center mb-3">
                <button class="btn btn-primary" type="button" id="change_password">UPDATE</button>
            </div>

            </form>
        </div>
    </div>
</div>
</div>


<!-- Assigning tables -->
<div class="modal fade " id="tableassign" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> Table Assigning</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form id="table_assign_form">
                    <input type="hidden" id="user_id_for_assigning" value="" />

                    <div class="container">
                        <div class="row g-2">

                            <?php foreach ($tables as $table): 
                            $table_name = $table['store_table_name'] ? $table['store_table_name'] : $table['table_name'];   
                            ?>
                            <div class="col-12 col-md-6">
                                <div class="p-3 border bg-light d-flex owner_model_heading justify-content-between">
                                    <p class="owner_model_heading_name mb-0"><i
                                            class="fa-solid fa-table mx-2"></i><?= $table_name; ?></p>
                                    <span class=""><input class="form-check-input table-checkbox" type="checkbox"
                                            name="options[]" value="<?= $table['table_id']; ?>"
                                            id="table<?= $table['table_id']; ?>"></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <div class="col-12 col-md-6">
                                <div class="p-3 border bg-light d-flex owner_model_heading justify-content-between">
                                    <p class="owner_model_heading_name mb-0">
                                        <i class="fa-solid fa-user mx-2"></i>Take away
                                    </p>
                                    <span class=""><input class="form-check-input" type="checkbox" name="options[]"
                                            value="PK" id="tableTakeaway"></span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-3 border bg-light d-flex owner_model_heading justify-content-between">
                                    <p class="owner_model_heading_name mb-0">
                                        <i class="fa-solid fa-truck mx-2"></i>Delivery
                                    </p>
                                    <span class=""><input class="form-check-input" type="checkbox" name="options[]"
                                            value="DL" id="tableDelivery"></span>
                                </div>
                            </div>
                        </div>
                        <div class=" text-center mb-3">
                            <button type="submit" id="assign_table_btn" class="btn btn-primary">Assign</button>
                        </div>
                    </div>
                </form>
            </div>


            </form>
        </div>
    </div>
</div>
</div>
<!-- Assigning table -->


<!-- delete user -->
<div class="modal fade " id="deleteuser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- if response within jquery -->
                <div class="message d-none" role="alert"></div>
                <input type="hidden" name="id" id="delete_id" value="" />
                <?php echo are_you_sure; ?>
            </div>
            <div class="modal-footer"><button class="btn btn-primary" type="button" data-bs-dismiss="modal">No</button>
                <button class="btn btn-secondary" id="yes_del_user" type="button" data-bs-dismiss="modal">Yes</button>
            </div>

            </form>
        </div>
    </div>
</div>
</div>
<script src="<?php echo base_url();?>assets/admin/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/ownerscripts.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/bootstrap.bundle.min.js"></script>