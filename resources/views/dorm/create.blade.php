@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('assets/libs/chartist/chartist.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
{{-- <p>Welcome to this beautiful admin panel.</p> --}}
<div class="row align-items-center">
    <div class="col-sm-6">
        <div class="page-title-box">
            <h4 class="font-size-18">Tambah Permintaan</h4>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Asrama >> Tambah Permintaan</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">

            @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form method="post" action="{{ route('dorm.store') }}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Organisasi</label>
                        <select name="organization" id="organization" class="form-control">
                            @foreach($organization as $row)
                                @if ($loop->first)
                                <option value="{{ $row->id }}" selected>{{ $row->nama }}</option>
                                @else
                                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nama Pelajar</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Pelajar">
                    </div>

                    <div class="form-group">
                        <label>Email Pelajar</label>
                        <input type="text" name="email" class="form-control" placeholder="Email Pelajar">
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category" id="category" class="form-control">
                            @foreach($category as $row)
                                @if ($loop->first)
                                <option value="{{ $row->id }}" selected>{{ $row->name }}</option>
                                @else
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Alasan</label>
                        <textarea name="reason" class="form-control" placeholder="Alasan Keluar" cols="30"
                            rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Tarikh Keluar</label>
                        <input onclick="this.showPicker()" class="form-control" id="start_date" name="start_date" type="date"
                                placeholder="Pilih Tarikh Keluar">
                    </div>

                    <div class="form-group mb-0">
                        <div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </form>
        </div>
    </div>
</div>
@endsection


@section('script')
<!-- Peity chart-->
<script src="{{ URL::asset('assets/libs/peity/peity.min.js')}}"></script>

<!-- Plugin Js-->
<script src="{{ URL::asset('assets/libs/chartist/chartist.min.js')}}"></script>

<script src="{{ URL::asset('assets/js/pages/dashboard.init.js')}}"></script>

<script>

    start_date.min = new Date().toISOString().split("T")[0];
    
</script>
@endsection