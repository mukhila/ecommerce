$(function () {
    var wishlistUrl   = '/wishlist/toggle';
    var loginUrl      = '/login';
    var isLoggedIn    = $('meta[name="user-logged-in"]').attr('content') === 'true';

    // Replace the dummy wishlist click handler from script.js
    $(document).off('click', '.basic-product .ri-heart-line');

    $(document).on('click', '.wishlist-icon', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (!isLoggedIn) {
            window.location.href = loginUrl;
            return;
        }

        var $icon      = $(this).find('i');
        var productId  = $(this).data('product-id');

        if (!productId) return;

        $.ajax({
            url: wishlistUrl,
            method: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (res) {
                var added = res.status === 'added';
                $icon.toggleClass('ri-heart-line', !added)
                     .toggleClass('ri-heart-fill',  added);

                $.notify(
                    { icon: 'ri-check-line', title: 'Wishlist', message: res.message },
                    {
                        type: added ? 'success' : 'info',
                        placement: { from: 'top', align: 'right' },
                        delay: 3000,
                        animate: { enter: 'animated fadeInDown', exit: 'animated fadeOutUp' },
                        icon_type: 'class',
                        template:
                            '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                            '<span class="alert-icon" data-notify="icon"></span> ' +
                            '<span data-notify="title">{1}</span> ' +
                            '<span data-notify="message">{2}</span>' +
                            '<button type="button" aria-hidden="true" class="btn-close" data-notify="dismiss"></button>' +
                            '</div>',
                    }
                );
            },
            error: function (xhr) {
                if (xhr.status === 401 || xhr.status === 419) {
                    window.location.href = loginUrl;
                }
            },
        });
    });
});
