$('.list-group-item').click(function (event) {
    event.preventDefault();
    $('#users a.list-group-item.active').removeClass('active');
    $(this).addClass('active');
});