afterCreated = "";
beforeCreated = "";

if(localStorage.getItem('afterCreated') !== ""){
    afterCreated = localStorage.getItem('afterCreated');
}
if(localStorage.getItem('beforeCreated') !== ""){
    afterCreated = localStorage.getItem('beforeCreated');
}

$('#afterCreatedAt').val(afterCreated);
$('#beforeCreatedAt').val(beforeCreated);
$(document).on('click', '#createdAtClear', function(){
    let afterCreated = "";
    let beforeCreated = "";
    $('#afterCreatedAt').val('');
    $('#beforeCreatedAt').val('');
    prepareData();
});
