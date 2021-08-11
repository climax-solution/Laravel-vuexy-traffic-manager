@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.CreateNewRedirect'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
@endsection

@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
  <style>
    .xx-small {
      font-size: 8px;
    }
    .f-10 {
      font-size: 10px;
    }
  </style>
@endsection

@section('content')
	<div class="row">
		<p>Select the Type of Redirect Link that you would like to create.<p>
      <div class="table-responsive">
        <table class="table data-list-view dataTable">
          <tbody>
            @foreach ($products as $key => $product)
              @php
                $arr = array('success', 'primary', 'info', 'warning', 'danger');
              @endphp

              <tr>
                <td>
                  <i class="fa fa-link fa-2x"></i>
                </td>
                <td class="product-name">
                  <h5 class="text-weight">{{ $product["name"] }}</h5>
                  <p class="f-10">{{ $product['description'] }}</p>
                </td>
                <td>
                  <a href="{{route('redirects.'.$product['path'])}}" class="btn btn-danger btn-sm pt-1 pb-1 btn-block">SELECT</a>
                  <a class="xx-small work-btn">How does it work?</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
	</div>
@endsection

@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.select.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
@endsection

@section('page-script')
  <script src="{{ asset(mix('js/scripts/create-new-redirect.js')) }}"></script>
@endsection
