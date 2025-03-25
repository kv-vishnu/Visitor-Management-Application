$(document).ready(function(){
    
    base_url = $('#base_url').val(); 
    //alert(base_url);
    //delete user
    $(".del_user").click(function () {
            $('#user_id').val($(this).data('id'));
    });
    
    $('#yes_del_user').click(function(){
        //alert('delete');
        $.ajax({
        method: "POST",
        url: base_url + 'admin/user/delete',
            data: { 'id' : $('#user_id').val() },
            success: function(data){
                window.location.href = base_url + 'admin/user';  
            }
        });
    });
    //delete user

});