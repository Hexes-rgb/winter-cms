function sendRequest(authors, tags){
    localStorage.setItem('authors', JSON.stringify(authors));
    localStorage.setItem('tags', JSON.stringify(tags));
    $.request('onFilter', {
        data: {authors: authors, tags: tags, request: true},
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            renderPostsTable(response.partial);
        }
    })
}
