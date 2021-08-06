$(function(){
  const ruleList = ['geo-ip-group','proxy-group','referrer-group','empty-referrer-group','device-type-group'];
  let active_rule = [];
  let addFile = {};
  const validate_list = ['link_name','tracking_url','pixel','max_hit_day','fallback_url'];
  let saveData = {
    '_token': $('meta[name="csrf-token"]').attr('content')
  };
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
              minlength: 10
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
        if (!active_rule.length) {
          toastr.warning('No selected rule.', 'Warning');
          return;
        }
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
                country_group: Number($('#country-group').val()),
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
        if ( !active_rule.length || !addFile || flag ) {
          return;
        }
        let spoof_sevice = '';
        if (advance_options.spoof) spoof_service = $('#spoof-select').val();
        validate_list.map(item => {
          saveData[item] = $('#' + item).val();
        })

        saveData.active_rule = JSON.stringify(active_rule);
        saveData.addFile = JSON.stringify(addFile);
        saveData.advance_options = JSON.stringify(advance_options);
        saveData.spoof_service = spoof_sevice;
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
            if ( !sumHit ) {
              toastr.warning('Total value is wrong!','Warning');
              return false;
            }
            break;
        }
        if (!weightHit.length) {
          toastr.warning('No exist rows!','Warning');
          return false;
        }
        console.log(saveData);
        saveData.rotation_option = $("input[type='radio'][name='rotate_option']:checked").val();
        addUrlList();
        let flag = 0;
        async function SaveData() {
          await $.ajax({
            async: false,
            type: 'post',
            url: createURL,
            data: saveData,
            success:function(res) {
              Swal.fire({
                title: "Custom URL successfully created.",
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
    }
    else $('#url-add-btn').addClass('hidden');
  })
  $('#url-add-btn').click(function() {
    $('.new-url-group').removeClass('hidden');
  })
  $('#addgroup-hide-btn').click(function(){
    $('.new-url-group').addClass('hidden');
  })
  $('.spoof-switch').on('change', function() {
    const index = $('.spoof-switch').index($(this));
    $('.add-spoof-select').eq(index).toggleClass('hidden');
  })
  $('input[name="rotate_option"]').change(function(){
    switch($(this).val()) {
      case '1':
        $('.weight-hit').addClass('hidden');
        $('.weight-text').removeClass('hidden');
        break;
      case '3':
          $('.weight-hit').addClass('hidden');
          $('.max-hit-text').removeClass('hidden');
          break;
    }
  })
  $('#add-spoof-switch').change(function() {
    $('#add-spoof-select').toggleClass('hidden');
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
        },
        'weight-or-max_hit': {
          required: true
        }
      }
    });
    $('.new-url-group').valid();
    if (res.errorList.length) {
      return;
    }
    const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
    const data_index = !$('.target-item-group:last-child').length ? 0 : Number($('.target-item-group:last-child').attr('data-index')) + 1;
    var html ='<div class="form-group row target-item-group"  data-index="'+data_index+'">'+
        '<div class="col-md-4 col-8">'+
          '<span class="dest-url-link">'+targetUrl+'</span>'+
        '</div>'+
        '<div class="col-md-2 col-4">'+
          '<div class="form-group">'+
            '<input type="text" class="form-control form-control-sm weight-or-max_hit" value="' + (rotate_checked == '1' ? weightHit : rotate_checked == '3' ? weightHit : '') + '">'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2">'+
            '<div class="form-group row">'+
              '<div class="col-md-12">'+
                '<div class="row">'+
                  '<div class="col-md-4 col-6">'+
                    '<div class="custom-control custom-switch custom-switch-success mr-2">'+
                      '<input type="checkbox" class="custom-control-input custom-control-input-sm spoof-switch" id="spoof-switch'+$('.target-item-group').length+'" '+(!spoof_checked ? '' : 'checked')+'>'+
                      '<label class="custom-control-label" for="spoof-switch'+$('.target-item-group').length+'"></label>'+
                    '</div>'+
                  '</div>'+
                  '<div class="col-md-8 col-6">'+
                    '<select class="form-control form-control-sm add-spoof-select '+(spoof_checked ? '' : 'hidden')+'">'+
                      '<option value="0"'+(spoof_service == '0' ? ' selected' : '')+'>Google</option>'+
                      '<option value="1"'+(spoof_service == '1' ? ' selected' : '')+'>Twitter</option>'+
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
                '<input type="checkbox" class="custom-control-input custom-control-input-sm deep-switch" id="deep-switch'+$('.target-item-group').length+'" '+(!deep_checked ? '' : 'checked')+'>'+
                '<label class="custom-control-label" for="deep-switch'+$('.target-item-group').length+'"></label>'+
              '</div>'+
            '</div>'+
          '</div>'+
        '</div>'+
        '<div class="col-md-2 col-6 text-right">'+
          '<a href="#"><i class="fa fa-external-link fa-2x mr-1"></i></a>'+
          '<a href="#" class="target-item-remove"><i class="fa fa-trash fa-2x"></i></a>'+
        '</div>'+
      '</div>' ;
    $('.all-url-list-group').html($('.all-url-list-group').html() + html);
    addUrlList();
    $('#target-url').val('');
    $('#weight-or-max_hit').val('');
    $('#add-spoof-switch').prop({'checked': false});
    $('#add-deep-switch').prop({'checked': false});
    $('#add-spoof-select').val(0).toggleClass('hidden');
  })
  function addUrlList () {
    const url_list = [];
    const DestUrls = $('.dest-url-link');
    DestUrls.each(function(index) {
      let row = {};
      row.dest_url = $(this).text();
      row.uuid = index;
      const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
      const weightHit = $('.weight-or-max_hit');
      switch(rotate_checked) {
        case '1':
          row.weight = weightHit.eq(index).val();
          break;
        case '3':
          row.max_hit = weightHit.eq(index).val();
          break;
      }
      url_list.push(row);
    })
    saveData.url_list = JSON.stringify(url_list);
  }

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
                '<input type="text" class="form-control form-control-sm weight-or-max_hit" value="' + (rotate_checked == '1' ? item.weight_hit : rotate_checked == '3' ? item.weight_hit : '') + '">'+
              '</div>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<div class="form-group row">'+
                  '<div class="col-md-12">'+
                    '<div class="row">'+
                      '<div class="col-md-4 col-6">'+
                        '<div class="custom-control custom-switch custom-switch-success mr-2">'+
                          '<input type="checkbox" class="custom-control-input custom-control-input-sm spoof-switch" id="spoof-switch'+index+'" '+(!item.spoof_checked ? '' : 'checked')+'>'+
                          '<label class="custom-control-label" for="spoof-switch'+index+'"></label>'+
                        '</div>'+
                      '</div>'+
                      '<div class="col-md-8 col-6">'+
                        '<select class="form-control form-control-sm add-spoof-select '+(item.spoof_checked ? '' : 'hidden')+'">'+
                          '<option value="0"'+(item.spoof_service == '0' ? ' selected' : '')+'>Google</option>'+
                          '<option value="1"'+(item.spoof_service == '1' ? ' selected' : '')+'>Twitter</option>'+
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
                    '<input type="checkbox" class="custom-control-input custom-control-input-sm deep-switch" id="deep-switch'+index+'" '+(!item.deep_checked ? '' : 'checked')+'>'+
                    '<label class="custom-control-label" for="deep-switch'+index+'"></label>'+
                  '</div>'+
                '</div>'+
              '</div>'+
            '</div>'+
            '<div class="col-md-2 col-6 text-right">'+
              '<a href="#"><i class="fa fa-external-link fa-2x mr-1"></i></a>'+
              '<a href="#" class="target-item-remove"><i class="fa fa-trash fa-2x"></i></a>'+
            '</div>'+
          '</div>'
        })
        $('.all-url-list-group').html(html);
        addUrlList();
      }
    })
    $(this).prop('type','text').prop('type','file');
  })
})
