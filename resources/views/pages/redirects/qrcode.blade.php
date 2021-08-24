@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.CreateNewRedirects.qr_code'))

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
                        <form class="form form-horizontal" id="qr-code-create-form">
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
                                      <!--
                                      <div class="form-group">
                                        <span class="font-bold">Advanced Options</span>
                                        <hr>
                                      </div>
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
                                              <span>Spoof Referrer<i class="feather icon-help-circle spoof-referrer-help"></i> </span>
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
                                                    <option value="1">Twitter</option>
                                                  </select>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="form-group row">
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
                                              <input type='number' class="form-control" id="max_hit_day" name="max_hit_day"/>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <div class="col-md-4">
                                              <span>Fallback URL:</span>
                                            </div>
                                            <div class="col-md-8">
                                              <input type="text" id="fallback_url" class="form-control" name="fallback_url">
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <span class="font-bold">Rules</span>
                                        <hr>
                                      </div>

                                      <div class="form-group row">
                                        <div class="col-md-3">
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
                                      <div class="row geo-ip-group border-light p-1 rounded-lg position-relative hidden rule-group">
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
                                                @foreach ($country_group as $key => $item)
                                                  <option value="{{$item['list']}}" @if(!$key) {{'selected'}} @endif>{{ $item['group_name']}}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group row border-light p-1 rounded-lg mt-2 proxy-group position-relative hidden rule-group">
                                        <div class="col-md-2">
                                          <span class="mt-1-2 d-inline-block">Bots & SPAM: </span>
                                        </div>
                                        <div class="col-md-8">
                                          <select class="form-control" id="proxy-action">
                                            <option value="1" selected>Accept visitors only if bot or SPAM IP is detected</option>
                                            <option value="0">Reject visitor if bot or SPAM IP is detected</option>
                                          </select>
                                        </div>
                                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="proxy-group">REMOVE</button>
                                      </div>
                                      <div class="form-group row referrer-group border-light p-1 rounded-lg position-relative hidden rule-group">
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
                                      <div class="form-group row border-light p-1 rounded-lg mt-2 empty-referrer-group position-relative hidden rule-group">
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
                                      <div class="form-group row device-type-group border-light p-1 rounded-lg position-relative hidden rule-group mt-2">
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
                                  -->

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
    $tracking_url = isset($url_data->tracking_url) ? $url_data->tracking_url : 0;
    $pixel = isset($url_data->pixel) ? $url_data->pixel : 0;
    $campaign = isset($url_data->campaign) ? $url_data->campaign : 0;
  @endphp
  <script>
    const createURL = "{{route('redirects.create-new-qr-code')}}";
  </script>
  <script src="{{ asset(mix('js/scripts/qr-code.js')) }}"></script>
  <script>
    $(function(){
      $('#tracking_url').val({{$tracking_url}});
      $('#pixel').val({{$pixel}});
      $('#campaign').val({{$campaign}});
    })
  </script>
@endsection
