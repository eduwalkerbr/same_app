@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @switch($fase)
            @case(2)
                @include('previlegio._partials.escola');
                @break    
            @case(3)
                @include('previlegio._partials.turma');
                @break
        @endswitch
    </div>
</div>
@endsection
