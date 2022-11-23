$(document).on('submit', '#createPost', function(event){
    event.preventDefault();
    $photos = $('#photos').prop('files');
    let form = new FormData();

    Array.from(photos).forEach(photo => {
        form.append('photos', photo);
    });
    form.append('title', $('#title').val())
    form.append('text', $('#text').val())
    console.log(form.entries());
    $.request('onCreatePost', {
        data: form,
        files: true
    });
});
