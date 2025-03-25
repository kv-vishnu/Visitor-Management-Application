$(document).ready(function () {


    //alert(base_url);
    //delete store
    $(".del_store").click(function () {
        $('#store_id').val($(this).data('id'));
    });

    $('#yes_del_store').click(function () {
        //alert('delete');
        $.ajax({
            method: "POST",
            url: base_url + 'admin/store/delete',
            data: { 'id': $('#store_id').val() },
            success: function (data) {
                window.location.href = base_url + 'admin/store';
            }
        });
    });
    //delete store


    //delete country
    $(".del_country").click(function () {
        //alert('click');
        $('#country_id1').val($(this).data('id'));
    });

    $('#yes_del_country').click(function () {
        //alert('delete');
        $.ajax({
            method: "POST",
            url: base_url + 'admin/country/delete',
            data: { 'id': $('#country_id1').val() },
            success: function (data) {
                window.location.href = base_url + 'admin/country';
            }
        });
    });
    //delete store

    //delete table
    $(".del_table").click(function () {
        //alert('click');
        $('#table_id').val($(this).data('id'));
        $('#store_id_hidden_popup').val($(this).data('storeid'));
    });

    $('#yes_del_table').click(function () {
        //alert('delete');
        $.ajax({
            method: "POST",
            url: base_url + 'admin/table/delete',
            data: { 'id': $('#table_id').val(), 'store_id': $('#store_id_hidden_popup').val() },
            success: function (data) {
                console.log(data);

                window.location.href = base_url + 'admin/table/load_store_tables_iframe/' + $('#store_id_hidden_popup').val();
            }
        });
    });
    //delete table



    //delete category

    //delete store


    //delete category
    $(".del_tax").click(function () {
        //alert('click');
        $('#tax_id').val($(this).data('id'));
    });

    $('#yes_del_tax').click(function () {
        $.ajax({
            method: "POST",
            url: base_url + 'admin/tax/delete',
            data: { 'id': $('#tax_id').val() },
            success: function (data) {
                window.location.href = base_url + 'admin/tax';
            }
        });
    });
    //delete store


    //delete cookings
    $(".del_cooking").click(function () {
        //alert('click');
        $('#cooking_id').val($(this).data('id'));
    });

    $('#yes_del_cooking').click(function () {
        $.ajax({
            method: "POST",
            url: base_url + 'admin/cooking/delete',
            data: { 'id': $('#cooking_id').val() },
            success: function (data) {
                window.location.href = base_url + 'admin/cooking';
            }
        });
    });
    //delete cookings


    //delete cookings




});