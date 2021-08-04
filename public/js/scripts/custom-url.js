$(function(){
  const ruleList = ['geo-ip-group','proxy-group','referrer-group','empty-referrer-group','device-type-group'];
  let active_rule = [];
  let addFile = {};
  const validate_list = ['link_name','dest_url','tracking_url','pixel','max_hit_day','fallback_url'];
  $(".select2").select2({
    // the following code is used to disable x-scrollbar when click in select input and
    // take 100% width in responsive also
    dropdownAutoWidth: true,
    width: '100%'
  });
  $('#spoof-refer-switch').change(()=> {
    const SpoofSelect = $('#spoof-select');
    if (SpoofSelect.hasClass('hidden')) SpoofSelect.removeClass('hidden');
    else SpoofSelect.addClass('hidden');
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
  $('#done-btn').click(() => {

    let flag = 0;
    let saveData = {
      '_token': $('meta[name="csrf-token"]').attr('content')
    };
    validate_list.map((item, key) => {
      if (!$('#'+item).val()) flag = 1;
    })
    if (!active_rule.length) flag = 1;
    switch($('#active_rule').val()) {
      case 'geo-ip-group':
        if (!$('#country-list').val()) flag = 1;
        break;
      case 'referrer-group':
        if (!$('#domain-name').val()) flag = 1;
        break;
    }

    if (flag) {
      toastr.warning('Warning', 'Input is invalid!');
      return;
    }
    active_rule.map((item) => {
      let row = {};
      switch(item) {
        case 0:
          let country_list = $('#country-list').val();
          if (!country_list.length) {
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

    let spoof_sevice = '';
    if (advance_options.spoof) spoof_service = $('#spoof-select').val();

    validate_list.map(item => {
      saveData[item] = $('#' + item).val();
    })
    if ( !active_rule || !addFile || flag ) {
      toastr.warning('Warning', 'Input is invalid!');
      return;
    }
    saveData['active_rule'] = JSON.stringify(active_rule);
    saveData['addFile'] = JSON.stringify(addFile);
    saveData.advance_options = JSON.stringify(advance_options);
    saveData.spoof_service = spoof_sevice;
    saveData.campaign = $('#campaign').val();

    $.ajax({
      type: 'post',
      url: createURL,
      data: saveData,
      success:(res) => {
        if (res.status) toastr.success('Created','Success');
        Swal.fire({
          title: "Custom URL successfully created.",
          html : "<p>Your Unique URL is</p><p>"+res.url+"</p>",
          type: "success",
          confirmButtonClass: 'btn btn-primary',
          buttonsStyling: false,
        });
      },
      error: () => {
        toastr.error('Created Error!','Error');
      },
      complete:() => {
        validate_list.map((item, key) => {
          $('#'+item).val('');
        })
        $('input, select').val('');
        $('select').val($('select option').eq(0)[0].value);
        $('#active_rule').val('');
        $('#country-list').val('');
        $('.rule-group').addClass('hidden');
        active_rule =  [];
        addFile = {};
        advance_options = {};
      }
    })
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
})
