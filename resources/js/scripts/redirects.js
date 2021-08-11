$(function(){
  let table = $('#tblRedirects').DataTable();
  $('body').on('change','.active-switch', function(){
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

  $('body').on('click', '.copy-btn', function() {
    const index = $(this).attr('data-index');
    const text = $('.redirect-url').eq(index).text();
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
    toastr.success('Copied!');
  })

  $('body').on('click','.clone-btn', function() {
    const id = $(this).attr('data-id');
    const updateData = {
      id: id,
      _token: $('input[name="_token"]').val()
    };
    $.ajax({
      type: 'post',
      url: cloneURL,
      data: updateData,
      success:function(res) {
        const key = $('#tblRedirects tbody tr').length;
        const active_switch = '<div class="custom-control custom-switch custom-switch-success switch-lg mr-2">'+
        '<input id="locked_'+res.id+'" class="custom-control-input active-switch" type="checkbox" '+(res.id == 1 ? 'checked': '')+' value="'+res.id+'">'+
        '<label class="custom-control-label" for="locked_'+res.id+'">'+
        '<span class="switch-text-left">Active</span>'+
        '<span class="switch-text-right white">Inactive</span></label></div>';
        const action_btn = '<a class="copy-btn" data-index="'+key+'"><i class="fa fa-copy fa-2x"></i></a>'+
        '<a class="clone-btn" data-id="'+res.id+'"><i class="fa fa-clone fa-2x"></i></a>'+
        '<a class="remove-btn" data-id="'+res.id+'" data-index="'+key+'"><i class="feather icon-trash-2 fa-2x"></i></a>';
        let row = table.row.add([
          res.id,
          APP_URL + '/r/' + res.uuid,
          active_switch,
          '',
          res.max_hit_day,
          action_btn
        ]);
        table.row(row).column(1).nodes().to$().addClass('redirect-url');
        table.row(row).column(5).nodes().to$().addClass('action-group');
        table.row(row).draw(true);
        let total_redirect = Number($('.total-redirect').text());
        total_redirect ++;
        $('.total-redirect').text(total_redirect);
        toastr.success('Cloned');
      },
      catch:function() {
        toastr.error('Failed');
      }
    })
  })

  $('body').on('click', '.remove-btn', function() {
    const index = $('.remove-btn').index($(this));
    const tr = $('#tblRedirects tbody tr');
    const id = $(this).attr('data-id');
    const updateData = {
      id: id,
      _token: $('input[name="_token"]').val()
    };
    $.ajax({
      type: 'post',
      url: deleteURL,
      data: updateData,
      success:function(res) {
        toastr.success('Removed');
        const checked = $('.active-switch').eq(index).prop('checked');
        let link_num = Number($('.active-links').text());
        table.row(tr.eq(index)).remove().draw();
        if (checked) {
          link_num --;
          $('.active-links').text(link_num);
        }
        $('.total-redirect').text(tr.length - 1);
      }
    })
  })
})
