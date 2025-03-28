//1. Scroll top jquery
//2.Add enquiry
//3.Edit Enquiry
//4.Update Enquiry
//5.Add user functionality
//6.Edit user window showing with details
//7.Update user
//8.Delete User
//9.Change password
$(document).ready(function () {

    var base_url = 'http://localhost/visitor-management-application/';
    //var base_url = 'https://qr-experts.com/visitor-management-application/';

    //new DataTable('#example');
    $(document).on('click', '.emigo-close-btn , .reload-close-btn', function () {
        location.reload();
    });

    //1. Scroll top jquery
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#goToTop').fadeIn();
        } else {
            $('#goToTop').fadeOut();
        }
    });

    $('#goToTop').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    //2.Add enquiry
    $('#addNewEnquiry').click(function () {
        let formData = new FormData($('#add-new-enquiry')[0]); // Capture form data

        $.ajax({
            url: base_url + "admin/Enquiry/save", // URL to the controller method
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                if (response.success) {
                    $('#successModal .modal-body').text('Enquiry saved successfully');
                    $('#successModal').modal('show');
                    $('#add-new-enquiry')[0].reset();
                } else {
                    if (response.errors.visitor_name) {
                        $('#visitor_name_error').html(response.errors.visitor_name);
                    } else {
                        $('#visitor_name_error').html('');
                    }

                    if (response.errors.phone_number) {
                        $('#phone_number_error').html(response.errors
                            .phone_number);
                    } else {
                        $('#phone_number_error').html('');
                    }

                    if (response.errors.email) {
                        $('#email_error').html(response.errors.email);
                    } else {
                        $('#email_error').html('');
                    }

                    if (response.errors.purpose_of_visit) {
                        $('#purpose_of_visit_error').html(response.errors
                            .purpose_of_visit);
                    } else {
                        $('#purpose_of_visit_error').html('');
                    }

                    if (response.errors.contact_person) {
                        $('#contact_person_error').html(response.errors.contact_person);
                    } else {
                        $('#contact_person_error').html('');
                    }

                    if (response.errors.remarks) {
                        $('#remarks_error').html(response.errors
                            .remarks);
                    } else {
                        $('#remarks_error').html('');
                    }

                    if (response.errors.visitor_message) {
                        $('#visitor_message_error').html(response.errors
                            .visitor_message);
                    } else {
                        $('#visitor_message_error').html('');
                    }
                }
            },
            error: function (xhr) {
                $('#response').html('<p>An error occurred: ' + xhr.responseText + '</p>');
            }
        });
    });




    //3.Edit Enquiry
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).attr('data-id');
        $('#hiddenField').val(id);
        $('#enquiry_id_new').val(id);
        $.ajax({
            url: base_url + "admin/Enquiry/getEnquiryDetails",
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {
                console.log(response.data);
                $('#visitor_name').val(response.data
                    .visitor_name);
                $('#phone_number').val(response.data
                    .phone_number);
                $('#email').val(response.data
                    .email);
                $('#company_id').val(response.data
                    .company_id);
                $('#purpose_of_visit').val(response.data.purpose_of_visit);
                $('#contact_person').val(response.data
                    .contact_person);
                $('#remarks').val(response.data
                    .remarks);
                $('#visitor_message').val(response.data.visitor_message);

            },
            error: function () {
                alert('An error occurred while fetching data.');
            }
        });
    });

    //4.Update Enquiry
    $(document).on('click', '#update-btn', function () {
        let enquiry_id = $('#enquiry_id_new').val(); // Get product_id value
        let formData = new FormData($('#productForm')[
            0]);
        formData.append('enquiry_id_new', enquiry_id);

        $.ajax({
            url: base_url + "admin/Enquiry/updateEnquirydetails",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting the Content-Type header
            success: function (response) {
                console.log(response.data);

                if (response.data.errors) {
                    if (response.errors.visitor_name) {
                        $('#visitor_name_error').html(response.errors
                            .visitor_name);
                    } else if (response.errors.phone_number) {
                        $('#phone_number_error').html(response.errors
                            .phone_number);
                    } else if (response.errors.email) {
                        $('#email_error').html(response.errors
                            .email_hindi);
                    } else if (response.errors.company_id) {
                        $('#company_id_error').html(response.errors
                            .company_id);
                    }
                    else if (response.errors.purpose_of_visit) {
                        $('#purpose_of_visit_error').html(response.errors
                            .purpose_of_visit);
                    }

                    else if (response.errors.contact_person) {
                        $('#contact_person_error').html(response.errors
                            .contact_person);
                    }

                    else if (response.errors.remarks) {
                        $('#remarks_error').html(response.errors
                            .remarks);
                    }

                    else if (response.errors.visitor_message) {
                        $('#visitor_message_error').html(response.errors
                            .visitor_message);
                    }

                }
                else {
                    $('#Edit-dish').modal('hide');
                    $('#successModal .modal-body').text('Enquiry updated successfully');
                    $('#successModal').modal('show');
                }
            },
            error: function (xhr) {
                $('#response').html('<p>An error occurred: ' + xhr
                    .responseText +
                    '</p>');
            }
        });

    });

    //5.Add user functionality
    $("#addusers").click(function () {
        // Get the value from the input field
        var productID = $("#hiddenField").val();
        $("#user_company_id").val(productID);
        //  alert(productID);
    })



    $("#add_user").click(function () {
        let user_id = $('#user_company_id').val();
        //  alert(user_id);
        let formData = new FormData($('#adduserr')[0]);
        // alert(formData);
        formData.append('user_company_id', user_id);

        $.ajax({
            url: base_url + "admin/Users/save", // URL to the controller method
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('#successModal .modal-body').text('User saved successfully');
                    $('#successModal').modal('show');
                    $("#adduser").modal("hide");
                } else {
                    if (response.errors.user_name) {
                        $('#user_name_error').html(response.errors.user_name);
                    } else {
                        $('#user_name_error').html('');
                    }

                    if (response.errors.user_email) {
                        $('#user_email_error').html(response.errors
                            .user_email);
                    } else {
                        $('#user_email_error').html('');
                    }

                    if (response.errors.user_address) {
                        $('#user_address_error').html(response.errors.user_address);
                    } else {
                        $('#user_address_error').html('');
                    }

                    if (response.errors.user_phoneno) {
                        $('#user_phoneno_error').html(response.errors
                            .user_phoneno);
                    } else {
                        $('#user_phoneno_error').html('');
                    }

                    if (response.errors.user_username) {
                        $('#user_username_error').html(response.errors.user_username);
                    } else {
                        $('#user_username_error').html('');
                    }

                    if (response.errors.user_password) {
                        $('#user_password_error').html(response.errors
                            .user_password);
                    } else {
                        $('#user_password_error').html('');
                    }

                    if (response.errors.role) {
                        $('#user_role_error').html(response.errors
                            .role);
                    } else {
                        $('#user_role_error').html('');
                    }
                    if (response.errors) {

                    }
                }

            },
            error: function (xhr) {
                $('#response').html('<p>An error occurred: ' + xhr.responseText + '</p>');
            }
        });
    });

    //6.Edit user
    $(".edit-user").click(function () {
        var id = $(this).attr('data-id');
        $('#edit_user_id').val(id);
        $.ajax({
            url: base_url + "admin/Users/getUserDetails", // URL to the controller method
            type: 'POST',
            data: {
                edit_user_id: id
            },
            dataType: 'json',

            success: function (response) {
                console.log("User Data:", response); // Debug the response
                if (response.data) {
                    $('#user_name').val(response.data
                        .Name);
                    $('#user_email').val(response.data
                        .userEmail);
                    $('#user_address').val(response.data
                        .userAddress);
                    $('#user_phoneno').val(response.data
                        .UserPhoneNumber);
                    $('#user_username').val(response.data
                        .userName);
                    $('#user_password').val(response.data
                        .userPassword);
                    $('#role').val(response.data
                        .userroleid);

                }
            },
        });
    });

    //.7Update user
    $("#update_user").click(function () {
        var user_id = $('#edit_user_id').val();
        let formData = new FormData($('#edituserr')[0]);
        formData.append('edit_user_id', user_id);
        $.ajax({
            url: base_url + "admin/Users/updateUserdetails",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting the Content-Type header
            success: function (response) {
                if (response.data.errors) {
                    if (response.errors.user_name) {
                        $('#user_name_error').html(response.errors.user_name);
                    } else {
                        $('#user_name_error').html('');
                    }

                    if (response.errors.user_email) {
                        $('#user_email_error').html(response.errors
                            .user_email);
                    } else {
                        $('#user_email_error').html('');
                    }

                    if (response.errors.user_address) {
                        $('#user_address_error').html(response.errors.user_address);
                    } else {
                        $('#user_address_error').html('');
                    }

                    if (response.errors.user_phoneno) {
                        $('#user_phoneno_error').html(response.errors
                            .user_phoneno);
                    } else {
                        $('#user_phoneno_error').html('');
                    }

                    if (response.errors.user_username) {
                        $('#user_username_error').html(response.errors.user_username);
                    } else {
                        $('#user_username_error').html('');
                    }

                    if (response.errors.user_password) {
                        $('#user_password_error').html(response.errors
                            .user_password);
                    } else {
                        $('#user_password_error').html('');
                    }

                    if (response.errors.role) {
                        $('#user_role_error').html(response.errors
                            .role);
                    } else {
                        $('#user_role_error').html('');
                    }
                    if (response.errors) {
                        alert(response.errors);
                    }
                }
                else {
                    $('#successModal .modal-body').text('User Updated successfully');
                    $('#successModal').modal('show');
                    $('#list-users').modal('hide');
                }
            },
            error: function (xhr) {
                $('#response').html('<p>An error occurred: ' + xhr
                    .responseText +
                    '</p>');
            }
        });
    });

    //8.Delete User
    $('.delete-user').click(function () {
        var id = $(this).attr('data-id');
        $('#delete_id').val(id);
    })

    $('#yes_del_user').click(function () {
        $.ajax({
            method: "POST",
            url: base_url + "admin/Users/DeleteUser",
            data: {
                'id': $('#delete_id').val()
            },
            success: function (data) {
                console.log(data);
                window.location.href = '';
            }
        });
    });

    //9.Change password
    $('.password-change').click(function () {
        // alert(1);
        var id = $(this).attr('data-id');
        // alert(id);
        $('#user_password_change').val(id);
    })

    $('#change_password').click(function () {
        let formData = new FormData($('#PasswordChange')[0]);
        $.ajax({
            url: base_url + "admin/Enquiry/ChangePassword",
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    console.log(response);
                    location.reload();
                } else {
                    if (response.errors && response.errors.password_changes) {
                        $('#password_change_error').html(response.errors.password_changes);
                    }
                }
            },
        })
    })

});