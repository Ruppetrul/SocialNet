try {
    $(document).ready(function() {

        var _token = $('input[name="_token"]').val();

        load_data('',0, _token);

        function load_data(id="", num, _token) {

            var data = {};
            data["num"] = num;
            data["test"] = "test";
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
                        //$('#load_more_button').remove();
                        $('#students_table tbody').append(data);
                    },
                    error: function (error) {
                        document.write(JSON.stringify(error))
                    }
                })
            } catch (exception) {
                alert(exception);
            }

        }

        $(document).on('click', '#load_more_button', function(){
            $('#load_more_button').html('<b>Loading...</b>');
            var d = document.getElementById("load_more_button");  //   Javascript
            load_data( user_id, Number(d.getAttribute('num')) , _token);
        });
        /*
        const btn = $(document).querySelector('load_more_button');

        btn.onclick = function () {
            alert('test');
        }

            .getElementById('load_more_button').onclick = function () {
            alert('click');
        }*/

    });
}
catch (exception) { // не стандартно
    alert('ошибка');
}
