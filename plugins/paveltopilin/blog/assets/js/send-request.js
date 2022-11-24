function sendRequest(authors, tags){
    $.request('onFilter', {
        data: {authors: authors, tags: tags},
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            renderPostsTable(response.partial);
        }
    })
}
