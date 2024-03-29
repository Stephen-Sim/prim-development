@extends('layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@include('layouts.datatable');

@endsection

@section('content')
@foreach ($data as $item)

<div class="row align-items-center">
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4 class="font-size-18">Trip Bus dari : {{ $item->bus_depart_from }} ke  {{ $item->bus_destination }}</h4>
        </div>
    </div>
</div>

@if ($item->status == 'AVAILABLE')
<form  method="post" action="/passengerpay-bus/{{ $item->id }}"> 
    @if(Session::has('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
    @endif
    @if(Session::has('fail'))
        <div class="alert alert-danger">{{Session::get('fail')}}</div>
    @endif
    @csrf
    <div class="table-responsive">
    <table id="bookgrab" class="table table-bordered table-striped dt-responsive wrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <tr>
    <th hidden>Passenger ID :</th>
    <td  hidden><input type="text" class="form-control" aria-describedby="emailHelp" value="{{ $userId }}" readonly name="idpassenger"></td>
    </tr>
    <tr>
    <th>Trip Number :</th>
    <td>{{ $item->trip_number }}</td>
    </tr>
    <tr>
    <th>Bus Registration Number :</th>
    <td>{{ $item->bus_registration_number }}</td>
    </tr>
    <tr>
    <th>Available Seat :</th>
    <td>{{ $item->available_seat }} seat</td>
    </tr>
    <tr>
    <th>Depart From :</th>
    <td>{{ $item->bus_depart_from }}</td>
    </tr>
    <tr>
    <th>Bus Destination :</th>
    <td>{{ $item->bus_destination }}</td>
    </tr>
    <tr>
    <th>Price Per Seat :</th>
    <td>RM {{ $item->price_per_seat }}</td>
    </tr>
    <tr>
    <th>Departure Time :</th>
    <td>{{ $item->departure_time }}</td>
    </tr>
    <tr>
    <th>Estimate Arrive Destination Time :</th>
    <td>{{ $item->estimate_arrive_time }}</td>
    </tr>
    <tr>
    <th>Departure Date :</th>
    <td>{{ $item->departure_date }}</td>
    </tr>   
    </table>
</div>
    <input type="text" class="form-control" aria-describedby="emailHelp" value="{{ $item->available_seat }}" hidden name="seat">
    <input type="text" class="form-control" aria-describedby="emailHelp" value="{{ $item->booked_seat }}" hidden name="bookedseat">
    <br>
    <button type="submit" class="btn btn-success">Make Payment</button>
    <button class="btn btn-danger"><a href="/passenger-grab" style="text-decoration: none; color: white;">Cancel</a></button>
</form>

@elseif ($item->status == 'NOT CONFIRM')
<form  method="post" action="/passengernotify-bus/{{ $item->id }}"> 
    @if(Session::has('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
    @endif
    @if(Session::has('fail'))
        <div class="alert alert-danger">{{Session::get('fail')}}</div>
    @endif
    @csrf
    <div class="table-responsive">
    <table id="bookgrab" class="table table-bordered table-striped dt-responsive wrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <tr>
    <th hidden>Passenger ID :</th>
    <td  hidden><input type="text" class="form-control" aria-describedby="emailHelp" value="{{ $userId }}" readonly name="idpassenger"></td>
    </tr>
    <tr>
    <th>Trip Number :</th>
    <td>{{ $item->trip_number }}</td>
    </tr>
    <tr>
    <th>Bus Registration Number :</th>
    <td>{{ $item->bus_registration_number }}</td>
    </tr>
    <tr>
    <th>Available Seat :</th>
    <td>{{ $item->available_seat }} seat</td>
    </tr>
    <tr>
    <th>Depart From :</th>
    <td>{{ $item->bus_depart_from }}</td>
    </tr>
    <tr>
    <th>Bus Destination :</th>
    <td>{{ $item->bus_destination }}</td>
    </tr>
    <tr>
    <th>Price Per Seat :</th>
    <td>RM {{ $item->price_per_seat }}</td>
    </tr>
    <tr>
    <th>Departure Time :</th>
    <td>{{ $item->departure_time }}</td>
    </tr>
    <tr>
    <th>Estimate Arrive Destination Time :</th>
    <td>{{ $item->estimate_arrive_time }}</td>
    </tr>
    <tr>
    <th>Departure Date :</th>
    <td>{{ $item->departure_date }}</td>
    </tr>
    </table>
</div>
    <br>
    <button type="submit" class="btn btn-primary">Add to Book List</button>
    <button class="btn btn-danger"><a href="/passenger-grab" style="text-decoration: none; color: white;">Cancel</a></button>
</form>
@endif
@endforeach 


@endsection

@section('script')
<!-- Peity chart-->
<script src="{{ URL::asset('assets/libs/peity/peity.min.js')}}"></script>

{{-- <script src="{{ URL::asset('assets/js/pages/dashboard.init.js')}}"></script> --}}

<script>
    $(document).ready(function() {
    
        $('#bookgrab').DataTable();
});

</script>

@endsection