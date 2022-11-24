let authors = []
if(localStorage.getItem('authors') !== null){
    authors = JSON.parse(localStorage.getItem('authors'));
};
changeAuthorsColor(authors);

$(document).on('click', '#authorFilter', function(){
    $('#authorMenu').show();
})

$(document).on('click', '.author', function(){
    let id = Number($(this).attr('author-id'));
    if(authors.includes(id, 0)){
        let index = authors.indexOf(id);
        authors.splice(index, 1);
    } else {
        authors.push(id);
    }
    console.log(authors);
    changeAuthorsColor(authors);
})

$(document).on('click', '#authorsApply', function(){
    sendRequest(authors, tags);
});

$(document).on('click', '#authorsClear', function(){
    authors.splice(0, authors.length);
    changeAuthorsColor(authors);
    sendRequest(authors, tags);
});

async function changeAuthorsColor(author){
    removeAuthorsColor();
    author.forEach(element => {
        $(`#author${element}`).addClass('text-white');
        $(`#author${element}`).addClass('bg-primary');
    });
}

function removeAuthorsColor(){
    $('.author').removeClass('text-white');
    $('.author').removeClass('bg-primary');
 }

 function renderPostsTable(partial){
    $('#table').replaceWith(partial);
 }
