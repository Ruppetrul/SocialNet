$(document).ready(function(){
    var _token = $('input[name="_token"]').val();
    alert("Hello");
    load_data('', _token);

    function load_data(id="", _token){

    $.ajax({
    url:"{{ route('load_data1') }}",
    method:"POST",
    data:{id:id, _token:_token},
    success:function (data) {
    $('#load_more_button').remove();
    $('#post_data').append(data);
}

})
}
});
