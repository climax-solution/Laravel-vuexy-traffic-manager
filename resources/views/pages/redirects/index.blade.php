@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.RedirectLinks'))

@section('content')
	<a href="{{ route('redirects.create') }}" class="btn btn-primary">CREATE NEW REDIRECT LINK</a>
	<div class="row mt-1">
		<div class="col-lg-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-header d-flex align-items-start pb-0">
					<div>
						<h2 class="text-bold-700 mb-0 active-links">{{ $analysis['activeLinks'] }}</h2>
						<p class="small">Active Links</p>
					</div>
					<div class="avatar bg-rgba-primary p-50 m-0">
						<div class="avatar-content">
							<i class="feather icon-link text-primary font-medium-5"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-header d-flex align-items-start pb-0">
					<div>
						<h2 class="text-bold-700 mb-0 total-redirect">{{ $analysis['totalRedirects'] }}</h2>
						<p class="small">Total Redirects</p>
					</div>
					<div class="avatar bg-rgba-success p-50 m-0">
						<div class="avatar-content">
							<i class="feather icon-server text-success font-medium-5"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-header d-flex align-items-start pb-0">
					<div>
						<h2 class="text-bold-700 mb-0">{{ $analysis['totalPixelsFired'] }}</h2>
						<p class="small">Total Pixels Fired</p>
					</div>
					<div class="avatar bg-rgba-danger p-50 m-0">
						<div class="avatar-content">
							<i class="feather icon-activity text-danger font-medium-5"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-header d-flex align-items-start pb-0">
					<div>
						<h2 class="text-bold-700 mb-0">{{ $analysis['totalBlockedTraffic'] }}</h2>
						<p class="small">Total Blocked Traffic</p>
					</div>
					<div class="avatar bg-rgba-warning p-50 m-0">
						<div class="avatar-content">
							<i class="feather icon-alert-octagon text-warning font-medium-5"></i>
						</div>
						</div>
					</div>
				</div>
			</div>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="tblRedirects" class="table table-striped">
				  <thead>
					 <tr>
						<th>ID</th>
						<th>TRACKING URL</th>
						<th>DESTINATION URL</th>
						<th>ACTIVE</th>
						<th>ORDER</th>
						<th>MAX DAILY HITS</th>
						<th>HITS</th>
						<th class="action-head">ACTION</th>
					 </tr>
				  </thead>
				  <tbody>
					  @foreach ($redirects as $key => $redirect)
            @php
              $editURL = '/edit-url/'.$redirect->uuid;
            @endphp
					  <tr>
							<td>{{ $redirect->id }}</td>
							<td class="redirect-url">{{ env('APP_URL').'/r/'.$redirect->uuid }}</td>
							<td>{{ $redirect->dest_url ? $redirect->dest_url : 'Multiple URLs' }}</td>
						  <td>
								<div class="custom-control custom-switch custom-switch-success switch-lg mr-2">
									<input id="locked_{{ $redirect->id }}" class="custom-control-input active-switch" type="checkbox" {{ $redirect->active == 1 ? "checked" : "" }} value="{{$redirect->id}}">
									<label class="custom-control-label" for="locked_{{ $redirect->id }}">
										<span class="switch-text-left">Active</span>
										<span class="switch-text-right white">Inactive</span>
									</label>
								</div>
							</td>
							<td>{{ $redirect->order }}</td>
							<td>{{ $redirect->max_hit_day }}</td>
							<td>{{ $redirect->take_count }}</td>
							<td class="action-group">
                <a class="copy-btn" data-index="{{$key}}"><i class="fa fa-copy text-em"></i></a>
                <a href="{{ url($editURL) }}" class="edit-btn color-inherit" data-index="{{$key}}"><i class="feather icon-edit text-em"></i></a>
                <a class="clone-btn" data-id="{{$redirect->id}}"><i class="fa fa-clone text-em"></i></a>
                <a class="remove-btn" data-id="{{$redirect->id}}" data-index="{{$key}}"><i class="feather icon-trash-2 text-em"></i></a>
              </td>
					  </tr>
					  @endforeach
				  </tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">

@endsection

@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/plugins/toastr.css')) }}">

<style>
	.custom-select {
		height: auto;
	}
  .action-group {
    display: flex;
    justify-content: space-evenly;
  }
  .text-em {
    font-size: 16px;
  }
  .color-inherit,.color-inherit:hover {
    color: inherit;
  }
  .action-head {
    width: 90px !important;
    min-width: 90px !important;
  }
</style>
@endsection
@section('vendor-script')
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>

@endsection

@section('page-script')
<script>
  var tblRedirects = $("#tblRedirects").DataTable();
  const updateURL = "{{route('redirects.update-url-active')}}";
  const cloneURL = "{{route('redirects.clone-url')}}";
  const deleteURL = "{{route('redirects.delete-url')}}";
  const APP_URL = "{{env('APP_URL')}}";
</script>
<script src="{{asset(mix('js/scripts/redirects.js'))}}"></script>
@endsection
