//Add new order
//View pending table orders
//View completed table orders
//Table Is resereved functionality
//Pending Orders Pickup And Delivery
//Completed delivery and pickup orders
//Get pending order count on order dashboard

$(document).ready(function () {

    var base_url = 'http://localhost/emigo-restaurant-application/';
    //var base_url = 'https://qr-experts.com/emigo-restaurant-application/';

    //View pending table orders
    $('.tableOrderPending').click(function () {
        $('#table_name').html($(this).attr('data-name'));
        document.getElementById('table_iframe_recipe').src = base_url + 'owner/order/OrdersPendingPKDL/' + $(this).attr('data-id');
    });
    //end

    //view ready order details within popup
    $('.ready_order_details').click(function () {
        $('#table_name').html($(this).attr('data-name'));
        document.getElementById('table_iframe_recipe').src = base_url + 'owner/order_kitchen/ReadyOrderDetails/' + $(this).attr('data-id');
    });
    //end

    //View completed table orders
    $('.tableOrdercompleted').click(function () {
        $('#table_name').html('Order Details -' + $(this).attr('data-name'));
        document.getElementById('table_iframe_recipe').src = base_url + 'owner/order/tableOrders/' + $(this).attr('data-id');
    });
    //end

    //Table Is resereved functionality
    $('.cbIsReserved').on('click', function () {
        const isChecked = $(this).is(':checked') ? 1 : 0; // Get the checked state
        const tableId = $(this).attr('data-id'); // Get the data-id attribute value
        $.ajax({
            url: base_url + "owner/order/setTableReserved",
            type: 'POST',
            data: {
                isReserved: isChecked,
                tableId: tableId
            },
            dataType: 'json',
            success: function (response) {
                location.reload();
            }
        });

    });
    //end

    //Pending Orders Pickup And Delivery
    $('.orders').click(function () {
        $('#table_name').html($(this).attr('data-name'));
        document.getElementById('table_iframe_recipe').src = base_url + 'owner/order/OrdersPKDL/' + $(this).attr('data-id');

    });
    //end

    //Completed delivery and pickup orders
    $('.completedOrders').click(function () {
        $('#table_name').html($(this).attr('data-name'));
        document.getElementById('table_iframe_recipe').src = base_url + 'owner/order/completedOrdersPKDL/' + $(this).attr('data-id');
    });
    //end

    //Add new order
    $('.new_order').click(function () {
        $('#table_name').html('Order Details -' + $(this).attr('data-name'));
        document.getElementById('table_iframe_order').src = base_url + 'owner/order/newOrder/' + $(this).attr('data-id');
    });
    //end







    //Get pending order count on order dashboard
    // This function will be called every 5 seconds
    setInterval(function () {

        $.ajax({
            url: base_url + "owner/order/get_Pending_Orders_Count",
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                // Update buttons if counts are greater than 0
                if (response.dining > 0) {
                    $('#tabs__nav_pending_table_count').removeClass('d-none');
                    $('#tabs__nav_pending_table_count').text(response.dining);
                    pendingOrderAlert();
                    response.table_ids.forEach(function (table) {
                        $("#order-table-list__item-heading_" + table.table_id).addClass("order-table-list__item-heading_pending-order");
                    });
                } else {
                    $('#tabs__nav_pending_table_count').text();
                }

                if (response.pickup > 0) {
                    $('#tabs__nav_pending_pickup_count').removeClass('d-none');
                    $('#tabs__nav_pending_pickup_count').text(response.pickup);
                    pendingOrderAlert();
                } else {
                    $('#tabs__nav_pending_pickup_count').text();
                }

                if (response.delivery > 0) {
                    $('#tabs__nav_pending_delivery_count').removeClass('d-none');
                    $('#tabs__nav_pending_delivery_count').text(response.delivery);
                    pendingOrderAlert();
                } else {
                    $('#tabs__nav_pending_delivery_count').text();
                }
                if (response.ready_order > 0) {
                    $('#tabs__nav_approved_ready_count').removeClass('d-none');
                    $('#tabs__nav_approved_ready_count').text(response.ready_order);
                    pendingOrderAlert();
                } else {
                    $('#tabs__nav_approved_ready_count').text();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching order counts:', error);
            }
        });

    }, 5000); // Interval of 5000ms = 5 seconds


    //3.Order Alert using set interval
    function pendingOrderAlert() {
        const audio = document.getElementById('alert-audio');
        audio.play();
        //location.reload();
    }




});