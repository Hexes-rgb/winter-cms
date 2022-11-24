let tags = []

changeTagsColor(tags);

$(document).on('click', '#tagFilter', function(){
    $('#tagMenu').show();
})

$(document).on('click', '.tag', function(){
    let id = Number($(this).attr('tag-id'));
    if(tags.includes(id, 0)){
        let index = tags.indexOf(id);
        tags.splice(index, 1);
    } else {
        tags.push(id);
    }
    console.log(tags);
    changeTagsColor(tags);
})

$(document).on('click', '#tagsApply', function(){
    sendRequest(authors, tags);
});

$(document).on('click', '#tagsClear', function(){
    tags.splice(0, tags.length);
    changeTagsColor(tags);
    sendRequest(authors, tags);
    console.log(tags);
});

async function changeTagsColor(tags){
    removeTagsColor();
    tags.forEach(element => {
        $(`#tag${element}`).addClass('text-white');
        $(`#tag${element}`).addClass('bg-primary');
    });
}

function removeTagsColor(){
    $('.tag').removeClass('text-white');
    $('.tag').removeClass('bg-primary');
 }

 function renderPostsTable(partial){
    $('#table').replaceWith(partial);
 }
