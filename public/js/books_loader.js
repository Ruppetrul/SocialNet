try {
    $(document).ready(function() {

        var _token = $('input[name="_token"]').val();

        load_data(id_user, _token);

        function load_data(id_user = 0, _token) {

            var data = {};
            data["num"] = last_book_num;
            data["id_user"] = id_user;

            try {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    method: "POST",
                    data: data,
                        success: function (data) {
                            last_book_num += 5;
                            var d = document.getElementById("load_more_books_button");  //   Javascript

                            if (data != null && typeof data !== "undefined" && data.trim() !== '') {
                                $('#books_ul').append(data);
                                d.textContent = "Show more";
                            } else {
                                d.textContent = "No new comments found";
                            }
                        }
                    }).fail(function (xhr, textStatus, errorThrown) {
                    alert(errorThrown);
                    alert(textStatus);
                    alert(JSON.stringify(xhr));
                })

            } catch (exception) {
                alert(exception);
            }
        }

        $(document).on('click', '#load_more_books_button', function(){
            $('#load_more_books_button').html('<b>Loading...</b>');
            load_data(id_user, _token);
        });
    });
}
catch (exception) { // не стандартно
    alert('ошибка');
}
