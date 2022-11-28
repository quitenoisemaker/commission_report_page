@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div id="accordion">
                <div class="card">
                  <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <h5 class="mb-0">
                      <button class="btn btn-link text-muted">
                        Filter Transaction
                      </button>
                    </h5>
                  </div>
              
                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <form method="GET" action="{{ route('commission.report.filter')}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="form-row align-items-center">
                                <div class="form-group col-md-12">
                                    <select name="user_id" id="user_filter" class="form-control">
                                        <option value="" selected disabled>Search users</option>
                                        @if (count(getDistributor())>0)
                                        @foreach(getDistributor() as $user)
                                            <option value={{$user->id}}>{{$user->first_name.' '.$user->first_name}}</option>
                                        @endforeach
                                    @endif
                                      </select>
                                </div>

                                <div class="col-5">
                                    <label for="inlineFormInputGroup">From</label>
                                    <input type="date" name="from" class="form-control" id="from_date_order_filter">
                                </div>
                              <div class="col-5">
                                <label for="inlineFormInput">To</label>
                                <input type="date" name="to" class="form-control mb-2" id="to_date_order_filter">                            
                              </div>
                              
                              <div class="col-auto pt-4">
                                <button type="submit" id="filter_page" class="btn btn-primary mb-2">Submit</button>
                              </div>
                            </div>
                          </form>
                    </div>
                  </div>
                </div>
              </div>
            <div class="card" id="commission">
                <table class="table table-striped">
                    <thead id="commission-head">
                      <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col">Purchaser</th>
                        <th scope="col">Distributor</th>
                        <th scope="col">Referred Distributor</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Order Total</th>
                        <th scope="col">Percentage</th>
                        <th scope="col">Commission</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody id="commission-body">

                        @if (count($orders)>0)
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->invoice_number}}</td>
                                    <td>{{$order->purchaser->first_name.' '.$order->purchaser->last_name}}</td>
                                    {{-- call method to get distributor name --}}
                                    <td>{{App\User::getReferralName($order->id)}}</td>
                                    {{-- call method to get number of referred distributor --}}                            
                                    <td>{{App\User::getNumberOfReferredDistributor($order->id)}}</td>
                                    <td>{{$order->order_date}}</td>
                                    {{-- call method to get order Total --}}
                                    <td>{{number_format(App\Models\OrderItem::orderItemsTotal($order->id),2)}}</td>
                                    {{-- call method to get commission percentage --}}
                                    <td>{{App\User::getcommissionPercentage($order->id, App\User::getNumberOfReferredDistributor($order->id)).'%'}}</td>
                                    {{-- call method to get commission --}}
                                    <td>{{number_format(getCommission(App\Models\OrderItem::orderItemsTotal($order->id), App\User::getcommissionPercentage($order->id, App\User::getNumberOfReferredDistributor($order->id))) ,2)}}</td>
                                    <td>
                                        <a href="/items/viewData/{{$order->id}}" class="btn btn-primary modal-global"><i class="glyphicon glyphicon-eye-open">View Items</i></a>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                  </table>
                  <div id="commission_pagination">{{$orders->links('commission.pagination')}}</div>
            </div>
        </div>
    </div>
</div>
{{-- Modalsection --}}
    <div id="modal-global" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <i class="fa fa-3x fa-refresh fa-spin"></i>
                                <div>Please wait...</div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $('.modal-global').click(function(event) {
        event.preventDefault();

        var url = $(this).attr('href');

        $("#modal-global").modal('show');

                axios.get(url)
                .then(async function (response) {
            let htmlCode = '';    
            let queryData = response.data.responseMessage.data;
            let invoiceNumber = response.data.responseMessage.invoice_number;
            if (response) {
                    htmlCode += '<h4 class="">'+ invoiceNumber+'</h4>';
                    htmlCode += '<table class="table table-hover">';
                    htmlCode += '<thead>';
                    htmlCode += '<tr>';
                    htmlCode += ' <th scope="col">SKU</th>';
                    htmlCode += '<th scope="col">Product Name</th>';
                    htmlCode += '<th scope="col">Price</th>';
                    htmlCode += '<th scope="col">Quantity</th>';
                    htmlCode += '<th scope="col">Total</th>';
                    htmlCode += '</tr>';
                    htmlCode += '</thead>';
                    htmlCode += '<tbody>';
                }

            if (response) {
                let i = 0;
                    
                queryData.forEach(function(row) {
                    i++;
                    htmlCode += '<tr>';
                    htmlCode += '<td>' + row.order_id + '</td>';
                    htmlCode += '<td>' + row.product_name + '</td>';
                    htmlCode += '<td>' + row.product_price + '</td>';
                    htmlCode += '<td>' + row.qantity + '</td>';
                    htmlCode += '<td>' + row.total + '</td>';
                    htmlCode += '</tr>';
                    });
            }
            $("#modal-global").find('.modal-body').html(htmlCode);                                
        })
    });   

    $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
</script>

{{-- script file --}}
@include('commission.filter-script')
@endsection




