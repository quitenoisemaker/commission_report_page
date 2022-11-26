@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" id="Ranks">
                <table class="table table-striped">
                    <thead id="Ranks-head">
                      <tr>
                        <th scope="col">Top</th>
                        <th scope="col">Distributor Name</th>
                        <th scope="col">Total Sales</th>
                      </tr>
                    </thead>
                    <tbody id="commission-body">

                        @if (count($orders)>0)
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->serial_number}}</td>
                                    <td>{{$order->distributor}}</td>
                                    <td>{{$order->sales}}</td>        
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                  </table>
                  <div>{{$orders->links() }}</div>
            </div>
        </div>

    
    </div>
</div>
@endsection





