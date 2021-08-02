@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.CreateNewRedirect'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

@endsection

@section('page-style')
  <style>
    .xx-small {
      font-size: xx-small;
    }
    .space-evenly {
      justify-content: space-evenly;
    }
    .f-12px {
      font-size: 12px;
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
  </style>
@endsection

@section('content')
	<div class="row">
    <div class="col-md-12">
      <span>Edit Redirect Link.</span>
      <button type="button" class="btn btn-outline-dark round mr-1 mb-1 pull-right f-10">You are creating a Custom URL redirect</button>

    </div>
    <div class="col-md-12">
      <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                              <span>Link Name: </span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" id="link_name" class="form-control" name="linkname">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <span>Marketplace: </span>
                                          </div>
                                          <div class="col-md-10">
                                            <fieldset class="form-group">
                                              <select class="form-control" id="marketplace">
                                                  <option>United States</option>
                                              </select>
                                            </fieldset>
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <span>ASIN 1: </span>
                                          </div>
                                          <div class="col-md-6">
                                              <input type="text" id="asin-1-input" class="form-control" name="asin-1-input">
                                          </div>
                                          <div class="col-md-1">
                                            <span class="f-12px d-inline-block mt-1-2">QUANTITY: </span>
                                          </div>
                                          <div class="col-md-2">
                                              <input type="text" id="quantity-1-input" class="form-control" name="quantity-1-input">
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <span>ASIN 2: </span>
                                          </div>
                                          <div class="col-md-6">
                                              <input type="text" id="asin-1-input" class="form-control" name="asin-2-input">
                                          </div>
                                          <div class="col-md-1">
                                            <span class="f-12px d-inline-block mt-1-2">QUANTITY: </span>
                                          </div>
                                          <div class="col-md-2">
                                              <input type="text" id="quantity-2-input" class="form-control" name="quantity-2-input">
                                          </div>
                                          <div class="col-md-1">
                                            <a class="d-inline-block mt-1-2">
                                              <i class="feather icon-plus-circle fa-2x"></i>
                                            </a>
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <span>Tracking URL: </span>
                                          </div>
                                          <div class="col-md-10">
                                            <fieldset class="form-group">
                                              <select class="form-control" id="trackingurl">
                                                  <option>IT</option>
                                                  <option>Blade Runner</option>
                                                  <option>Thor Ragnarok</option>
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
                                              <select class="form-control" id="pixel">
                                                  <option>IT</option>
                                                  <option>Blade Runner</option>
                                                  <option>Thor Ragnarok</option>
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
                                            <span>Amazon Aff ID: </span>
                                          </div>
                                          <div class="col-md-10">
                                              <input type="text" id="dest_url" class="form-control" name="amazon-aff-id">
                                          </div>
                                        </div>
                                        <div class="form-group row">
                                          <div class="col-md-2">
                                            <span>Add to Campaign: </span>
                                          </div>
                                          <div class="col-md-10">
                                            <fieldset class="form-group">
                                              <select class="form-control" id="campaign">
                                                  <option>Campaign 1</option>
                                                  <option>Blade Runner</option>
                                                  <option>Thor Ragnarok</option>
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
                                                  <input type="checkbox" class="custom-control-input" id="blank-refer-switch">
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
                                                      <input type="checkbox" class="custom-control-input" id="spoof-refer-switch">
                                                      <label class="custom-control-label" for="spoof-refer-switch"></label>
                                                    </div>
                                                  </div>
                                                  <div class="col-md-8 col-sm-6 col-6">
                                                    <select class="form-control" id="spoof-select">
                                                      <option>Google</option>
                                                      <option>Twitter</option>
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
                                                  <input type="checkbox" class="custom-control-input" id="deep-link-switch">
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
                                                <input type='text' class="form-control pickadate" />

                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-4">
                                                <span>Fallback URL:</span>
                                              </div>
                                              <div class="col-md-8">
                                                <input type="text" id="fallback_url" class="form-control" name="fallback">
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
                                          <div class="col-md-3">
                                              <div class="row">
                                                <div class="col-md-2 col-sm-2 col-2">
                                                  <button type="button" class="btn btn-icon btn-outline-light waves-effect waves-light">
                                                    <i class="feather icon-plus"></i>
                                                  </button>
                                                </div>
                                                <div class="col-md-10 col-sm-10 col-10">
                                                  <select class="form-control" id="campaign">
                                                    <option>ADD NEW RULE</option>
                                                    <option>GeoIP</option>
                                                    <option>Proxy</option>
                                                    <option>Referrer</option>
                                                    <option>Empty refer</option>
                                                    <option>Device Type</option>
                                                </select>
                                                </div>
                                              </div>
                                          </div>
                                        </div>
                                        <div class="row geo-ip-group border-light p-1 rounded-lg position-relative">
                                          <div class="col-md-3">
                                              <div class="row">
                                                <div class="col-md-4">
                                                  <span class="mt-1-2 d-inline-block">Geo IP:</span>
                                                </div>
                                                <div class="col-md-8">
                                                  <select class="form-control" id="geo-ip">
                                                      <option>Accept only from</option>
                                                      <option>Reject from</option>
                                                  </select>
                                                </div>
                                              </div>
                                          </div>
                                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2" id="geo-ip-remove">REMOVE</button>
                                          <div class="offset-md-2 col-md-9 mt-2">
                                            <div class="form-group row">
                                              <div class="col-md-2">
                                                <span class="mt-1-2 d-inline-block">Country: </span>
                                              </div>
                                              <div class="col-md-10">
                                                <div class="form-group">
                                                  <select class="select2 form-control" multiple="multiple" id="country-list">
                                                    @foreach ($countries as $key => $country)
                                                        <option value="{{$key}}">{{$country}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-2">
                                                <span class="mt-1-2 d-inline-block">Country Group: </span>
                                              </div>
                                              <div class="col-md-10">
                                                <input type="text" class="form-control" id="country-group">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group row border-light p-1 rounded-lg mt-2 proxy-group position-relative">
                                          <div class="col-md-1">
                                            <span class="mt-1-2 d-inline-block">Proxy: </span>
                                          </div>
                                          <div class="col-md-10">
                                            <select class="form-control" id="proxy-action">
                                              <option value="accept">Accept visitor only if proxy is detected</option>
                                              <option value="reject">Reject visitor is proxy is detected</option>
                                            </select>
                                          </div>
                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2" id="proxy-remove">REMOVE</button>
                                        </div>
                                        <div class="form-group row referrer-group border-light p-1 rounded-lg position-relative">
                                            <div class="col-md-5">
                                              <div class="row">
                                                <div class="col-md-3">
                                                  <span class="d-inline-block mt-1-2">Referrer:</span>
                                                </div>
                                                <div class="col-md-8">
                                                  <select class="form-control" id="referrer-action">
                                                    <option>Accept only</option>
                                                    <option>Reject</option>
                                                  </select>
                                                </div>
                                              </div>
                                          </div>
                                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2" id="referrer-remove">REMOVE</button>
                                        <div class="offset-md-1 col-md-10 mt-2">
                                          <div class="form-group row">
                                            <div class="col-md-2">
                                              <span class="d-inline-block mt-1-2">Referrer: </span>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                              <select class="form-control" id="domain_type">
                                                <option>Full referrer</option>
                                                <option>Domain only</option>
                                              </select>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                              <select class="form-control" id="domain-reg">
                                                <option>Matcheds regex</option>
                                                <option>Does not match</option>
                                                <option>Equals</option>
                                                <option>Does not equal</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4 col-sm-12 mt-sm-1 mt-md-0 mt-xs-1">
                                              <input type="text" class="form-control" id="domain-name">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-8 offset-md-4">
                                        <button type="button" class="btn btn-primary mr-1 mb-1 pull-right">FINISH</button>
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
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

@endsection

@section('page-script')
  <script>
    $(function(){
      $('.pickadate').pickadate();

        $('.pickatime-format').pickatime({
          // Escape any “rule” characters with an exclamation mark (!).
          format: 'T!ime selected: h:i a',
          formatLabel: 'HH:i a',
          formatSubmit: 'HH:i',
          hiddenPrefix: 'prefix__',
          hiddenSuffix: '__suffix'
      });
      $(".select2").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        dropdownAutoWidth: true,
        width: '100%'
      });
    })
  </script>
@endsection
