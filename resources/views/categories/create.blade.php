@extends('app')


@section('content')
    <div class="container">
        <h3>Nova Categorias</h3>

        @include('errors._check')

    {!! Form::open(['route'=>'categories.store']) !!}

        @include('categories._form')

        <div class="form-group">

            {!! Form::submit('criar categoria',['class'=>'btn btn-primary']) !!}
        </div>


    {!! Form::close()!!}
 </div>


@endsection