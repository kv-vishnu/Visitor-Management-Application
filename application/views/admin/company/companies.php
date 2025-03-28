<div class="application-content dashboard-content">
    <div class="application-content__container container">

        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($companies)) { 
                $count = 1;
                foreach ($companies as $val) { ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo htmlspecialchars($val['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($val['company_code']); ?></td>
                        <td>
                            <a data-bs-toggle="modal" data-bs-target="#list-users" data-id="<?php echo $val['n_id']; ?>"
                                href="" class="btn btn-success btn-sm edit-btn">Users</a>
                        </td>
                    </tr>
                    <?php $count++; } 
            } else { ?>
                    <tr>
                        <td colspan="7" class="text-center">No enquiries found.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            <?= $pagination; ?>
        </div>
    </div>



</div>







<div class="modal fade" id="list-users" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span>List users</h1>
                <button type="button" class="emigo-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="message d-none" role="alert"></div>
                <div class="container">
                    <div class="justify-content-end d-flex">
                        <button class="btn6-small" id="addusers" data-bs-toggle="modal" data-bs-target="#adduser">Add
                            User</button>
                    </div>
                    <div class="row">
                        <input type="text" id="hiddenField" name="product_id">
                        <div class="table-responsive-sm">
                            <table id="examplee" class="table table-striped mt-3" style="width:100%">
                                <thead style="background: #e5e5e5;">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                       $count = 1;
                       foreach($list_users as $users){
                        ?>
                                    <tr>
                                        <td><?=$count;?></td>
                                        <td><?=$users['Name'];?></td>
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
                                        <td><?=$users['userName'];?></td>
                                        <td><?=$users['userEmail'];?></td>
                                        <td><?=$users['UserPhoneNumber'];?></td>

                                        <td>
                                            <button class="btn tblEditBtn pl-0 pr-0 edit-user" id="edit-users"
                                                type="submit" data-bs-toggle="tooltip" data-id="<?=$users['userid'];?>"
                                                data-name="<?=$users['Name'];?>" data-email="<?=$users['userEmail'];?>"
                                                data-phone="<?=$users['UserPhoneNumber'];?>"
                                                data-role="<?=$users['userroleid'];?>"
                                                data-bs-original-title="Edit Country"><i class="fa fa-edit"
                                                    data-bs-target="#edituser" data-bs-toggle="modal"></i></button>
                                            <button data-id="<?=$users['userid'];?>"
                                                class="btn tblDelBtn pl-0 pr-0 delete-user" type="submit"
                                                data-bs-toggle="tooltip"><i class="fa fa-trash"
                                                    data-bs-target="#deleteuser" data-bs-toggle="modal"></i></button>
                                            <button data-id="<?=$users['userid'];?>"
                                                class="btn tblLogBtn pl-0 pr-0 password-change" type="submit"
                                                data-toggle="tooltip" data-placement="bottom" title="Password Change"><i
                                                    class="fa-solid fa-key" data-bs-target="#passwordchange"
                                                    data-bs-toggle="modal"></i></button>

                                        </td>
                                    </tr>
                                    <?php $count++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- <iframe id="iframe_body" height="700px" width="100%"></iframe> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add user -->
<div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span>Add User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">



                <div class="row bg-soft-light mb-3 border1 pt-2">
                    <form class="row mt-0 mb-0" id="adduserr" method="post" enctype="multipart/form-data">
                        <input type="text" id="user_company_id" name="user_company_id" readonly>

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
<!-- Add User -->

<!-- edit user -->
<div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h1 class="modal-title fs-5" id="exampleModalLabel"> <span id="table_name"></span>Add User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">



                <div class="row bg-soft-light mb-3 border1 pt-2">
                    <form class="row mt-0 mb-0" id="edituserr" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="edit_user_id" name="edit_user_id" readonly>

                        <div class="col-md-4">
                            <div class="mb-2 ">
                                <label class="form-label mx-2" for="default-input">Name</label>
                                <input type="text" class="form-control" required placeholder=" Name" id="user_name"
                                    name="user_name">
                                <span class="error errormsg mt-2 mx-2" id="user_name_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-2 focus">
                                <label class="form-label" for="default-input">Email</label>
                                <input class="form-control" value="" placeholder="Email" type="text" id="user_email"
                                    name="user_email">
                                <span class="error errormsg mt-2 mx-2" id="user_email_error"></span>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Address</label>
                                <input class="form-control" value="" placeholder="Address" type="text" id="user_address"
                                    name="user_address">
                                <span class="error errormsg mt-2 mx-2" id="user_address_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Phone No</label>
                                <input class="form-control" value="" placeholder="Phone No" type="text"
                                    id="user_phoneno" name="user_phoneno">
                                <span class="error errormsg mt-2 mx-2" id="user_phoneno_error"></span>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Username</label>
                                <input class="form-control" value="" placeholder="Username" type="text"
                                    name="user_username" id="user_username">
                                <span class="error errormsg mt-2 mx-2" id="user_username_error"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Password</label>
                                <input class="form-control" value="" placeholder="Password" type="text"
                                    name="user_password" id="user_password">
                                <span class="error errormsg mt-2 mx-2" id="user_password_error"></span>

                            </div>
                        </div> -->

                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label" for="default-input">Role</label>
                                <select class="form-control" name="role" id="role">
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
                                <button class="btn btn-primary w-md" type="button" id="update_user">Save</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- edit user -->

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
                <input type="text" name="id" id="delete_id" value="" />
                <?php echo are_you_sure; ?>
            </div>
            <div class="modal-footer"><button class="btn btn-primary" type="button" data-bs-dismiss="modal">No</button>
                <button class="btn btn-secondary" id="yes_del_user" type="button" data-bs-dismiss="modal">Yes</button>
            </div>

            </form>
        </div>
    </div>
</div>
<!-- delete user -->

<!-- Change password -->
<div class="modal fade " id="passwordchange" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"> Change Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- if response within jquery -->
                <div class="message d-none" role="alert"></div>
                <form action="" method="post" id="PasswordChange">
                    <input type="text" name="user_password_change" id="user_password_change" value="" />
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
<!-- Change password -->