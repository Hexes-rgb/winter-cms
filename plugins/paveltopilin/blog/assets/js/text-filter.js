let timeout;
query = "";
if(localStorage.getItem('query') !== 'undefined'){
    query = localStorage.getItem('query');
};
$('#searchInput').val(query);
$(document).on('keyup', '#searchInput', function(){
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        // let query = $('#searchInput').val();
        // sendRequest(authors, tags, query, afterCreated, beforeCreated);
        prepareData();
        // $(this).val(query);
    }, 400);
});
