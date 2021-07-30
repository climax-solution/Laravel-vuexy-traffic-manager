@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.CreateNewRedirect'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
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
                <div class="card-header">
                    <h4 class="card-title">Horizontal Form</h4>
                </div>
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
                                                <div class="col-md-4">
                                                  <div class="custom-control custom-switch custom-switch-success mr-2 mb-1">
                                                    <input type="checkbox" class="custom-control-input" id="spoof-refer-switch">
                                                    <label class="custom-control-label" for="spoof-refer-switch"></label>
                                                  </div>
                                                </div>
                                                <div class="col-md-8">
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
                                            <div class="col-md-4">
                                              <input type="date" id="max-hits-day" class="form-control" name="maxhits">
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <div class="col-md-4">
                                              <span>Fallback URL::</span>
                                            </div>
                                            <div class="col-md-8">
                                              <input type="text" id="fallback-url" class="form-control" name="fallback">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
                                        <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button>
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
@endsection

@section('page-script')
  <script src="{{ asset(mix('js/scripts/create-new-redirect.js')) }}"></script>
@endsection
