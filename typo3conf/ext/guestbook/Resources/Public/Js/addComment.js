$(document).ready(function () {

    var guestBook = $('#guestBookBlock');

    guestBook
        .find('#commentsubmit').click(function () {
            var form = guestBook.find('form');
            $.post(ajaxURL, form.serialize(), function (response) {
                console.log(response);
                var items = [];
                $.each(response, function (key, val) {
                    var res = '<li class="list-group-item">' + val.comment + ' <span class="text-muted">(' + val.commentdate.date + ')</span> ';
                    res += '<br /><img src="fileadmin/profile_photo/' + val.author.image + '" width="30" height="30" /><br /><small><i>'+ val.author.fullname + '(Email:' + val.author.email + ')</i></small>';
                    res += '</li>';
                    items.push(res);
                });
                guestBook.find('#comments').html(items.reverse().join(''));
                guestBook.find('#commentfield').val('');
            });
            return false;
        });

});