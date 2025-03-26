//1. Scroll top jquery
//2.Add enquiry

$(document).ready(function () {

    var base_url = 'http://localhost/visitor-management-application/';
    //var base_url = 'https://qr-experts.com/visitor-management-application/';

    //new DataTable('#example');
    $(document).on('click', '.emigo-close-btn', function () {
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
        let formData = new FormData($('#add-new-enquiry')[0]); // Capture the form data, including files

        $.ajax({
            url: base_url + "admin/Enquiry/save", // URL to the controller method
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting the Content-Type header
            success: function (response) {

                if (response.errors) {
                    // Define a mapping between error keys and their corresponding HTML elements
                    const errorMapping = {
                        company_id: '#company_id_error',
                        purpose_of_visit: '#purpose_of_visit_error',
                        contact_person: '#contact_person_error',
                        visitor_name: '#visitor_name_error',
                        phone_number: '#phone_number_error',
                        email: '#email_error',
                        remarks: '#remarks_error',
                        visitor_message: '#visitor_message_error',
                    };

                    // Iterate through the errorMapping and set the corresponding error messages
                    Object.keys(errorMapping).forEach(key => {
                        if (response.errors[key]) {
                            $(errorMapping[key]).html(response.errors[key]);
                        } else {
                            $(errorMapping[key]).html(
                                ''); // Clear the error message if not present
                        }
                    });
                } else {
                    alert(2);
                    // $(this).prop('disabled', true).text('Processing...');
                    // window.location.href = base_url + "admin/Enquiry/";
                }

            },
            error: function (xhr) {
                $('#response').html('<p>An error occurred: ' + xhr
                    .responseText +
                    '</p>');
            }
        });
    });


    //Edit enquiry details
    //14.Edit dish popup tab.Get default product tab details 
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).attr('data-id');
        $('#hiddenField').val(id);
        $('#product_id_new').val(id);
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
                $('#purpose_of_visit').val(response.data
                    .purpose_of_visit);
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


});