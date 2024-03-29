@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/datatable.css')}}">
    @include('layouts.datatable')

    <style>
        :root {
            --primary-bc: #ffffff;
            --secondary-bc: rgb(2, 122, 129);
            --hover-color:rgb(6, 225, 237);
            --primary-color:#5b626b;
            --transition: all 0.3s linear;
        }
        .main-content{
            color: var(--primary-color);
        }
        .noborder{
        border: none!important;
        }

        #img-size
        {
        width: 100px;
        height: 100px;
        object-fit: cover;
        }

        .loading {
        width: 35px;
        height: 35px;
        display:none;
        }
        /* for submit button */
        .submit-btn {
            border: none;
            background: none;
        }
        .submit-btn span {
            color:var(--primary-color);
            padding-bottom: 7px;
            font-family: Roboto, sans-serif;
            font-size: 17.5px;
            padding-right: 15px;
            text-transform: uppercase;
        }
        .submit-btn svg {
            transform: translateX(-8px);
            transition: all 0.3s ease;
        }
        .submit-btn:hover svg {
            transform: translateX(0);
        }
        .submit-btn:active svg {
            transform: scale(0.9);
        }
        .hover-underline-animation {
            position: relative;
            color:var(--primary-color);
            padding-bottom: 20px;
        }
        .hover-underline-animation:after {
            content: "";
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }
        .submit-btn:hover .hover-underline-animation:after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }
        .form-control{
            border: 2px solid #5b626b6c!important;
        }
        .form-control:focus{
            outline: none;
            border: 2px solid #5b626b!important;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col">
                <div class="d-flex justify-content-center align-items-center">
                    <span class="h2 m-4">Bayaran Pesanan</span>
                </div>

                <div class="card">
                    <div class="card-body">

                        @if(Session::has('success'))
                            <div class="alert alert-success">
                            <p>{{ Session::get('success') }}</p>
                            </div>
                        @elseif(Session::has('error'))
                            <div class="alert alert-danger">
                            <p>{{ Session::get('error') }}</p>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-borderless responsive" id="cartTable" width="100%" cellspacing="0">
                                <thead class="thead-dark">
                                    <tr class="text-center">
                                        <th hidden>Order ID</th>
                                        <th>Nama Organisasi</th>
                                        <th>Alamat</th>
                                        <th>Nama Menu</th>
                                        <th>Kuantiti</th>
                                        <th>Waktu Pesanan</th>
                                        <th>Harga (RM)</th>
                                        <!-- <th>Total Harga (RM)</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $record)
                                    <tr>
                                        <td hidden>{{ $record->orderid }}</td>
                                        <td>{{ $record->nama }}</td>
                                        <td>{{ $record->address }}</td>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->quantity }}</td>
                                        <td>{{ $record->updated_at }}</td>
                                        <td>{{ $record->price }}</td>
                                        <!-- <td>{{ $record->totalprice }}</td> -->
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="text-align:right"><b>Total Harga (RM)</b> </td>
                                        <td style="text-align:right">
                                            <b>{{ $record->totalprice  }}</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        
                <div class="card mb-4 border">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
            
                <form action="{{ route('fpxIndex') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="desc" id="desc" value="OrderS">
                    <input type="hidden" name="orderid" id = "orderid" value="{{ $orderId }}">
                    <input type="hidden" name="amount" id="amount" value="{{ $record->totalprice }}">
                    <div class="card mb-4 border">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Pilih Bank</label>
                                        <select name="bankid" id="bankid" class="form-control"
                                            data-parsley-required-message="Sila pilih bank" required>
                                            <option value="">Pilih Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" type="button" class="btn-lg btn-light mr-2" style="color:#5b626b">Kembali</a>
                            {{-- <button type="submit" class="btn-lg btn-primary">Bayar</button> --}}
                            <button class="submit-btn" type="submit">
                                <span class="hover-underline-animation">Bayar</span>
                                <svg viewBox="0 0 46 16" height="10" width="30" xmlns="http://www.w3.org/2000/svg" id="arrow-horizontal">
                                    <path transform="translate(30)" d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" data-name="Path 10" id="Path_10"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var arr = [];
            
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: "/fpx/getBankList",
                success: function(data) {
                    jQuery.each(data.data, function(key, value){
                        arr.push(key);
                    });
                    for(var i = 0; i < arr.length; i++){
                        arr.sort();
                        $("#bankid").append("<option value='"+data.data[arr[i]].code+"'>"+data.data[arr[i]].nama+"</option>");
                    }

                },
                error: function (data) {
                    // console.log(data);
                }
            });

            $('.alert-success').delay(2000).fadeOut()
            $('.alert-danger').delay(4000).fadeOut()

        });
    </script>
@endsection