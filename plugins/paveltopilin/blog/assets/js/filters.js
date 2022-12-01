let timeout;
prepareFilterData();

$(document).on('click', '#authorFilter', function(){
    filtersMenu.authorMenu.visible = (filtersMenu.authorMenu.visible === false) ? true : false;
    changeFilterMenuVisibility();
});

$(document).on('click', '#tagFilter', function(){
    filtersMenu.tagMenu.visible = (filtersMenu.tagMenu.visible === false) ? true : false;
    changeFilterMenuVisibility();
});

$(document).on('click', '#createdAtFilter', function(){
    filtersMenu.createdAtMenu.visible = (filtersMenu.createdAtMenu.visible === false) ? true : false;
    changeFilterMenuVisibility();
});

$(document).on('click', '#authorsApply', function(){
    filters.authors = tempFilters.tempAuthors;
    prepareRequestData();

});

$(document).on('click', '#tagsApply', function(){
    filters.tags = tempFilters.tempTags;
    prepareRequestData();
});

$(document).on('click', '#createdAtApply', function(){
    filters.afterCreated = $('#afterCreatedAt').val();
    filters.beforeCreated = $('#beforeCreatedAt').val();
    prepareRequestData();
});

$(document).on('click', '.author', function(){
    let id = Number($(this).attr('author-id'));
    if(tempFilters.tempAuthors.includes(id, 0)){
        let index = tempFilters.tempAuthors.indexOf(id);
        tempFilters.tempAuthors.splice(index, 1);
    } else {
        tempFilters.tempAuthors.push(id);
    }
    changeFilterColor(tempFilters.tempAuthors, 'author');
});

$(document).on('click', '#selectAllAuthors', function(){
    $('.author').each(function(){
        tempFilters.tempAuthors.push(Number($(this).attr('author-id')));
    });
    changeFilterColor(tempFilters.tempAuthors, 'author');
});

$(document).on('click', '.tag', function(){
    let id = Number($(this).attr('tag-id'));
    if(tempFilters.tempTags.includes(id, 0)){
        let index = tempFilters.tempTags.indexOf(id);
        tempFilters.tempTags.splice(index, 1);
    } else {
        tempFilters.tempTags.push(id);
    }
    changeFilterColor(tempFilters.tempTags, 'tag');
});

$(document).on('click', '#selectAllTags', function(){
    $('.tag').each(function(){
        tempFilters.tempTags.push(Number($(this).attr('tag-id')));
    });
    changeFilterColor(tempFilters.tempTags, 'tag');
});

$(document).on('click', '#tagsClear', function(){
    tempFilters.tempTags.splice(0, tempFilters.tempTags.length);
    filters.tags = Array.from(tempFilters.tempTags);
    changeFilterColor(filters.tags, 'tag');
    prepareRequestData();
});

$(document).on('click', '#authorsClear', function(){
    tempFilters.tempAuthors.splice(0, tempFilters.tempAuthors.length);
    filters.authors = Array.from(tempFilters.tempAuthors);
    changeFilterColor(filters.authors, 'author');
    prepareRequestData();
});

$(document).on('click', '#createdAtClear', function(){
    $('#afterCreatedAt').val('');
    $('#beforeCreatedAt').val('');
    filters.afterCreated = $('#afterCreatedAt').val();
    filters.beforeCreated = $('#beforeCreatedAt').val();
    prepareRequestData();
});

$(document).on('keyup', '#searchInput', function(){
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        filters.text = $('#searchInput').val();
        prepareRequestData();
    }, 400);
});

$(document).on('keyup', '#searchAuthors', function(){
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        filtersMenu.authorMenu.text = $('#searchAuthors').val();
        sendAuthorMenuData();
    }, 400);
});

$(document).on('keyup', '#searchTags', function(){
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        filtersMenu.tagMenu.text = $('#searchTags').val();
        sendTagMenuData();
    }, 400);
});

$(document).on('click', '.sort', function(){
    let column = $(this).attr('column');
    newColumn = filters.sort[0]
    order = filters.sort[1];
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
    filters.sort = [column, order];
    prepareRequestData();
});

function prepareRequestData(){
    sendRequest(filters);
    changeFilterColor(filters.authors, 'author');
    changeFilterColor(filters.tags, 'tag');
}

function sendRequest(filters){
    localStorage.setItem('filters', JSON.stringify(filters));
    $.request('onFilter', {
        data: {filters: filters},
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            renderPostsTable(response.partial);
            tempFilters = {
                tempAuthors: Array.from(filters.authors),
                tempTags: Array.from(filters.tags)
            }
            setFiltersDesc()
        }
    })
}

function sendAuthorMenuData(){
    localStorage.setItem('filtersMenu', JSON.stringify(filtersMenu));
    $.request('onGetAuthors', {
        data: {authorSearch: filtersMenu.authorMenu.text},
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            renderFilterMenu(response.partial, 'authors');
        }
    })
}

