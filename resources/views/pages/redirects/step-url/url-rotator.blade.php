@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.CreateNewRedirect'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/plugins/forms/wizard.css')) }}">
  <style>
    ul {
      list-style-type: none;
    }
  </style>
@endsection

@section('page-style')
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
  </style>
@endsection

@section('content')
	<div class="row">
    <div class="col-md-12">
      <span>Edit Redirect Link.</span>
      <button type="button" class="btn btn-outline-dark round mr-1 mb-1 pull-right f-10">You are creating a Custom URL redirect</button>

    </div>

	</div>
  <section id="number-tabs">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <form action="#" class="number-tab-steps wizard-circle">

                <!-- Step 1 -->
                <h6>Redirect settings</h6>
                <fieldset>

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
                            <span>Destination URL: </span>
                          </div>
                          <div class="col-md-10">
                              <input type="text" id="dest_url" class="form-control" name="desturl">
                          </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-2">
                          <span>Tracking URL: </span>
                        </div>
                        <div class="col-md-10">
                          <fieldset class="form-group">
                            <select class="form-control" id="tracking_url">
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
                            <select class="form-control" id="pixel">
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
                            <select class="form-control" id="campaign">
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
                                <input type="checkbox" class="custom-control-input" id="blank-refer-switch">
                                <label class="custom-control-label" for="blank-refer-switch"></label>
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
                              <input type='number' class="form-control" id="max_hit_day"/>
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
                                <button type="button" class="btn btn-icon btn-outline-light waves-effect waves-light" id="rule-box-toggle">
                                  <i class="feather icon-plus"></i>
                                </button>
                              </div>
                              <div class="col-md-10 col-sm-10 col-10">
                                <select class="form-control" id="active_rule">
                                  <option value="">ADD NEW RULE</option>
                                  <option value="geo-ip-group" data-index="0">GeoIP</option>
                                  <option value="proxy-group" data-index="1">Proxy</option>
                                  <option value="referrer-group" data-index="2">Referrer</option>
                                  <option value="empty-referrer-group" data-index="3">Empty referrer</option>
                                  <option value="device-type-group" data-index="4">Device Type</option>
                              </select>
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
                                <select class="form-control" id="geo-ip">
                                    <option value="0" selected>Accept only from</option>
                                    <option value="1">Reject from</option>
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
                                <select class="select2 form-control" multiple="multiple" id="country-list">
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
                              <select class="form-control" id="country-group">
                                @foreach ($country_group as $key => $item)
                                  <option value="{{$key}}" @if(!$key) {{'selected'}} @endif>{{ $item }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row border-light p-1 rounded-lg mt-2 proxy-group position-relative hidden rule-group">
                        <div class="col-md-1">
                          <span class="mt-1-2 d-inline-block">Proxy: </span>
                        </div>
                        <div class="col-md-8">
                          <select class="form-control" id="proxy-action">
                            <option value="0" selected>Accept visitor only if proxy is detected</option>
                            <option value="1">Reject visitor is proxy is detected</option>
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
                                  <option value="0" selected>Accept only</option>
                                  <option value="1">Reject</option>
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
                                <option value="0" selected>Matcheds regex</option>
                                <option value="1">Does not match</option>
                                <option value="2">Equals</option>
                                <option value="3">Does not equal</option>
                              </select>
                            </div>
                            <div class="col-md-4 col-sm-12 mt-sm-1 mt-md-0 mt-xs-1">
                              <input type="text" class="form-control" id="domain-name">
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
                            <option value="0" selected>Accept visitor only with empty referrer</option>
                            <option value="1">Reject visitor with empty referrer</option>
                          </select>
                        </div>
                          <button type="button" class="btn btn-danger btn-sm waves-effect waves-light xx-small position-absolute right-2-p top-1-2 remove-btn" data-group="empty-referrer-group">REMOVE</button>
                      </div>
                      <div class="form-group row device-type-group border-light p-1 rounded-lg position-relative hidden rule-group">
                        <div class="col-md-8">
                          <div class="row">
                            <div class="col-md-3">
                              <span class="d-inline-block mt-1-2">Device Type:</span>
                            </div>
                            <div class="col-md-5">
                              <select class="form-control" id="device-action-list">
                                <option value="0" selected>Accept only from</option>
                                <option value="1">Reject from</option>
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
                </div>
                </fieldset>

                <!-- Step 3 -->
                <h6>Manage URLs</h6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                        <div class="col-md-2">
                          <span>Rotation Options: </span>
                        </div>
                        <div class="col-md-10">
                          <ul class="list-unstyled mb-0">
                            <li class="d-inline-block mr-2">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="customRadio" id="customRadio1" checked>
                                  <label class="custom-control-label" for="customRadio1">Radnom</label>
                                </div>
                              </fieldset>
                            </li>
                            <li class="d-inline-block mr-2">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="customRadio" id="customRadio2">
                                  <label class="custom-control-label" for="customRadio2">Weighted Rotation</label>
                                </div>
                              </fieldset>
                            </li>
                            <li class="d-inline-block mr-2">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="customRadio" id="customRadio3">
                                  <label class="custom-control-label" for="customRadio3">Position</label>
                                </div>
                              </fieldset>
                            </li>
                            <li class="d-inline-block mr-2">
                              <fieldset>
                                <div class="custom-control custom-radio">
                                  <input type="radio" class="custom-control-input" name="customRadio" id="customRadio4">
                                  <label class="custom-control-label" for="customRadio4">Fixed Hits</label>
                                </div>
                              </fieldset>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="destination-url-group">
                        <p>Destination URL</p>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <div class="col-md-8">
                                <select class="form-control-sm" id="dest_url">
                                  <option>Select from list of redirect links</option>
                                  <option>Enter New</option>
                                </select>
                              </div>
                              <div class="col-md-4">
                                <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light xx-small" id="add-btn">ADD</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <div class="col-md-12 text-right">
                                <span>Bulk Upload (Max 1,000Kwds)</span>
                                <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light xx-small" id="add-btn">UPLOAD</button>
                              </div>
                              <div class="col-md-12 text-right">
                                <a href="#">Download Sample File</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="target-urls-group">
                        <div class="form-group row">
                          <div class="col-md-4">
                            <ol>
                              <li class="pl-1">
                                <a href="#">https://domain.com/keyword1</a>
                              </li>
                              <li class="mt-1 pl-1">
                                <input type="text" class="form-control form-control-sm" id="target-url">
                              </li>
                              <li class="mt-1 pl-1">
                                <a href="#">https://domain.com/keyword1</a>
                              </li>
                            </ol>
                          </div>
                          <div class="col-md-2">
                            <span>Weight/Max Hits</span>
                            <div class="form-group">
                              <input type="text" class="form-control form-control-sm" id="target-url">
                              <input type="text" class="form-control form-control-sm mt-1" id="target-url">
                              <input type="text" class="form-control form-control-sm mt-1" id="target-url">
                            </div>
                          </div>
                          <div class="col-md-2">
                            <span>Spoof Referrer</span>
                            <div class="form-group row">
                              <div class="col-md-12">
                                <div class="custom-control custom-switch custom-switch-success mr-2">
                                  <input type="checkbox" class="custom-control-input custom-control-input-sm" id="spoof-switch-1">
                                  <label class="custom-control-label" for="spoof-switch-1"></label>
                                </div>
                              </div>
                            </div>

                              <div class="form-group row">
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4 col-sm-6 col-6">
                                      <div class="custom-control custom-switch custom-switch-success mr-2">
                                        <input type="checkbox" class="custom-control-input custom-control-input-sm" id="spoof-switch-2">
                                        <label class="custom-control-label" for="spoof-switch-2"></label>
                                      </div>
                                    </div>
                                    <div class="col-md-8 col-sm-6 col-6">
                                      <select class="form-control form-control-sm" id="spoof-select">
                                        <option>Google</option>
                                        <option>Twitter</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <div class="col-md-12">
                                  <div class="custom-control custom-switch custom-switch-success mr-2">
                                    <input type="checkbox" class="custom-control-input" id="spoof-switch-3">
                                    <label class="custom-control-label" for="spoof-switch-3"></label>
                                  </div>
                                </div>
                              </div>
                          </div>
                          <div class="col-md-2">
                            <span>Deep Link</span>
                            <div class="form-group row">
                              <div class="col-md-12">
                                <div class="custom-control custom-switch custom-switch-success mr-2">
                                  <input type="checkbox" class="custom-control-input custom-control-input-sm" id="deep-switch-1">
                                  <label class="custom-control-label" for="deep-switch-1"></label>
                                </div>
                              </div>
                            </div>
                              <div class="form-group row">
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4 col-sm-6 col-6">
                                      <div class="custom-control custom-switch custom-switch-success mr-2">
                                        <input type="checkbox" class="custom-control-input custom-control-input-sm" id="deep-switch-2">
                                        <label class="custom-control-label" for="deep-switch-2"></label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <div class="col-md-12">
                                  <div class="custom-control custom-switch custom-switch-success mr-2">
                                    <input type="checkbox" class="custom-control-input" id="deep-switch-3">
                                    <label class="custom-control-label" for="deep-switch-3"></label>
                                  </div>
                                </div>
                              </div>
                          </div>
                          <div class="col-md-2">
                            <ul class="text-right">
                              <li class="">
                                <a href="#"><i class="fa fa-external-link fa-2x mr-1"></i></a>
                                <a href="#"><i class="fa fa-trash fa-2x"></i></a>
                              </li>
                              <li class="mt-1">
                                <a href="#"><i class="fa fa-trash fa-2x"></i></a>
                              </li>
                              <li class="mt-1">
                                <a href="#"><i class="fa fa-external-link fa-2x mr-1"></i></a>
                                <a href="#"><i class="fa fa-trash fa-2x"></i></a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>

@endsection

@section('page-script')
<script>
  const createURL = "{{route('redirects.create-new-custom-url')}}";
</script>
<script src="{{ asset(mix('js/scripts/url-rotator.js')) }}"></script>
  <script>
    $(function(){

    })
  </script>
@endsection
