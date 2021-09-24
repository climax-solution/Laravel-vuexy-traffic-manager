@extends('layouts/contentLayoutMaster')

@section('title', trans($id < 0 ? 'locale.CreateNewRedirects.keyword' : 'locale.EditNewRedirects.keyword'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/dragula.min.css')) }}">


  <style>
    ul {
      list-style-type: none;
    }
    .error {
      color: #ea5455 !important;
    }
    .hide-weight .target-item-group .d-table:nth-child(2){
      display: none !important;
    }
    .hide-weight .target-item-group {
      justify-content: space-between;
    }
    .hidden-move .handle {
      visibility: hidden;
    }
    .list-group-item  {
      border: none;
    }
  </style>
@endsection

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/plugins/toastr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/plugins/extensions/drag-and-drop.css')) }}">

  <style>
    .xx-small {
      font-size: xx-small;
    }
    .space-evenly {
      justify-content: space-evenly;
    }
    .mt-1-2 {
      margin-top: 0.5rem;
    }
    .mt-4px {
      margin-top: 4px;
    }
    .p-1-2 {
      padding: 0.5rem !important;
    }
    .right-2-p {
      right: 2%;
    }
    .top-1-2 {
      top: 0.5rem !important
    }
    .text-break-all {
      word-break: break-all;
    }
  </style>
@endsection

@section('content')
  <input type="hidden" name="_id" value="{{$id}}"/>
  <section id="number-tabs">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <div action="#" class="number-tab-steps wizard-circle">

                <!-- Step 1 -->
                <h6>Redirect settings</h6>
                <fieldset>
                  <form class="row" id="step-wizard-1">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-2">
                              <span>Link Name: </span>
                            </div>
                            <div class="col-md-10">
                                <input type="text" id="link_name" class="form-control" name="link_name" value="{{ isset($url_data->link_name) ? $url_data->link_name : '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-2">
                            <span>Destination URL: </span>
                          </div>
                          <div class="col-md-10">
                              <input type="text" id="dest_url" class="form-control" name="dest_url" value="{{ $dest_url }}">
                              <span class="text-mute xx-small">(eg. https://www.amazon.com/s?k={keyword}&ref=nb_sb_noss_2)</span>
                          </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-2">
                          <span>Tracking URL: </span>
                        </div>
                        <div class="col-md-10">
                          <fieldset class="form-group">
                            <select class="form-control" id="tracking_url" name="tracking_url">
                              @foreach ($track as $key => $item)
                                <option value="{{$key}}" @if(!$key) {{'selected'}} @endif>{{ $item }}</option>
                              @endforeach
                            </select>
                        </fieldset>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-2">
                          <span>Pixel: </span>
                        </div>
                        <div class="col-md-7">
                          <fieldset class="form-group">
                            <select class="form-control" id="pixel" name="pixel">
                              @foreach ($pixel as $key => $item)
                              <option value="{{$key}}">{{ $item }}</option>
                            @endforeach
                            </select>
                            <span class="xx-small">The pixel will be fired before the user is redirected to the Destination URL.</span>
                          </fieldset>
                        </div>
                        <div class="col-md-3">
                          <div class="d-flex mt-1-2 space-evenly">
                            <span class="mt-4px">OR</span>
                            <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light xx-small">CREATE NEW PIXEL</button>
                          </div>
                        </div>
                      </div>
                      <!-- Begin Advanced Options Divider-->
                      <div class="form-group">
                        <span class="font-bold">Advanced Options</span>
                        <hr>
                      </div>
                      <!-- End Advanced Options Divider-->
                      <div class="form-group row">
                        <div class="col-md-5 row">
                          <div class="form-group row col-md-12 col-sm-6 col-7">
                            <div class="col-md-5">
                              <span>Blank Referrer: </span>
                            </div>
                            <div class="col-md-7">
                              <div class="custom-control custom-switch custom-switch-success mr-2 mb-1">
                                <input type="checkbox" class="custom-control-input" id="blank-refer-switch" name="blank-refer-switch">
                                <label class="custom-control-label" for="blank-refer-switch"></label>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row col-md-12 col-sm-6 col-5">
                            <div class="col-md-5">
                              <span>Deep Link<i class="feather icon-help-circle deep-link-help"></i> </span>
                            </div>
                            <div class="col-md-7">
                              <div class="custom-control custom-switch custom-switch-success mr-2 mb-1">
                                <input type="checkbox" class="custom-control-input" id="deep-link-switch" name="deep-link-switch">
                                <label class="custom-control-label" for="deep-link-switch"></label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-7">
                          <div class="form-group row">
                            <div class="col-md-4">
                              <span>Max Hits Day:</span>
                            </div>
                            <div class="col-md-5">
                              <input type='number' class="form-control" id="max_hit_day" name="max_hit_day" value="{{ isset($url_data->max_hit_day) ? $url_data->max_hit_day : '' }}"/>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-md-4">
                              <span>Fallback URL:</span>
                            </div>
                            <div class="col-md-8">
                              <input type="text" id="fallback_url" class="form-control" name="fallback_url" value="{{ isset($url_data->fallback_url) ? $url_data->fallback_url : '' }}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Begin Rules Divider-->
                      <div class="form-group">
                        <span class="font-bold">Rules</span>
                        <hr>
                      </div>
                      <!-- End Rules Divider-->
                      <div class="form-group row">
                        <div class="col-md-4">
                            <div class="row">
                              <div class="col-md-10 col-sm-10 col-10">
                                <select class="form-control" id="active_rule" name="active_rule">
                                  <option value="">ADD NEW RULE</option>
                                  <option value="geo-ip-group" data-index="0">GeoIP</option>
                                  <option value="proxy-group" data-index="1">Bots & SPAM</option>
                                  <option value="referrer-group" data-index="2">Referrer</option>
                                  <option value="empty-referrer-group" data-index="3">Empty referrer</option>
                                  <option value="device-type-group" data-index="4">Device Type</option>
                                </select>
                              </div>
                              <div class="col-md-2 col-sm-2 col-2">
                                <button type="button" class="btn btn-icon btn-outline-light waves-effect waves-light" id="rule-box-toggle">
                                  <i class="feather icon-plus"></i>
                                </button>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="row geo-ip-group border-light p-1 rounded-lg position-relative @if(!isset($rule_data[0])) hidden @endif rule-group mb-1">
                        <div class="col-md-4">
                            <div class="row">
                              <div class="col-md-4">
                                <span class="mt-1-2 d-inline-block">Geo IP:</span>
                              </div>
                              <div class="col-md-8">
                                <select class="form-control" id="geo-ip" name="geo-ip">
                                    <option value="1" selected>Accept only from</option>
                                    <option value="0">Reject from</option>
                                </select>
                              </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="geo-ip-group">REMOVE</button>
                        <div class="offset-md-2 col-md-8 mt-2">
                          <div class="row">
                            <div class="col-md-3">
                              <span class="mt-1-2 d-inline-block">Country: </span>
                            </div>
                            <div class="col-md-9">
                              <div class="form-group">
                                <select class="select2 form-control" multiple="multiple" id="country-list" name="country-list">
                                  @foreach ($countries as $key => $country)
                                      <option value="{{$key}}">{{$country}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-md-3">
                              <span class="mt-1-2 d-inline-block">Country Group: </span>
                            </div>
                            <div class="col-md-9">
                              <select class="form-control" id="country-group" name="country-group">
                                <option value="" selected>Select Country Group.</option>
                                @foreach ($country_group as $key => $item)
                                  <option value="{{$item['list']}}">{{ $item['group_name']}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row border-light p-1 rounded-lg mb-1 mt-1 proxy-group position-relative @if(!isset($rule_data[1])) hidden @endif rule-group ">
                        <div class="col-md-2">
                          <span class="mt-1-2 d-inline-block">Bots & SPAM: </span>
                        </div>
                        <div class="col-md-8">
                          <select class="form-control" id="proxy-action" name="proxy-action">
                            <option value="1" selected>Accept visitors only if bot or SPAM IP is detected</option>
                            <option value="0">Reject visitor if bot or SPAM IP is detected</option>
                          </select>
                        </div>
                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="proxy-group">REMOVE</button>
                      </div>
                      <div class="form-group row referrer-group border-light mb-1 mt-1 p-1 rounded-lg position-relative @if(!isset($rule_data[2])) hidden @endif rule-group">
                          <div class="col-md-5">
                            <div class="row">
                              <div class="col-md-3">
                                <span class="d-inline-block mt-1-2">Referrer:</span>
                              </div>
                              <div class="col-md-8">
                                <select class="form-control" id="referrer-action" name="referrer-actioon">
                                  <option value="1" selected>Accept only</option>
                                  <option value="0">Reject</option>
                                </select>
                              </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="referrer-group">REMOVE</button>
                        <div class="offset-md-1 col-md-10 mt-2">
                          <div class="form-group row">
                            <div class="col-md-2">
                              <span class="d-inline-block mt-1-2">Referrer: </span>
                            </div>
                            <div class="col-md-3 col-sm-6">
                              <select class="form-control" id="domain-type" name="domain-type">
                                <option value="0" selected>Full referrer</option>
                                <option value="1">Domain only</option>
                              </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                              <select class="form-control" id="domain-reg" name="domain-reg">
                                <option value="0" selected>Equals</option>
                                <option value="1">Does not equal</option>
                              </select>
                            </div>
                            <div class="col-md-4 col-sm-12 mt-sm-1 mt-md-0 mt-xs-1">
                              <input type="text" class="form-control" id="domain-name" name="domain-name">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row border-light p-1 rounded-lg  mb-1 mt-1 empty-referrer-group position-relative @if(!isset($rule_data[3])) hidden @endif rule-group">
                        <div class="col-md-2">
                          <span class="mt-1-2 d-inline-block">Empty referrer: </span>
                        </div>
                        <div class="col-md-8">
                          <select class="form-control" id="empty-referrer-action" name="empty-referrer-action">
                            <option value="1" selected>Accept visitor only with empty referrer</option>
                            <option value="0">Reject visitor with empty referrer</option>
                          </select>
                        </div>
                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="empty-referrer-group">REMOVE</button>
                      </div>
                      <div class="form-group row device-type-group border-light mb-1 mt-1 p-1 rounded-lg position-relative @if(!isset($rule_data[4])) hidden @endif rule-group">
                        <div class="col-md-8">
                          <div class="row">
                            <div class="col-md-3">
                              <span class="d-inline-block mt-1-2">Device Type:</span>
                            </div>
                            <div class="col-md-5">
                              <select class="form-control" id="device-action-list" name="device-action-list">
                                <option value="1" selected>Accept only from</option>
                                <option value="0">Reject from</option>
                              </select>
                            </div>
                            <div class="col-md-4">
                              <select class="form-control" id="device-type-list" name="device-type-list">
                                <option value="0" selected>Desktop</option>
                                <option value="1">Mobile</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="device-type-group">REMOVE</button>
                      </div>
                    </div>
                  </form>
                </fieldset>

                <!-- Step 3 -->
                <h6>Select Keywords</h6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <div class="col-md-2">
                          <span>Rotation Options: </span>
                        </div>
                        <div class="col-md-10">
                          <ul class="list-unstyled mb-0 row">
                            <li class="d-inline-block col-md-3 col-sm-6">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="rotate_option" id="rotate_option1" value="0" checked>
                                  <label class="custom-control-label" for="rotate_option1">Random</label>
                                </div>
                              </fieldset>
                            </li>
                            <li class="d-inline-block col-md-3 col-sm-6">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="rotate_option" id="rotate_option2" value="1">
                                  <label class="custom-control-label" for="rotate_option2">Weighted Rotation</label>
                                </div>
                              </fieldset>
                            </li>
                            <li class="d-inline-block col-md-3 col-sm-6">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="rotate_option" id="rotate_option3" value="2">
                                  <label class="custom-control-label" for="rotate_option3">Position</label>
                                </div>
                              </fieldset>
                            </li>
                            <li class="d-inline-block col-md-3 col-sm-6">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="rotate_option" id="rotate_option4" value="3">
                                  <label class="custom-control-label" for="rotate_option4">Fixed Hits</label>
                                </div>
                              </fieldset>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <form class="new-url-group">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <label>Key word</label>
                                <input type="text" class="form-control" id="keyword" name="keyword">
                              </div>
                              <div class="col-md-4 mt-md-0 weight-max-hit mt-1">
                                <label class="weight-label hidden">Weight</label>
                                <label class="max_hit-label hidden">Max Hits</label>
                                <label></label>
                                <input type="number" class="form-control" id="weight-or-max_hit" name="weight-or-max_hit">
                              </div>
                              <div class="col-md-2 text-center mt-md-0 mt-1">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light xx-small mt-md-2" id="new-url-add-btn">ADD</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row mt-md-n1">
                              <div class="col-md-12 text-md-right text-center ">
                                <span>Bulk Upload (Max 1,000Kwds)</span>
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light xx-small" id="upload-btn">UPLOAD</button>
                                <input type="file" name="csv-file" accept=".csv" class="d-none" id="csv-file"/>
                              </div>
                              <div class="col-md-12 text-md-right text-center">
                                <a href="{{asset('csv_file/sample.csv')}}" download>Download Sample File</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                      <div class="form-group">
                        <span class="font-bold">Target Keywords<i class="feather icon-info"></i></span>
                        <hr>
                      </div>
                      <div class="row mb-2 justify-content-between">
                        <div class="col-md-2 d-md-block d-none">Keyword</div>
                        <div class="col-md-2 d-md-block d-none weight-hit-text">
                          <span class="weight-hit hidden weight-text">Weight</span>
                          <span class="weight-hit hidden max-hit-text">Max Hits</span>
                        </div>
                        <div class="col-md-6 d-md-block d-none"><span>Destination URL</span></div>
                        <div class="col-md-2 d-md-block d-none text-right">Action</div>
                      </div>
                      <ul class="target-keywords-group mb-4 list-group" id="target-keywords-group">
                        @foreach ($url_list as $key => $item)
                          <div class="form-group row target-item-group d-flex" data-index="{{$key}}">
                            <div class="col-md-2 col-6 d-table">
                              <input type="text" class="keyword d-table-cell align-middle form-control" value="{{$item->keyword}}">
                            </div>
                            <div class="col-md-2 col-6 d-table">
                              <div class="form-group d-table-cell align-middle">
                                <input type="number" class="form-control weight-or-max_hit" value="{{ $rotation == '1' ? $item->weight : ($rotation == '3' ? $item->max_hit_day : '')}}">
                              </div>
                            </div>
                            <div class="col-md-4 col-12 d-table">
                              <p class="preview-link text-break-all d-table-cell align-middle">{{$item->dest_url}}</p>
                            </div>
                            <div class="col-md-2">
                              <div class="row">
                                <div class="col-md-4 col-sm-6 col-6">
                                  <div class="custom-control custom-switch custom-switch-success mr-2">
                                    <input type="checkbox" class="custom-control-input custom-control-input spoof-switch" id="spoof-switch{{$key}}" @if(isset($item->spoof_referrer) && $item->spoof_referrer) checked @endif>
                                    <label class="custom-control-label" for="spoof-switch{{$key}}"></label>
                                  </div>
                                </div>
                                <div class="col-md-8 col-sm-6 col-6">
                                  <select class="form-control form-control add-spoof-select @if(!isset($item->spoof_referrer) || isset($item->spoof_referrer) && !$item->spoof_referrer)hidden @endif">
                                    <option value="0">Google</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2 col-3 justify-content-end d-flex">
                              <a class="handle fa fa-arrows fa-2x mr-1"></a>
                              <a href="{{$item->dest_url}}" target="_blank" class="fa fa-external-link fa-2x mt-2px">
                              </a>
                              <a href="#" class="target-item-remove fa fa-trash fa-2x"></a>
                            </div>
                          </div>
                        @endforeach
                      </ul>
                      <p class="realtime-weight w-25 d-flex justify-content-between">Total Weight: <span class="weight-value"></span></p>
                      <p class="realtime-weight w-25 d-flex justify-content-between">Total weight must be 100%.</p>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/dragula.min.js')) }}"></script>

@endsection

@section('page-script')
  @php
    $country_list = []; $geo_ip_action = 1; $country_group = 0;
    $proxy_action = 1; $tracking_url = isset($url_data->tracking_url) ? $url_data->tracking_url : 0;
    $pixel = isset($url_data->pixel) ? $url_data->pixel : 0;
    $campaign = isset($url_data->campaign) ? $url_data->campaign : 0;
    $referrer_action = 1; $domain_type = 0; $domain_reg = 0; $domain_name = '';
    $empty_referrer_action = 1;
    $device_action = 1; $device_type = 0;
    $blank = 'false'; $spoof = 'false'; $spoof_service = 0; $deep = 'false';
    if (isset($rule_data[0])) {
      $country_list = explode(',',$rule_data[0]->country_list);
      $geo_ip_action = $rule_data[0]->action;
      $country_group = $rule_data[0]->country_group;
    }
    if (isset($rule_data[1])) {
      $proxy_action = $rule_data[1]->action;
    }
    if (isset($rule_data[2])) {
      $referrer_action = $rule_data[2]->action;
      $domain_type = $rule_data[2]->domain_type;
      $domain_reg = $rule_data[2]->domain_reg;
      $domain_name = $rule_data[2]->domain_name;
    }
    if (isset($rule_data[3])) {
      $empty_referrer_action = $rule_data[3]->action;
    }
    if (isset($rule_data[4])) {
      $device_action = $rule_data[4]->action;
      $device_type = $rule_data[4]->device;
    }
    if (count($advance_options)) {
      $blank = $advance_options['blank'] ? 'true' : 'false';
      $deep = $advance_options['deep'] ? 'true' : 'false';
    }
    $rule_key = [];
    foreach ($rule_data as $key => $value) {
      if (isset($value)) array_push($rule_key, $key);
    }
  @endphp
<script>
  const createURL = "{{route('redirects.create-new-keyword-rotator')}}";
  let active_rule = @php
    echo json_encode($rule_key);
  @endphp;
</script>
<script src="{{asset(mix('js/scripts/keyword-rotator.js'))}}"></script>

<script>
  $(function(){
      dragula([document.getElementById('target-keywords-group')],{
        moves: function (el, container, handle) {
          console.log(handle.classList.contains('handle'));
          return handle.classList.contains('handle');
        }
      });

    $('body').on('click','.target-item-remove',function(){
      const index = $('.target-item-remove').index($(this));
      $('.target-item-group').eq(index).remove();
    })
  })
</script>

<script>
  const country_list = @php
  function get_tag($value) {
      return $value;
  }
  echo json_encode(array_map("get_tag", $country_list));
  @endphp;

  $(function(){

    $('#tracking_url').val({{$tracking_url}});
    $('#pixel').val({{$pixel}});
    $('#campaign').val({{$campaign}});
    $('#geo-ip').val({{$geo_ip_action}});
    $("#country-list").val(country_list).change();
    $('#country-group').val({{ $country_group }});
    $('#proxy-action').val({{$proxy_action}});
    $('#referrer-action').val({{$referrer_action}});
    $('#domain-type').val({{$domain_type}});
    $('#domain-reg').val({{$domain_reg}});
    $('#domain-name').val('{{$domain_name}}');
    $('#empty-referrer-action').val({{$empty_referrer_action}});
    $('#device-action-list').val({{$device_action}});
    $('#device-type-list').val({{$device_type}});
    $('#blank-refer-switch').attr('checked',{{$blank}});
    $('#deep-link-switch').attr('checked', {{$deep}});
    $('input[name="rotate_option"]').eq({{$rotation}}).attr('checked',true).change();
  })
</script>
@endsection