function sendTagMenuData(){
    localStorage.setItem('filtersMenu', JSON.stringify(filtersMenu));
    $.request('onGetTags', {
        data: {tagSearch: filtersMenu.tagMenu.text},
        loading: $.wn.stripeLoadIndicator,
        success: function(response){
            renderFilterMenu(response.partial, 'tags')
        }
    })
}

function changeFilterColor(filter, selector){
    removeFilterColor(selector);
    filter.forEach(element => {
        $(`#${selector}${element}`).addClass('text-white');
        $(`#${selector}${element}`).addClass('bg-primary');
    });
}

function removeFilterColor(selector){
    $(`.${selector}`).removeClass('text-white');
    $(`.${selector}`).removeClass('bg-primary');
}

function setSortIcons(selector){
    deleteSortIcons();
    let img;
    if(filters.sort[1] === 'desc'){
        img = '<img src="/themes/blog/assets/images/sort-down.png" heigth="15px" width="15px" style="margin-left:5px">';
    } else if (filters.sort[1] === 'asc'){
        img = '<img src="/themes/blog/assets/images/sort-up.png" heigth="15px" width="15px" style="margin-left:5px">';
    } else {
        $(`#${selector}`).children(img).remove();
    }
    $(`#${selector}`).append(img);
}

function deleteSortIcons(){
    $('.sort').children('img').remove();
}

function renderPostsTable(partial){
    $('#table').replaceWith(partial);
    setSortIcons(filters.sort[0]);

    google.charts.load('current', {'packages':['corechart']});

    google.charts.setOnLoadCallback(drawCharts);

}

function renderFilterMenu(partial, selector){
    $(`#${selector}`).replaceWith(partial);
    changeFilterColor(tempFilters.tempAuthors, 'author');
    changeFilterColor(tempFilters.tempTags, 'tag');
}

function changeFilterMenuVisibility(){
    $.each(filtersMenu, function(key, menu){
        if(menu.visible === true){
            $(`#${key}`).show();
            menu.visible === true;
        } else {
            $(`#${key}`).hide();
            menu.visible === false;
        }
    });
    localStorage.setItem('filtersMenu', JSON.stringify(filtersMenu));
}

function setFiltersDesc(){
    authorsCount = (filters.authors.length === 0) ? 'all' : filters.authors.length;
    tagsCount = (filters.tags.length === 0) ? 'all' : filters.tags.length;
    createdAtDesc = ''.concat(
        ( filters.afterCreated === '' ) ? '∞' : filters.afterCreated, ' -> ',
        ( filters.beforeCreated === '' ) ? '∞' : filters.beforeCreated
    )
    $('#authorFilterDesc').empty();
    $('#authorFilterDesc').append(authorsCount);

    $('#tagFilterDesc').empty();
    $('#tagFilterDesc').append(tagsCount);

    $('#createdAtFilterDesc').empty();
    $('#createdAtFilterDesc').append(createdAtDesc);
}
function prepareFilterData(){
    if(localStorage.getItem('filters') !== null){
        filters = JSON.parse(localStorage.getItem('filters'));
    } else {
        filters = {
            authors: [],
            tags: [],
            afterCreated: "",
            beforeCreated: "",
            text : "",
            sort : ['id', '']
        }

    }
    tempFilters = {
        tempAuthors: Array.from(filters.authors),
        tempTags: Array.from(filters.tags)
    }
    if(localStorage.getItem('filtersMenu') !== null){
        filtersMenu = JSON.parse(localStorage.getItem('filtersMenu'));
    } else {
        filtersMenu = {
            authorMenu: {text: '', visible: false},
            tagMenu: {text: '', visible: false},
            createdAtMenu: {text: '', visible: false}
        }
    }

    setSortIcons(filters.sort[0]);
    changeFilterMenuVisibility();
    changeFilterColor(tempFilters.tempAuthors, 'author');
    changeFilterColor(tempFilters.tempTags, 'tag');
    setFiltersDesc();
    $('#afterCreatedAt').val(filters.afterCreated);
    $('#beforeCreatedAt').val(filters.beforeCreated);
    $('#searchInput').val(filters.text);
    $('#searchAuthors').val(filtersMenu.authorMenu.text);
    $('#searchTags').val(filtersMenu.tagMenu.text);
}

$(document).on('click', '#exportPosts', function(){
    $.request('onExportPosts', {
        loading: $.wn.stripeLoadIndicator,
        handleRedirectResponse: function(url){
            window.location.href = url;
        }
    })
});

function drawCharts(){
    $.request('onLoadChartsTables', {
        success: function(response){
            // var data = new google.visualization.DataTable();
            // console.log(response, data);
            var authorsChart = new google.visualization.PieChart(document.getElementById('authors_div'));
            authorsChart.draw(response.authorsChart);
        }
    });
}
