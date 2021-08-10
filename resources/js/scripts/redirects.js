$(function(){
  $('.active-switch').change(function(){
    const updateData = {
      active: $(this).prop('checked') ? 1 : 0,
      id: $(this).val(),
      _token: $('input[name="_token"]').val()
    }
    $.ajax({
      type: 'post',
      url: updateURL,
      data: updateData,
      success:function(res) {
        console.log(res);
      }
    })
  })
})
