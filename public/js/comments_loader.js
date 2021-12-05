try {
    $(document).ready(function() {

        var _token = $('input[name="_token"]').val();

        load_data(user_id, _token);

        function load_data(user_id = 0, _token) {

            var data = {};
            data["num"] = last_comment_num;
            data["token"] = _token;
            data["id_user"] = user_id;

            try {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    method: "POST",
                    data: data,
                        success: function (data) {
                            last_comment_num += 5;
                            var d = document.getElementById("load_more_button");  //   Javascript

                            if (data != null && typeof data !== "undefined" && data.trim() !== '') {

                                $('#students_table tbody').append(data);
                                d.textContent = "Show more";
                            } else {

                                $('#load_more_button').html('<b>Loading...</b>');
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

        $(document).on('click', '#load_more_button', function(){
            $('#load_more_button').html('<b>Loading...</b>');
            load_data(user_id, _token);
        });
    });
}
catch (exception) { // не стандартно
    alert('ошибка');
}
