$(document).ready(function()
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Search Left Sidebar : Categories & Sub-categories */
    $('#subCatsList h5 a').click(function()
    {
        $('#subCatsList').hide();
        $('#catsList').show();
        return false;
    });

    /* Save the Post */
    $('.make-favorite, .save-job, a.saved-job').click(function(){
        savePost(this);
    });

    /* Save the Search */
    $('#saveSearch').click(function(){
        saveSearch(this);
    });

});

/**
 * Save Ad
 * @param elmt
 * @returns {boolean}
 */
function savePost(elmt)
{
    var postId = $(elmt).closest('li').attr('id');

    $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/save/post',
        data: {
            'postId': postId,
            '_token': $('input[name=_token]').val()
        }
    }).done(function(data) {
        if (typeof data.logged == "undefined") {
            return false;
        }
        if (data.logged == '0') {
            alert(lang.loginToSavePost);
            window.location.replace(data.loginUrl);
            window.location.href = data.loginUrl;
            return false;
        }
        /* Decoration */
        if (data.status == 1) {
            if ($(elmt).hasClass('btn')) {
                $('#' + data.postId).removeClass('saved-job').addClass('saved-job');
                $('#' + data.postId + ' a').removeClass('save-job').addClass('saved-job');
            } else {
                $(elmt).html('<span class="fa fa-heart"></span> ' + lang.labelSavePostRemove);
            }
            alert(lang.confirmationSavePost);
        } else {
            if ($(elmt).hasClass('btn')) {
                $('#' + data.postId).removeClass('save-job').addClass('save-job');
                $('#' + data.postId + ' a').removeClass('saved-job').addClass('save-job');
            } else {
                $(elmt).html('<span class="fa fa-heart-o"></span> ' + lang.labelSavePostSave);
            }
            alert(lang.confirmationRemoveSavePost);
        }
        return false;
    });

    return false;
}

/**
 * Save Search
 * @param elmt
 * @returns {boolean}
 */
function saveSearch(elmt)
{
    var url      = $(elmt).attr('name');
    var countPosts = $(elmt).attr('count');

    $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/save/search',
        data: {
            'url': url,
            'countPosts': countPosts,
            '_token': $('input[name=_token]').val()
        }
    }).done(function(data) {
        if (typeof data.logged == "undefined") {
            return false;
        }
        if (data.logged == '0') {
            alert(lang.loginToSaveSearch);
            window.location.replace(data.loginUrl);
            window.location.href = data.loginUrl;
            return false;
        }
        if (data.status == 1) {
            alert(lang.confirmationSaveSearch);
        } else {
            alert(lang.confirmationRemoveSaveSearch);
        }
        return false;
    });

    return false;
}
