$(function(){
  $('.active-switch').change(function(){
    const active = $(this).prop('checked');
    const updateData = {
      active: active ? 1 : 0,
      id: $(this).val(),
      _token: $('input[name="_token"]').val()
    }
    $.ajax({
      type: 'post',
      url: updateURL,
      data: updateData,
      success:function(res) {
        let link_num = Number($('.active-links').text());
        active ? link_num ++ : link_num --;
        $('.active-links').text(link_num);
      }
    })
  })
})
