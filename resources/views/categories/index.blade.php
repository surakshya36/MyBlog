@extends('layouts.master')
<nav>
@include('layouts.navbar')
</nav>  
@section('title', 'Add Categories')
@section('pageTitle','Categories')
@section('content')
<aside>
    @include('layouts.sidebar')
</aside>
    <section class="content">
        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">

                <div class="card-body">

                    <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Created by</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($data['rows'] as $i => $row)
                            <tr>
                                <td>{{$i+1}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->description}}</td>
                                <td>{{$row->creator->name}}</td>
                                <td>
                                    @if($row->status==1)
                                        <p style="color:Green">Active</p>
                                    @else
                                        <p style="color:red">Deactive</p>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{route('categories.show',$row->id)}}" class="btn btn-success btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <a href="{{route('categories.edit',$row->id)}}"  class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a>
                                    <form action="{{route('categories.destroy',$row->id)}}" method="post" class="d-inline">
                                        <input type="hidden" name="_method" value="delete" />
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button>

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- /.card-body -->

                </div>
            </div>
            <!-- /.card -->

        </section>
    </section>
    <!-- /.content -->
@endsection

@section('style')
    <style>
        .center-form{
            margin: auto;
        }
    </style>
@endsection
