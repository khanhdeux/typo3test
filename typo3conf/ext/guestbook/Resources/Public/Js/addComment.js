$(document).ready(function () {
    $('#commentsubmit').click(function () {
        var form = $('form');
        $.post(ajaxURL, form.serialize(), function (response) {
            console.log(response);
            var items = [];
            $.each(response, function (key, val) {
                var res = '<li class="list-group-item">' + val.comment + ' <span class="text-muted">(' + val.commentdate.date + ')</span> ';
                res += '<br /><img src="fileadmin/profile_photo/' + val.author.image + '" width="30" height="30" /><br /><small><i>'+ val.author.fullname + '(Email:' + val.author.email + ')</i></small>';
                res += '</li>';
                items.push(res);
            });
            $('#comments').html(items.reverse().join(''));
            $('#commentfield').val('');
        });
        return false;
    });
});