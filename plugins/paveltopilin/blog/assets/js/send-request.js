// $(document).on('click', '#authorsApply', function(){
//     sendRequest(authors, tags, query, afterCreated, beforeCreated);
// });

// $(document).on('click', '#tagsApply', function(){
//     sendRequest(authors, tags, query, afterCreated, beforeCreated);
// });
// $(document).on('click', '#createdAtApply', function(){
//     let afterCreated = $('#afterCreatedAt').val();
//     let beforeCreated = $('#beforeCreatedAt').val();
//     sendRequest(authors, tags, query, afterCreated, beforeCreated);
// });

$(document).on('click', '.apply', function(){
    prepareData();
});
sort = ['', ''];
if(localStorage.getItem('sort') !== null ){
    sort = JSON.parse(localStorage.getItem('sort'));
    // column = sort[0]
    // order = sort[1]
}

$(document).on('click', '.sort', function(){
    let column = $(this).attr('column');
    if(localStorage.getItem('sort') !== null ){
        sort = JSON.parse(localStorage.getItem('sort'));
        newColumn = sort[0]
        order = sort[1];
        if(newColumn === column){
            if(order === ''){
                order = 'desc';
            } else if ( order === 'desc') {
                order = 'asc';
            } else {
                order = '';
            }
        } else {
            order = 'desc';
        }
    } else {
        order = 'desc';
    }
    sort = [column, order];
    prepareData();
});

function prepareData(){
    let afterCreated = $('#afterCreatedAt').val();
    let beforeCreated = $('#beforeCreatedAt').val();
    let query = $('#searchInput').val();
    sendRequest(authors, tags, query, afterCreated, beforeCreated, sort);
}

function sendRequest(authors, tags, query, afterCreated, beforeCreated, sort){
    localStorage.setItem('authors', JSON.stringify(authors));
    localStorage.setItem('tags', JSON.stringify(tags));
    localStorage.setItem('query', query);
    localStorage.setItem('afterCreated', afterCreated);
    localStorage.setItem('beforeCreated', beforeCreated);
    localStorage.setItem('sort', JSON.stringify(sort));
    console.log(authors, tags, query, afterCreated, beforeCreated, sort);
    $.request('onFilter', {
        data: {authors: authors, tags: tags, query: query, afterCreated: afterCreated, beforeCreated: beforeCreated, sort: sort},
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            renderPostsTable(response.partial);
        }
    })
}
