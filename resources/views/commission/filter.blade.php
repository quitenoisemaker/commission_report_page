@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div id="accordion">
                <div class="card">
                  <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <h5 class="mb-0">
                      <button class="btn btn-link">
                        Filter Transaction
                      </button>
                    </h5>
                  </div>
              
                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <form method="POST" action="{{ route('commission.report.filter')}}">
                            @csrf 
                            <div class="form-row align-items-center">
                                {{-- <div class="form-group col-md-12">
                                    <select name="user_id" id="user" required class="form-control" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                        <option value="" selected disabled>Search users</option>
                                        @if (count($users)>0)
                                            @foreach($users as $user)
                                                <option value={{$user->id}}>{{$user->fname.' '. $user->lname}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div> --}}

                                <div class="col-5">
                                    <label for="inlineFormInputGroup">From</label>
                                    
                                      <input type="date" name="from" class="form-control" id="inlineFormInputGroup">
                                   
                                </div>
                              <div class="col-5">
                                <label for="inlineFormInput">To</label>
                                <input type="date" name="to" class="form-control mb-2" id="inlineFormInput">
                            
                              </div>
                              
                              <div class="col-auto pt-4">
                                <button type="submit" class="btn btn-primary mb-2">Submit</button>
                              </div>
                            </div>
                          </form>
                    </div>
                  </div>
                </div>
              </div>
            <div class="card">
                <table class="table table-striped">
                    <thead>
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
                    <tbody>

                        @if (count($queryDataFilters)>0)
                            @foreach ($queryDataFilters as $queryDataFilter)
                                <tr>
                                    <td>{{$queryDataFilter->invoice_number}}</td>
                                    <td>{{$queryDataFilter->purchaser->last_name.' '.$queryDataFilter->purchaser->last_name}}</td>
                                    <td>{{App\User::getReferralName($queryDataFilter->id)}}</td>
                                    
                                    <td>{{App\User::getNumberOfDistributor($queryDataFilter->id)}}</td>
                                    <td>{{$queryDataFilter->order_date}}</td>
                                    <td>{{number_format(App\Models\OrderItem::orderItemsTotal($queryDataFilter->id),2)}}</td>
                                    <td>{{App\User::getcommissionPercentage($queryDataFilter->id, App\User::getNumberOfDistributor($queryDataFilter->id)).'%'}}</td>
                                    <td>{{number_format(getCommission(App\Models\OrderItem::orderItemsTotal($queryDataFilter->id), App\User::getcommissionPercentage($queryDataFilter->id, App\User::getNumberOfDistributor($queryDataFilter->id))) ,2)}}</td>
                                    <td>
                                        <a href="/items/viewData/{{$queryDataFilter->id}}" class="btn btn-default modal-global"><i class="glyphicon glyphicon-eye-open">View Items</i></a>
                                        {{-- <button type="submit" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal{{$order->id}}" data-id="{{$order->id}}">View Items</button> --}}
                                           
          
                                    </td>
                                    
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                  </table>
                  {{ $queryDataFilters->links() }}
            </div>
        </div>

    
    </div>
</div>
{{-- Modal --}}
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

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
        })
        .done(function(response) {
            let htmlCode = '';
             alert(response.data);
            let queryData = response.data;
            // alert(response);
            if (response) {

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
                        htmlCode += '<td>' + row.product_id + '</td>';
                        htmlCode += '<td>' + row.quantity + '</td>';
                        htmlCode += '<td>' + row.product_id + '</td>';
                        htmlCode += '<td>' + row.quantity + '</td>';
                
                        htmlCode += '</tr>';
                    });
                }
            $("#modal-global").find('.modal-body').html(htmlCode);
        });

    });
</script>
@endsection

