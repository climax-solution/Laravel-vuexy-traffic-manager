$(function(){
  const ruleList = ['geo-ip-group','proxy-group','referrer-group','empty-referrer-group','device-type-group'];
  let addFile = {};
  const validate_list = ['link_name','tracking_url','pixel','max_hit_day','fallback_url'];
  let saveData = {
    '_token': $('meta[name="csrf-token"]').attr('content')
  };
  let total_weight = total_hits = 0;

  $(".number-tab-steps").steps({
      headerTag: "h6",
      bodyTag: "fieldset",
      transitionEffect: "fade",
      titleTemplate: '<span class="step">#index#</span> #title#',
      labels: {
          finish: 'FINISH'
      },
      onStepChanging: () => {

        let flag = 0;
        jQuery.validator.setDefaults({
          debug: true,
          success: "valid"
        });
        let rule_option = {
          rules:{
            link_name: {
              required: true,
            },
            max_hit_day: {
              required: true
            },
            fallback_url:{
              required: true,
              url: true
            }
          }
        };

        const res = $('#step-wizard-1').validate(rule_option);
        $('#step-wizard-1').valid();
        if (res.errorList.length) return;
        // if (!active_rule.length) {
        //   toastr.warning('No selected rule.', 'Warning');
        //   return;
        // }
        active_rule.map((item) => {
          let row = {};
          switch(item) {
            case 0:
              let country_list = $('#country-list').val();
              if (!country_list.length) {
                toastr.warning('No selected country.', 'Warning');
                flag = 1;
                break;
              }
              let list = country_list[0];
              for (let i = 1; i < country_list.length; i ++) list += ',' + country_list[i];
              row = {
                country_list: list,
                action: $('#geo-ip').val()
              };
              break;
            case 1:
              row = {
                action: $('#proxy-action').val()
              };
              break;
            case 2:
              if (!$('#domain-name').val()) {
                toastr.warning('No inputed referrer url.', 'Warning');
                flag = 1;
                break;
              }
              row = {
                action: $('#referrer-action').val(),
                domain_type: $('#domain-type').val(),
                domain_reg: $('#domain-reg').val(),
                domain_name: $('#domain-name').val()
              };
              break;
            case 3:
              row = {
                action: $('#empty-referrer-action').val(),
              };
              break;
            case 4:
              row = {
                action: $('#device-action-list').val(),
                device: $('#device-type-list').val(),
              };
              break;
          };

          addFile[item] = row;
        })


        let advance_options = {
          blank: $('#blank-refer-switch')[0].checked ? 1 : 0,
        };
        if ( flag ) {
          return;
        }
        validate_list.map(item => {
          saveData[item] = $('#' + item).val();
        })

        saveData.active_rule = JSON.stringify(active_rule);
        saveData.addFile = JSON.stringify(addFile);
        saveData.advance_options = JSON.stringify(advance_options);
        saveData.campaign = $('#campaign').val();
        return true;
      },
      onFinishing: () => {
        const weightHit = $('.weight-or-max_hit');
        let sumHit = 0;
        const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
        switch(rotate_checked) {
          case '1':
            weightHit.each(function(){
              sumHit += Number($(this).val());
            })

            if ( sumHit != 100 ) {
              toastr.warning('Total value must be 100!','Warning');
              return false;
            }
            break;
        }
        if (!weightHit.length) {
          toastr.warning('No exist rows!','Warning');
          return false;
        }

        saveData.rotation_option = $("input[type='radio'][name='rotate_option']:checked").val();
        saveData.id = $('input[name="_id"]').val();
        addUrlList();
        let flag = 0;
        async function SaveData() {
          await $.ajax({
            async: false,
            type: 'post',
            url: createURL,
            data: saveData,
            success:function(res) {
              if ($('input[name="_id"]').val() == -1) {
                Swal.fire({
                  title: "URL Rotator successfully created.",
                  html : "<p>Your Unique URL is</p><p>"+res.url+"</p>",
                  type: "success",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  confirmButtonText: `RETURN TO DASHBOARD`,
                  allowOutsideClick:false
                }).then((res) => {
                  if (res.value) {
                    window.location.href = '/redirects';
                  }
                })
              }
              else {
                Swal.fire({
                  title: "URL Rotator successfully updated.",
                  type: "success",
                  confirmButtonClass: 'btn btn-primary',
                  buttonsStyling: false,
                  confirmButtonText: `RETURN TO DASHBOARD`,
                  allowOutsideClick:false
                }).then((res) => {
                  if (res.value) {
                    window.location.href = '/redirects';
                  }
                })
              }
              flag = 1;
            }
          })
        }
         SaveData();
        if (flag) return true;
      },
      onFinished: function (event, currentIndex) {
      }
  });
  $(".select2").select2({
    // the following code is used to disable x-scrollbar when click in select input and
    // take 100% width in responsive also
    dropdownAutoWidth: true,
    width: '100%'
  });
  $('input.select2-search__field').attr({'name' : 'country-select-input'});

  $('#rule-box-toggle').click(()=> {

    const rule = $('#active_rule').val();
    if (rule) {
      $('.' + rule).removeClass('hidden');
      const index = active_rule.indexOf(rule);
      if (index == -1) active_rule.push(ruleList.indexOf(rule));
    }
  })
  $('#active_rule').change((e)=> {
    if ($(this).val() == '') e.preventDefault();
  })
  $('#country-group').change(function(){
    const NewList = ($(this).val()).split(',');
    let CountryList = $('#country-list').val();
    CountryList = [...CountryList, ...NewList];
    CountryList = [...new Set(CountryList)];
    $('#country-list').val(CountryList).change();
  })
  $('.remove-btn').click(function() {
    const group = $(this).attr('data-group');
    $('.' + group).addClass('hidden');
    const index = $('.remove-btn').index($(this));
    const exist = active_rule.indexOf(index);
    active_rule.splice(exist, 1);
    if (addFile.length) addFile.splice(index, 1);
    $('#active_rule').val('');
  })
  $('#dest_url').change(function(){
    if ($(this).val() == 1) {
      $('#url-add-btn').removeClass('hidden');
      $('#custom-url-add-btn').addClass('hidden');
    }
    else {
      $('#url-add-btn').addClass('hidden');
      $('#custom-url-add-btn').removeClass('hidden');
    }
  })
  $('#url-add-btn').click(function() {
    $('.new-url-group').removeClass('hidden');
  })
  $('#addgroup-hide-btn').click(function(){
    $('.new-url-group').addClass('hidden');
  })
  $('body').on('change', '.spoof-switch', function() {
    const index = $('.spoof-switch').index($(this));
    const checked = $(this).prop('checked');
    $('.add-spoof-select').eq(index).toggleClass('hidden');
    $('.deep-switch').eq(index).attr('disabled', checked);
    if (checked) {
      Swal.fire({
        html : "<p>The spoofed URL will be generated in the background. Please wait at least one minute before sending traffic to your redirect URL.</p>",
        type: "info",
        confirmButtonClass: 'btn btn-primary'
      })
    }
  })
  $('body').on('change', '.deep-switch', function() {
    const index = $('.deep-switch').index($(this));
    const checked = $(this).prop('checked');
    const dest_url = $('.dest-url-link').eq(index).text();
    const res = getDomain(dest_url);
    if (!res) {
      $(this).prop('checked', false)
      return;
    }
    $('.add-spoof-select').eq(index).addClass('hidden');
    $('.spoof-switch').eq(index).attr('disabled', checked);
  })
  $('input[name="rotate_option"]').change(function(){
    switch($(this).val()) {
      case '1':
        $('.weight-hit').addClass('hidden');
        $('.weight-text').removeClass('hidden');
        $('#weight-or-max_hit').show().attr({'placeholder': 'Weight'});
        $('.all-url-list-group').removeClass('hide-weight');
        $('.weight-or-max_hit, #weight-or-max_hit').val('');
        break;
      case '3':
        $('#weight-or-max_hit').show().attr({'placeholder': 'Max hits'});
        $('.weight-hit').addClass('hidden');
        $('.max-hit-text').removeClass('hidden');
        $('.all-url-list-group').removeClass('hide-weight');
        $('.weight-or-max_hit, #weight-or-max_hit').val('');
        break;
      default:
        $('.weight-hit').addClass('hidden');
        $('.max-hit-text').addClass('hidden');
        $('#weight-or-max_hit').hide();
        $('.all-url-list-group').addClass('hide-weight');
    }
    switch($(this).val()) {
      case '1':
        if ($('.target-item-group').length) {
          $('.realtime-weight').removeClass('hidden').addClass('d-flex');
          calculate_totalweight();
        }
        $('.all-url-list-group').addClass('hidden-move');
        break;
      case '2':
        $('.all-url-list-group').removeClass('hidden-move');
        $('.realtime-weight').addClass('hidden').removeClass('d-flex');
        break;
      default:
        $('.all-url-list-group').addClass('hidden-move');
        $('.realtime-weight').addClass('hidden').removeClass('d-flex');
        total_weight = 0;
        break;
    }
  })
  $('#add-spoof-switch').change(function() {
    $('#add-spoof-select').toggleClass('hidden');
    const checked = $(this).prop('checked');
    $('#add-deep-switch').attr('disabled', checked);
    if (checked) {
      Swal.fire({
        html : "<p>The spoofed URL will be generated in the background. Please wait at least one minute before sending traffic to your redirect URL.</p>",
        type: "info",
        confirmButtonClass: 'btn btn-primary'
      })
    }
  })
  $('#add-deep-switch').change(function(){
    const checked = $(this).prop('checked');
    const dest_url = $('#target-url').val();
    const res = getDomain(dest_url);
    if (!res) {
      $(this).prop('checked', false)
      return;
    }
    $('#add-spoof-switch').attr('disabled', checked);
  })
  $('#new-url-add-btn').click(function(){
    const targetUrl = $('#target-url').val();
    const weightHit = $('#weight-or-max_hit').val();
    const spoof_checked = $('#add-spoof-switch').prop('checked');
    const deep_checked = $('#add-deep-switch').prop('checked');
    const spoof_service = $('#add-spoof-select').val();
    let res = $('.new-url-group').validate({
      rules:{
        'target-url': {
          required: true,
          url: true
        }
      }
    });
    $('.new-url-group').valid();
    if (res.errorList.length) {
      return;
    }
    const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
    const data_index = !$('.target-item-group:last-child').length ? 0 : Number($('.target-item-group:last-child').attr('data-index')) + 1;
    var html = '<div class="form-group row target-item-group"  data-index="'+data_index+'">'+
        '<div class="col-md-4 col-8">'+
          '<span class="dest-url-link">'+targetUrl+'</span>'+
        '</div>'+
        '<div class="col-md-2 col-4">'+
          '<div class="form-group">'+
            '<input type="number" class="form-control form-control-sm weight-or-max_hit" value="' + weightHit + '">'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2">'+
          '<div class="row">'+
            '<div class="col-md-4 col-6">'+
              '<div class="custom-control custom-switch custom-switch-success mr-2">'+
                '<input type="checkbox" class="custom-control-input custom-control-input-sm spoof-switch" id="spoof-switch'+$('.target-item-group').length+'" '+(spoof_checked ? 'checked' : '')+'>'+
                '<label class="custom-control-label" for="spoof-switch'+$('.target-item-group').length+'"></label>'+
              '</div>'+
            '</div>'+
            '<div class="col-md-8 col-6">'+
              '<select class="form-control form-control-sm add-spoof-select '+(spoof_checked ? '' : 'hidden')+'">'+
                '<option value="0"'+(spoof_service == '0' ? ' selected' : '')+'>Google</option>'+
              '</select>'+
            '</div>'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2 col-6">'+
          '<div class="custom-control custom-switch custom-switch-success mr-2">'+
            '<input type="checkbox" class="custom-control-input custom-control-input-sm deep-switch" id="deep-switch'+$('.target-item-group').length+'" '+(deep_checked ? 'checked' : '')+'>'+
            '<label class="custom-control-label" for="deep-switch'+$('.target-item-group').length+'"></label>'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2 col-6 justify-content-end d-flex">'+
          '<a class="handle fa fa-arrows fa-2x mr-1"></a>'+
          '<a href="'+targetUrl+'" class="fa fa-external-link fa-2x mr-1 mt-2px"></a>'+
          '<a href="#" class="target-item-remove fa fa-trash fa-2x"></a>'+
        '</div>'+
      '</div>';
    // $('.all-url-list-group').html($('.all-url-list-group').html() + html);
    $('.all-url-list-group').append(html);
    addUrlList();
    $('#target-url').val('');
    $('#weight-or-max_hit').val('');
    $('#add-spoof-switch').prop({'checked': false, 'disabled': false});
    $('#add-deep-switch').prop({'checked': false, 'disabled': false});
    $('#add-spoof-select').val(0).addClass('hidden');
  })
  $('#custom-url-add-btn').click(function() {
    const url = $('#dest_url').val();
    const data_index = !$('.target-item-group:last-child').length ? 0 : Number($('.target-item-group:last-child').attr('data-index')) + 1;
    var html = '<div class="form-group row target-item-group"  data-index="'+data_index+'">'+
        '<div class="col-md-4 col-8">'+
          '<span class="dest-url-link">'+url+'</span>'+
        '</div>'+
        '<div class="col-md-2 col-4">'+
          '<div class="form-group">'+
            '<input type="number" class="form-control form-control-sm weight-or-max_hit">'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2">'+
          '<div class="row">'+
            '<div class="col-md-4 col-6">'+
              '<div class="custom-control custom-switch custom-switch-success mr-2">'+
                '<input type="checkbox" class="custom-control-input custom-control-input-sm spoof-switch" id="spoof-switch'+data_index+'" disabled>'+
                '<label class="custom-control-label" for="spoof-switch'+data_index+'"></label>'+
              '</div>'+
            '</div>'+
            '<div class="col-md-8 col-6">'+
              '<select class="form-control form-control-sm add-spoof-select hidden">'+
                '<option value="0">Google</option>'+
              '</select>'+
            '</div>'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2 col-6">'+
          '<div class="custom-control custom-switch custom-switch-success mr-2">'+
            '<input type="checkbox" class="custom-control-input custom-control-input-sm deep-switch" id="deep-switch'+data_index+'" disabled>'+
            '<label class="custom-control-label" for="deep-switch'+data_index+'"></label>'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2 col-6 justify-content-end d-flex">'+
          '<a class="handle fa fa-arrows fa-2x mr-1"></a>'+
          '<a href="'+url+'" class="fa fa-external-link fa-2x mr-1 mt-2px"></a>'+
          '<a href="#" class="target-item-remove fa fa-trash fa-2x"></a>'+
        '</div>'+
      '</div>';
    // $('.all-url-list-group').html($('.all-url-list-group').html() + html);
    $('.all-url-list-group').append(html);
    addUrlList();
  })
  function addUrlList () {
    const url_list = [];
    const DestUrls = $('.dest-url-link');
    total_weight = 0;
    DestUrls.each(function(index) {
      let row = {};
      row.dest_url = $(this).text();
      row.uuid = index;
      const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
      const weightHit = $('.weight-or-max_hit');
      row.spoof_referrer = $('.spoof-switch').eq(index).prop('checked') ? 1 : 0;
      row.spoof_type = $('.spoof-switch').eq(index).prop('checked') ? $('.add-spoof-select').eq(index).val() : 0;
      row.deep_link = $('.deep-switch').eq(index).prop('checked') ? 1 : 0;
      switch(rotate_checked) {
        case '1':
          total_weight += Number(weightHit.eq(index).val());
          row.weight = !weightHit.eq(index).val() ? 0 : weightHit.eq(index).val();
          break;
        case '3':
          row.max_hit = !weightHit.eq(index).val() ? 0 : weightHit.eq(index).val();
          break;
      }
      url_list.push(row);
    })
    if (DestUrls.length && $('input[name="rotate_option"]').val() == '1') {
      $('.realtime-weight').removeClass('hidden');
      $('.weight-value').text(total_weight);
      calculate_totalweight();
    }
    saveData.url_list = JSON.stringify(url_list);  }
  $('body').on('input','.weight-or-max_hit',function() {
    const rotate = $("input[type='radio'][name='rotate_option']:checked").val();
    switch(rotate) {
      case '1':
        calculate_totalweight();
        break;
    }
  })
  $('#upload-btn').click(function(){
    $('#csv-file').click();
  })
  $('#csv-file').change(function(){
    let sendData = new FormData();
    sendData.append('_token',$('input[name="_token"]').val());
    sendData.append('file',$(this)[0].files[0]);
    console.log(sendData.get('_token'));
    $.ajax({
      type:'post',
      url: '/get-csv-data',
      data: sendData,
      cache: false,
      contentType: false,
      processData: false,
      success:function(res) {
        let html = '';
        const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
        res.map((item, index) => {
          html += '<div class="form-group row target-item-group" data-index="'+index+'">'+
            '<div class="col-md-4 col-8">'+
              '<span class="dest-url-link">'+item.dest_url+'</span>'+
            '</div>'+
            '<div class="col-md-2 col-4">'+
              '<div class="form-group">'+
                '<input type="number" class="form-control form-control-sm weight-or-max_hit" value="' + item.weight_hit + '">'+
              '</div>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<div class="form-group row">'+
                  '<div class="col-md-12">'+
                    '<div class="row">'+
                      '<div class="col-md-4 col-6">'+
                        '<div class="custom-control custom-switch custom-switch-success mr-2">'+
                          '<input type="checkbox" class="custom-control-input custom-control-input-sm spoof-switch" id="spoof-switch'+index+'" '+(item.deep_checked ? 'disabled' : 'checked')+'>'+
                          '<label class="custom-control-label" for="spoof-switch'+index+'"></label>'+
                        '</div>'+
                      '</div>'+
                      '<div class="col-md-8 col-6">'+
                        '<select class="form-control form-control-sm add-spoof-select '+(item.spoof_checked ? '' : 'hidden')+'">'+
                          '<option value="0"'+(item.spoof_service == '0' ? ' selected' : '')+'>Google</option>'+
                        '</select>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
            '</div>'+
            '<div class="col-md-2 col-6">'+
              '<div class="form-group row">'+
                '<div class="col-md-12">'+
                  '<div class="custom-control custom-switch custom-switch-success mr-2">'+
                    '<input type="checkbox" class="custom-control-input custom-control-input-sm deep-switch" id="deep-switch'+index+'" '+(item.spoof_checked ? 'disabled' : 'checked')+'>'+
                    '<label class="custom-control-label" for="deep-switch'+index+'"></label>'+
                  '</div>'+
                '</div>'+
              '</div>'+
            '</div>'+
            '<div class="col-md-2 col-6 justify-content-end d-flex">'+
              '<a class="handle fa fa-arrows fa-2x mr-1"></a>'+
              '<a href="'+item.dest_url+'" class="fa fa-external-link fa-2x mr-1 mt-2px"></a>'+
              '<a href="#" class="target-item-remove fa fa-trash fa-2x"></a>'+
            '</div>'+
          '</div>'
        })
        $('.all-url-list-group').html(html);
        addUrlList();
      }
    })
    $(this).prop('type','text').prop('type','file');

  })
  $('body').on('click','.target-item-remove',function(){
    const index = $('.target-item-remove').index($(this));
    $('.target-item-group').eq(index).remove();
    if (!$('.target-item-group').length) {
      $('.realtime-weight').addClass('hidden');
    }
  })

  function calculate_totalweight() {
    total_weight = 0;
    $('.weight-or-max_hit').each(function() {
      const value = $(this).val();
      total_weight += Number(value);
    })
    $('.realtime-weight').removeClass('hidden');
    $('.weight-value').text(total_weight + '%');
    if (total_weight == 100) {
      $('.realtime-weight').addClass('text-success').removeClass('text-danger');
    }
    else {
      $('.realtime-weight').removeClass('text-success').addClass('text-danger');
    }
  }
})
