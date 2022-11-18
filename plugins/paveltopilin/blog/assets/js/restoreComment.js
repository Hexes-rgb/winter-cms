$(document).on('click', '.restoreComment', function(){
    let comment_id = $(this).attr('comment-id');
    $.request('onRestoreComment', {
        data: {comment_id: comment_id},
        update: {'postShow::alive-comment': `#comment-body${comment_id}`},
        loading: $.wn.stripeLoadIndicator,
        flash: 1,
        handleFlashMessage: function(message, type) {
            $.wn.flashMsg({ text: message, class: type })
        }
    });

});
