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
                                <input type="text" id="link-name" class="form-control" name="linkname">
                            </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-2">
                            <span>Destination URL: </span>
                          </div>
                          <div class="col-md-10">
                              <input type="text" id="dest-url" class="form-control" name="desturl">
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
                      <!-- Begin Advanced Options Divider-->
                      <div class="form-group">
                        <span class="font-bold">Advanced Options</span>
                        <hr>
                      </div>
                      <!-- End Advanced Options Divider-->
                      <div class="form-group row">
                        <div class="col-md-4">
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
                        <div class="col-md-8">
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
                              <input type="text" id="fallback-url" class="form-control" name="fallback">
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
                          <div class="row">
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
                          <select class="form-control" id="proxy-list">
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
                                <select class="form-control" id="referrer-list">
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
                            <select class="form-control" id="referrer-1">
                              <option>Full referrer</option>
                              <option>Domain only</option>
                            </select>
                          </div>
                          <div class="col-md-3 col-sm-6">
                            <select class="form-control" id="referrer-2">
                              <option>Matcheds regex</option>
                              <option>Does not match</option>
                              <option>Equals</option>
                              <option>Does not equal</option>
                            </select>
                          </div>
                          <div class="col-md-4 col-sm-12 mt-sm-1 mt-md-0 mt-xs-1">
                            <input type="text" class="form-control" id="referrer-input">
                          </div>
                        </div>
                      </div>
                      </div>
                    </div>
                </div>
                </fieldset>

                <!-- Step 3 -->
                <h6>Select Keywords</h6>
                <fieldset>
                  <div class="row">
                    <div class="col-md-12">
                      <p>
                        The keywords entered in this step will replace the {keyword} variable that you have entered in the destination URL in Step 1.
                        You can add up to 1000keywords. However, for ranking campaigns we recommend to focus on few keywords only unless you are planning to giveaway a large number of units.
                      </p>
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
                        <div class="row">
                          <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6">
                                <label>Key word</label>
                                <input type="text" class="form-control" id="keyword">
                              </div>
                              <div class="col-md-4">
                                <label>Weight/Max Hits</label>
                                <input type="text" class="form-control" id="weight-max-hit">
                              </div>
                              <div class="col-md-2">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light xx-small mt-2" id="add-btn">ADD</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row mt-1">
                              <div class="col-md-12 text-right mt-1">
                                <span>Bulk Upload (Max 1,000Kwds)</span>
                                <button type="button" class="btn btn-outline-primary  waves-effect waves-light xx-small" id="add-btn">UPLOAD</button>
                              </div>
                              <div class="col-md-12 text-right">
                                <a href="#">Download Sample File</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <span class="font-bold">Target Keywords<i class="feather icon-info"></i></span>
                        <hr>
                      </div>
                      <div class="target-urls-group mb-4">
                        <table>
                          <thead>
                            <tr>
                              <th width="3%"></th>
                              <th width="40%"></th>
                              <th width="15%">Weight/Max Hits</th>
                              <th width="15%">Preview Link</th>
                              <th width="10%"></th>
                            </tr>
                          </thead>
                          <tbody>
                            @for ($i = 0; $i < 3; $i ++)
                              <tr>
                                <td>{{$i + 1}}.</td>
                                <td class="pr-1">
                                  <input type="text" class="form-control">
                                </td>
                                <td class="pr-1">
                                  <input type="text" class="form-control">
                                </td>
                                <td>
                                  https://domain.com/keyword1
                                </td>
                                <td class="text-right">
                                  <a href="#"><i class="fa fa-external-link fa-2x mr-1"></i></a>
                                  <a href="#"><i class="fa fa-trash fa-2x"></i></a>
                                </td>
                              </tr>
                            @endfor
                          </tbody>
                        </table>
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
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
  <script>
    $(function(){
      $(".number-tab-steps").steps({
          headerTag: "h6",
          bodyTag: "fieldset",
          transitionEffect: "fade",
          titleTemplate: '<span class="step">#index#</span> #title#',
          labels: {
              finish: 'Submit'
          },
          onFinished: function (event, currentIndex) {
              alert("Form submitted.");
          }
      });

      $(".select2").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        dropdownAutoWidth: true,
        width: '100%'
      });

      $('.pickadate').pickadate();

    })
  </script>
@endsection