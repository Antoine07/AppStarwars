(function () {

    $(window).scroll(function () {
        var wh = $(window).height(); // h resolution screen
        var dh = $(document).height();
        var $scrollTop = Math.ceil($(window).scrollTop());
        var lastPage = false;
        var $load = $("#loading");

        if (lastPage) {
            $(this).stop(true, false);
        }

        if (($scrollTop + wh) == dh) {

            var offset = $('.offset:last').data('offset');
            loadProducts(offset);

        }

        function loadProducts(offset) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: 'prod/ajax',
                type: 'POST',
                data: {offset: offset},
                dataTpe: 'JSON',
                beforeSend: function () {
                    $load.show();
                },
                complete: function (data) {
                    if (data.responseText == 'last') {
                        lastPage = true;
                        $load.hide();
                    }
                    else {
                        $load.hide();
                        $('#main-content').append(data.responseText);
                        $("#loading").hide();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });
})();
