$(function(){
  const ruleList = ['geo-ip-group','proxy-group','referrer-group','empty-referrer-group','device-type-group'];
  let addFile = {};
  const validate_list = ['link_name','tracking_url','pixel','max_hit_day','fallback_url','amazon_aff_id'];
  let step_text = 'ASIN 2-Step URL';
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


        let advance_options = {
          blank: $('#blank-refer-switch')[0].checked ? 1 : 0,
          spoof: $('#spoof-refer-switch')[0].checked ? 1 : 0,
          deep: $('#deep-link-switch')[0].checked ? 1 : 0
        };
        if (flag ) {
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
        saveData.spoof_service = spoof_service;
        saveData.campaign = $('#campaign').val();
        if ($('#link_type').val() == '4') $('.brand-input').removeClass('hidden');
        else $('.brand-input').addClass('hidden');
        switch($('#link_type').val()) {
          case '1':
            step_text = 'STOREFRONT 2-STEP URL';
            $('#asin-label').addClass('hidden');
            $('#merchant-label').removeClass('hidden');
            break;
          case '2':
            step_text = 'HIDDEN KEYWORD 2-STEP URL';
            break;
          case '3':
            step_text = 'PRODUCT PAGE FROM SEARCH RESULTS';
            break;
          case '4':
            step_text = 'BRAND 2-STEP URL';
            break;
          default:
            $('#asin-label').removeClass('hidden');
            $('#merchant-label').addClass('hidden');
            break;
        }
        $('.step-text').html(step_text);
        return true;
      },
      onFinishing: () => {
        const itemList = $('.target-item-group');
        if (!itemList.length) {
          toastr.warning('No existing rows');
          return false;
        }
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
        saveData.rotation_option = $("input[type='radio'][name='rotate_option']:checked").val();
        saveData.id = $('input[name="_id"]').val();
        saveData.link_type = $('#link_type').val();
        addUrlList();
        let flag = 0;
        async function SaveData() {
          await $.ajax({
            async: false,
            type: 'post',
            url: createURL,
            data: saveData,
            success:function(res) {
              let title = '';
              switch($('#link_type').val()) {
                case '3':
                  title = 'Product Page from Search Results URL';
                  break;
                case '4':
                  title = 'Brand 2-Step URL';
                  break;
                default:
                  title = 'ASIN 2-Step URL';
                  break;
              }
              if ($('input[name="_id"]').val() == -1) {
                Swal.fire({
                  title: title + " successfully created.",
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
                  title: title + " successfully updated.",
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
  // $('.spoof-switch').on('change', function() {
  //   const index = $('.spoof-switch').index($(this));
  //   $('.add-spoof-select').eq(index).toggleClass('hidden');
  // })

  $('#spoof-refer-switch').change(function() {
    const SpoofSelect = $('#spoof-select');
    const checked = $(this).prop('checked');
    if ($(this).prop('checked')) {
      SpoofSelect.removeClass('hidden');
      Swal.fire({
        html : "<p>The spoofed URL will be generated in the background. Please wait at least one minute before sending traffic to your redirect URL.</p>",
        type: "info",
        confirmButtonClass: 'btn btn-primary'
      })
    }
    else {
      SpoofSelect.addClass('hidden');
    }
    $('#deep-link-switch').attr('disabled', checked);
  })
  $('#deep-link-switch').change(function(){
    const checked = $(this).prop('checked');
    $('#spoof-refer-switch').attr('disabled', checked);
  })
  $('#country-group').change(function(){
    const NewList = ($(this).val()).split(',');
    let CountryList = $('#country-list').val();
    CountryList = [...CountryList, ...NewList];
    CountryList = [...new Set(CountryList)];
    $('#country-list').val(CountryList).change();
  })

  $('input[name="rotate_option"]').change(function(){
    switch($(this).val()) {
      case '1':
        $('.weight-hit').addClass('hidden');
        $('.weight-text').removeClass('hidden');
        $('.weight-max_hit-group').removeClass('hidden');
        $('#target-urls-group').removeClass('hide-weight');
        $('.weight-hit-text').addClass('d-md-block');
        $('.weight-or-max_hit, #weight-or-max_hit').val('');
        break;
      case '3':
        $('.weight-hit').addClass('hidden');
        $('.max-hit-text').removeClass('hidden');
        $('.weight-max_hit-group').removeClass('hidden');
        $('#target-urls-group').removeClass('hide-weight');
        $('.weight-hit-text').addClass('d-md-block');
        $('.weight-or-max_hit, #weight-or-max_hit').val('');
        break;
      default:
        $('.weight-max_hit-group').addClass('hidden');
        $('.weight-hit').addClass('hidden');
        $('.weight-text').addClass('hidden');
        $('#target-urls-group').addClass('hide-weight');
        $('.weight-hit-text').removeClass('d-md-block');
        break;
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
        break;
    }
  })
  $('#add-spoof-switch').change(function() {
    $('#add-spoof-select').toggleClass('hidden');
  })
  $('#new-url-add-btn').click(function(){
    let rule_option = {
      'asin': {
        required: true,
        minlength: 10,
        maxlength: 10
      },
      'keyword': {
        required: true
      }
    };
    if ($('#link_type').val() == '4') rule_option = {...rule_option, ...{brand:{required: true}}};
    let res = $('.new-url-group').validate({
      rules: rule_option
    });
    $('.new-url-group').valid();
    if (res.errorList.length) {
      return;
    }
    const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
    const market = $('#market-place').val();
    let keyword = $('#keyword').val(); keyword = keyword.replaceAll(' ', '+');
    let custom_parameter =  $('#custom-parameter').val();
    let preview_link = 'https://www.amazon.' + market + '/s?k=' + keyword + '&rh=p_78%3A' + $('#asin').val() + `${custom_parameter ? '&' + custom_parameter : ''}`;
    switch($('#link_type').val()) {
      case '1':
        preview_link = 'https://www.amazon.' + market + '/s?k=' + keyword + '&me=' + $('#asin').val() + '&ref=nb_sb_noss' + `${custom_parameter ? '&' + custom_parameter : ''}`;
        step_text = 'STOREFRONT 2-STEP URL';
        break;
      case '2':
        preview_link = 'https://www.amazon.' + market + '/s?k=' + keyword + '&hidden-keywords=' + $('#asin').val() + '&ref=nb_sb_noss_1' + `${custom_parameter ? '&' + custom_parameter : ''}`;
        step_text = 'HIDDEN KEYWORD 2-STEP URL';
        break;
      case '3':
        let rand_1 = Math.floor(Math.random() * 14);
        let rand_2 = Math.floor(Math.random() * 10);
        rand_1 = !rand_1 ? 1 : rand_1;
        rand_2 = !rand_2 ? 1 : rand_2;
        preview_link = 'https://www.amazon.' + market + '/dp/' + $('#asin').val() + '/ref=sr_1_2?ie=UTF8&keywords=' + keyword + '&qid=' + Date.now() + '&s=gateway&sr=' + rand_1 + '-' + rand_2 + `${custom_parameter ? '&' + custom_parameter : ''}`;
        step_text = 'PRODUCT PAGE FROM SEARCH RESULTS' ;
        break;
      case '4':
        preview_link = 'https://www.amazon.' + market + '/s?k=' + keyword + '&rh=p_4%3A'+$('#brand').val()+'%2Cp_78%3A' + $('#asin').val() + '%2Cssx%3Arelevance&ref=nb_sb_noss_2' + `${custom_parameter ? '&' + custom_parameter : ''}`;
        step_text = 'BRAND 2-STEP URL';
        break;
    }
    let html ='<div class="form-group row target-item-group">'+
        '<div class="col-md-2 col-6">'+
          '<span class="keyword">'+keyword+'</span>'+
        '</div>'+
        '<div class="col-md-2 col-6">'+
          '<div class="form-group">'+
            '<input type="number" class="form-control weight-or-max_hit" value="'+$('#weight-or-max_hit').val()+'"/>'+
          '</div>'+
        '</div>'+
        '<div class="col-md-6 col-9">'+
          '<p class="preview-link text-break-all">'+preview_link+'</p>'+
        '</div>'+
        '<div class="col-md-2 col-3 d-flex justify-content-end">'+
          '<a class="fa fa-arrows handle fa-2x mr-1"></a>'+
          '<a href="'+preview_link+'" target="_blank" class="fa fa-external-link fa-2x mr-1 mt-2px"></a>'+
          '<a class="target-item-remove fa fa-trash fa-2x" href="#"></a>'+
        '</div>'+
      '</div>' ;
    $('.all-url-list-group').html($('.all-url-list-group').html() + html);
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
      row.keyword = $(this).text();
      row.uuid = index;
      row.dest_url = $('.preview-link').eq(index).text();
      const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
      const weightHit = $('.weight-or-max_hit');
      switch(rotate_checked) {
        case '1':
          row.weight = !weightHit.eq(index).val() ? 0 : weightHit.eq(index).val();
          break;
        case '3':
          row.max_hit = !weightHit.eq(index).val() ? 0 : weightHit.eq(index).val();
          break;
      }
      url_list.push(row);
    })
    if (Kewyword.length && $('input[name="rotate_option"]').val() == '1') {
      $('.realtime-weight').removeClass('hidden');
      $('.weight-value').text(total_weight);
      calculate_totalweight();
    }
    saveData.url_list = JSON.stringify(url_list);
  }

  $('#upload-btn').click(function(){
    $('#csv-file').click();
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
      url: '/get-csv-data-step-asin',
      data: sendData,
      cache: false,
      contentType: false,
      processData: false,
      success:function(res) {
        let html = '';
        const rotate_checked = $("input[type='radio'][name='rotate_option']:checked").val();
        res.map((item, index) => {
          html += '<div class="form-group row target-item-group">'+
          '<div class="col-md-2 col-6">'+
            '<span class="keyword">'+item.keyword+'</span>'+
          '</div>'+
          '<div class="col-md-2 col-6">'+
            '<div class="form-group">'+
              '<input type="number" class="form-control weight-or-max_hit" value="'+item.weight_hit+'"/>'+
            '</div>'+
          '</div>'+
          '<div class="col-md-6 col-9">'+
            '<span class="preview-link text-break-all">'+item.dest_url+'</span>'+
          '</div>'+
          '<div class="col-md-2 col-3 d-flex justify-content-end">'+
            '<a class="fa fa-arrows handle fa-2x mr-1"></a>'+
            '<a href="'+item.dest_url+'" target="_blank" class="fa fa-external-link fa-2x mr-1 mt-2px"></a>'+
            '<a class="target-item-remove fa fa-trash fa-2x" href="#"></a>'+
          '</div>'+
        '</div>' ;
        })
        $('.all-url-list-group').html(html);
        addUrlList();
      }
    })
    $(this).prop('type','text').prop('type','file');
  })
  function calculate_totalweight() {
    total_weight = 0;
    $('.weight-or-max_hit').each(function() {
      const value = $(this).val();
      total_weight += Number(value);
    })
    $('.weight-value').text(total_weight + '%');
    if (total_weight == 100) {
      $('.realtime-weight').addClass('text-success').removeClass('text-danger');
    }
    else {
      $('.realtime-weight').removeClass('text-success').addClass('text-danger');
    }
  }

  $('body').on('input','.weight-or-max_hit',function() {
    const rotate = $("input[type='radio'][name='rotate_option']:checked").val();
    switch(rotate) {
      case '1':
        calculate_totalweight();
        break;
    }
  })
})
