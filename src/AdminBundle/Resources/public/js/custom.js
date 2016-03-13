$(function(){
    $('.delete-link').bind('click', function(e){
        e.preventDefault();
        if (confirm("Are you sure you want to delete ?")) {
            window.location.href = $(this).attr('href');
        }
    });
});