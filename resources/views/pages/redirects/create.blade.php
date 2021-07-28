@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.CreateNewRedirect'))

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
@endsection

@section('page-style')
@endsection

@section('content')
	<div class="row">
		<p>Select the Type of Redirect Link that you would like to create.<p>
      <div class="table-responsive">
        <table class="table data-list-view">
          <thead>
            <tr>
              <th></th>
              <th>NAME</th>
              <th>CATEGORY</th>
              <th>POPULARITY</th>
              <th>ORDER STATUS</th>
              <th>PRICE</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($products as $product)
              @if($product["order_status"] === 'delivered')
                <?php $color = "success" ?>
              @elseif($product["order_status"] === 'pending')
                <?php $color = "primary" ?>
              @elseif($product["order_status"] === 'on hold')
                <?php $color = "warning" ?>
              @elseif($product["order_status"] === 'canceled')
                <?php $color = "danger" ?>
              @endif
              <?php
                $arr = array('success', 'primary', 'info', 'warning', 'danger');
              ?>

              <tr>
                <td></td>
                <td class="product-name">{{ $product["name"] }}</td>
                <td class="product-category">{{ $product["category"] }}</td>
                <td>
                  <div class="progress progress-bar-{{ $arr[array_rand($arr)] }}">
                    <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="40" aria-valuemax="100"
                      style="width:{{ $product["popularity"] }}%"></div>
                  </div>
                </td>
                <td>
                  <div class="chip chip-{{$color}}">
                    <div class="chip-body">
                      <div class="chip-text">{{ $product["order_status"]}}</div>
                    </div>
                  </div>
                </td>
                <td class="product-price">{{ $product["price"] }}</td>
                <td class="product-action">
                  <span class="action-edit"><i class="feather icon-edit"></i></span>
                  <span class="action-delete"><i class="feather icon-trash"></i></span>
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
