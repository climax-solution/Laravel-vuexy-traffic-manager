$(function(){
  const ruleList = ['geo-ip-group','proxy-group','referrer-group','empty-referrer-group','device-type-group'];
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
              required: true
            },
            dest_url: {
              required: true,
              url: true
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

        if (($('#dest_url').val()).indexOf("{keyword}") < 0) {
          toastr.warning('Please input destination url correctly!');
          return false;
        }

        let advance_options = {
          blank: $('#blank-refer-switch')[0].checked ? 1 : 0,
          deep: $('#deep-link-switch')[0].checked ? 1 : 0
        };
        if ( flag ) {
          return;
        }
        let spoof_service = '';
        if (advance_options.spoof) spoof_service = $('#spoof-select').val();
        validate_list.map(item => {
          saveData[item] = $('#' + item).val();
        })

        saveData.active_rule = JSON.stringify(active_rule);
        saveData.addFile = JSON.stringify(addFile);
        saveData.advance_options = JSON.stringify(advance_options);
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
            if (sumHit != 100) {
              toastr.warning('Total value must be 100!');
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
              if($('input[name="_id"]').val() == -1) {
                Swal.fire({
                  title: "Keyword Rotator URL successfully created.",
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
                  title: "Keyword Rotator URL successfully updated.",
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

  $('#country-group').change(function(){
    const NewList = ($(this).val()).split(',');
    let CountryList = $('#country-list').val();
    CountryList = [...CountryList, ...NewList];
    CountryList = [...new Set(CountryList)];
    $('#country-list').val(CountryList).change();
  })
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
  $('#addgroup-hide-btn').click(function(){
    $('.new-url-group').addClass('hidden');
  })
  $('#new-url-add-btn').click(function(){
    let rule_option = {
      'weight-or-max_hit': {
        required: true
      },
      'keyword': {
        required: true
      }
    };
    let res = $('.new-url-group').validate({
      rules: rule_option
    });
    $('.new-url-group').valid();
    if (res.errorList.length) {
      return;
    }
    const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
    let keyword = $('#keyword').val();
    const dest_url = $('#dest_url').val();
    // const st_k = dest_url.indexOf('{'); const en_k = dest_url.indexOf('}');
    keyword = keyword.replaceAll(' ', '+');
    let preview_link = dest_url.replaceAll('{keyword}',keyword);
    let html ='<div class="form-group row target-item-group d-flex">'+
        '<div class="col-md-2 col-6 d-table">'+
        '<input type="text" class="keyword d-table-cell align-middle form-control" value="'+keyword+'">'+
        '</div>'+
        '<div class="col-md-2 col-6 d-table">'+
          '<div class="form-group d-table-cell align-middle">'+
            '<input type="number" class="form-control weight-or-max_hit" value="'+$('#weight-or-max_hit').val()+'">'+
          '</div>'+
        '</div>'+
        '<div class="col-md-6 col-9 d-table">'+
          '<p class="preview-link text-break-all d-table-cell align-middle">'+preview_link+'</p>'+
        '</div>'+
        '<div class="col-md-2 col-3 d-flex justify-content-around">'+
          '<a class="fa fa-arrows fa-2x handle"></a>'+
          '<a href="'+preview_link+'" target="_blank"><i class="fa fa-external-link fa-2x"></i></a>'+
          '<a href="#" class="target-item-remove"><i class="fa fa-trash fa-2x"></i></a>'+
        '</div>'+
      '</div>' ;
    $('.target-keywords-group').html($('.target-keywords-group').html() + html);
    addUrlList();
    $('#asin').val('');
    $('#keyword').val('');
    $('#weight-or-max_hit').val('');
    $('#custom-parameter').val('');
  })
  function addUrlList () {
    const url_list = [];
    const Kewyword = $('.keyword');
    Kewyword.each(function(index) {
      let row = {};
      row.keyword = $(this).val();
      row.uuid = index;
      row.dest_url = $('.preview-link').eq(index).text();
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

  $('input[name="rotate_option"]').change(function(){
    switch($(this).val()) {
      case '1':
        $('.weight-label, .weight-text').removeClass('hidden');
        $('.max_hit-label, .max-hit-text').addClass('hidden');
        $('#target-keywords-group').removeClass('hide-weight');
        $('.weight-max-hit').removeClass('hidden');
        $('.weight-hit-text').addClass('d-md-block');
        break;
      case '3':
        $('.weight-label, .weight-text').addClass('hidden');
        $('.max_hit-label, .max-hit-text').removeClass('hidden');
        $('#target-keywords-group').removeClass('hide-weight');
        $('.weight-max-hit').removeClass('hidden');
        $('.weight-hit-text').addClass('d-md-block');
        break;
      default:
        $('.weight-label, .weight-text, .max-hit-text, .max_hit-label').addClass('hidden');
        $('#target-keywords-group').addClass('hide-weight');
        $('.weight-max-hit').addClass('hidden');
        $('.weight-hit-text').removeClass('d-md-block');
        break;
    }
    switch($(this).val()) {
      case '2':
        $('#target-keywords-group').removeClass('hide-move');
        break;
      default:
        $('#target-keywords-group').addClass('hide-move');
        break;
    }
  })

  $('#csv-file').change(function(){
    let sendData = new FormData();
    sendData.append('_token',$('input[name="_token"]').val());
    if (!$(this).val()) {
      toastr.warning('No selected file!', 'Warning');
      return;
    }
    sendData.append('file',$(this)[0].files[0]);
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
          html += '<div class="form-group row target-item-group d-flex">'+
          '<div class="col-md-2 col-6 d-table">'+
            '<input type="text" class="keyword d-table-cell align-middle form-control" value="'+item.keyword+'">'+
          '</div>'+
          '<div class="col-md-2 col-6 d-table">'+
            '<div class="form-group d-table-cell align-middle">'+
            '<input type="number" class="form-control weight-or-max_hit" value="'+item.weight_hit+'">'+
            '</div>'+
          '</div>'+
          '<div class="col-md-6 col-9 d-table">'+
            '<span class="preview-link text-break-all d-table-cell align-middle">'+item.dest_url+'</span>'+
          '</div>'+
          '<div class="col-md-2 col-3 d-flex justify-content-around">'+
            '<a class="fa fa-arrows fa-2x handle"></a>'+
            '<a href="'+item.dest_url+'" target="_blank"><i class="fa fa-external-link fa-2x"></i></a>'+
            '<a href="#" class="target-item-remove"><i class="fa fa-trash fa-2x"></i></a>'+
          '</div>'+
        '</div>' ;
        })
        $('.target-keywords-group').html(html);
        addUrlList();
      }
    })
    $(this).prop('type','text').prop('type','file');
  })

  $('#deep-link-switch').change(function() {
    const dest_url = $('#dest_url').val();
    const res = getDomain(dest_url);
    if (!res) {
      $(this).prop({'checked': false});
      return;
    }
  })

})
