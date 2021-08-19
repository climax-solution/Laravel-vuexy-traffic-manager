@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.CreateNewRedirects.custom_url'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/plugins/toastr.css')) }}">

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
    .error {
      color: #ea5455 !important;
    }
  </style>
@endsection

@section('content')
	<div class="row">
    <div class="col-md-12">

    </div>
    <input type="hidden" name="_id" value="{{$id}}"/>
    <div class="col-md-12">
      <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" id="custom-url-create-form">
                            <div class="form-body">
                                <div class="row">
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
                                              <input type="text" id="dest_url" class="form-control" name="dest_url" value="{{ isset($url_data->dest_url) ? $url_data->dest_url : '' }}">
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
                                      <div class="form-group row">
                                        <div class="col-md-2">
                                          <span>Add to Campaign: </span>
                                        </div>
                                        <div class="col-md-10">
                                          <fieldset class="form-group">
                                            <select class="form-control" id="campaign" name="campaign">
                                              @foreach ($campaign as $key => $item)
                                                <option value="{{$key}}" @if(!$key) {{'selected'}} @endif>{{ $item }}</option>
                                              @endforeach
                                            </select>
                                          </fieldset>
                                        </div>
                                      </div>
                                      <!-- Begin Advanced Options Divider-->
                                      <div class="form-group">
                                        <span class="font-bold">Advanced Options</span>
                                        <hr>
                                      </div>
                                      <!-- End Advanced Options Divider-->
                                      <div class="form-group row">
                                        <div class="col-md-5">
                                          <div class="form-group row">
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
                                          <div class="form-group row">
                                            <div class="col-md-5">
                                              <span>Spoof Referrer: </span>
                                            </div>
                                            <div class="col-md-7">
                                              <div class="row">
                                                <div class="col-md-4 col-sm-6 col-6">
                                                  <div class="custom-control custom-switch custom-switch-success mr-2 mb-1">
                                                    <input type="checkbox" class="custom-control-input" id="spoof-refer-switch" name="spoof-refer-switch">
                                                    <label class="custom-control-label" for="spoof-refer-switch"></label>
                                                  </div>
                                                </div>
                                                <div class="col-md-8 col-sm-6 col-6">
                                                  <select class="form-control hidden" id="spoof-select" name="spoof-select">
                                                    <option value="0" selected>Google</option>
                                                    <!-- <option value="1">Twitter</option> -->
                                                  </select>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <div class="col-md-5">
                                              <span>Deep Link: </span>
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
                                              <input type='number' class="form-control" id="max_hit_day" name="max_hit_day" value="{{isset($url_data->max_hit_day) ? $url_data->max_hit_day : ''}}"/>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <div class="col-md-4">
                                              <span>Fallback URL:</span>
                                            </div>
                                            <div class="col-md-8">
                                              <input type="text" id="fallback_url" class="form-control" name="fallback_url" value="{{isset($url_data->fallback_url) ? $url_data->fallback_url : ''}}">
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
                                                  <option value="proxy-group" data-index="1">Proxy</option>
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
                                      <div class="row geo-ip-group border-light p-1 rounded-lg position-relative @if(!isset($rule_data[0])) hidden @endif  rule-group">
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
                                      <div class="form-group row border-light p-1 rounded-lg mt-2 proxy-group position-relative @if(!isset($rule_data[1])) hidden @endif rule-group">
                                        <div class="col-md-1">
                                          <span class="mt-1-2 d-inline-block">Proxy: </span>
                                        </div>
                                        <div class="col-md-8">
                                          <select class="form-control" id="proxy-action">
                                              <option value="1" selected>Accept visitor only if proxy is detected</option>
                                              <option value="0">Reject visitor is proxy is detected</option>
                                          </select>
                                        </div>
                                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="proxy-group">REMOVE</button>
                                      </div>
                                      <div class="form-group row referrer-group border-light p-1 rounded-lg position-relative @if(!isset($rule_data[2])) hidden @endif rule-group">
                                          <div class="col-md-5">
                                            <div class="row">
                                              <div class="col-md-3">
                                                <span class="d-inline-block mt-1-2">Referrer:</span>
                                              </div>
                                              <div class="col-md-8">
                                                <select class="form-control" id="referrer-action">
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
                                              <select class="form-control" id="domain-type">
                                                <option value="0" selected>Full referrer</option>
                                                <option value="1">Domain only</option>
                                              </select>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                              <select class="form-control" id="domain-reg">
                                                <option value="1">Equals</option>
                                                <option value="0" selected>Does not equal</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mt-sm-1 mt-md-0 mt-xs-1">
                                              <input type="text" class="form-control" id="domain-name" name="domain-name">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group row border-light p-1 rounded-lg mt-2 empty-referrer-group position-relative @if(!isset($rule_data[3])) hidden @endif rule-group">
                                        <div class="col-md-2">
                                          <span class="mt-1-2 d-inline-block">Empty referrer: </span>
                                        </div>
                                        <div class="col-md-8">
                                          <select class="form-control" id="empty-referrer-action">
                                            <option value="1" selected>Accept visitor only with empty referrer</option>
                                            <option value="0">Reject visitor with empty referrer</option>
                                          </select>
                                        </div>
                                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="empty-referrer-group">REMOVE</button>
                                      </div>
                                      <div class="form-group row device-type-group border-light p-1 rounded-lg position-relative @if(!isset($rule_data[4])) hidden @endif rule-group mt-2">
                                        <div class="col-md-8">
                                          <div class="row">
                                            <div class="col-md-3">
                                              <span class="d-inline-block mt-1-2">Device Type:</span>
                                            </div>
                                            <div class="col-md-5">
                                              <select class="form-control" id="device-action-list">
                                                <option value="1" selected>Accept only from</option>
                                                <option value="0">Reject from</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4">
                                              <select class="form-control" id="device-type-list">
                                                <option value="0" selected>Desktop</option>
                                                <option value="1">Mobile</option>
                                              </select>
                                            </div>
                                          </div>
                                      </div>
                                      <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="device-type-group">REMOVE</button>
                                    </div>
                                    </div>
                                    <div class="col-md-8 offset-md-4">
                                        <button type="button" class="btn btn-primary mr-1 mt-1 mb-1 pull-right" id="done-btn">DONE</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>

	</div>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

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
      // dd($rule_data[4]);
      $device_type = $rule_data[4]->device;
    }
    if (count($advance_options)) {
      $blank = $advance_options['blank'] ? 'true' : 'false';
      $spoof = $advance_options['spoof'] ? 'true' : 'false';
      $deep = $advance_options['deep'] ? 'true' : 'false';
      $spoof_service = $advance_options['spoof_service'];
    }
    $rule_key = [];
    foreach ($rule_data as $key => $value) {
      if (isset($value)) array_push($rule_key, $key);
    }
  @endphp
  <script>
    const createURL = "{{route('redirects.create-new-custom-url')}}";
    let active_rule = @php
      echo json_encode($rule_key);
    @endphp;
  </script>
  <script src="{{ asset(mix('js/scripts/custom-url.js')) }}"></script>

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
      $('#spoof-refer-switch').attr('checked', {{$spoof}}).change();
      $('#spoof-select').val({{!$spoof_service ? 0 : $spoof_service}});
    })
  </script>
@endsection
