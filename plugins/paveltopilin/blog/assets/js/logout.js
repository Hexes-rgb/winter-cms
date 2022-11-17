$(document).on('click', '#logout', function(){
    $.request('onLogout', {
        data: {redirect: '/'},
        flash: 1,
        handleFlashMessage: function(message, type) {
            $.wn.flashMsg({ text: message, class: type })
        }
    })
});
