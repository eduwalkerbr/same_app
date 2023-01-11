@extends('layouts.appRegistro')

@section('content')
<div class="container" style="height: 100vh;">
    <div class="row justify-content-center" style="margin-top: 150px;">
        <div class="col-md-8 align-self-center">
            <div class="card" style="box-shadow: 5px 5px 5px rgba(0,0,139);">
                <div class="card-header" style="background-color: #0046AD; color:white;">Registro Realizado com Sucesso!</div>
                <div class="card-body">
                    <h4 style="font-weight: bolder; font-size: 18px; color: black; margin-bottom: 20px;">OLÁ, SEU REGISTRO FOI REALIZADO COM SUCESSO!</h4>
                    <h6 style="color: black; font-weight: bold;">Agradecemos sua solicitação, e segue abaixo algumas informações importantes:</h6>
                    <h6> - Feita a solicitação, você já pode utilizar seu e-mail e senha para realizar acesso a plataforma</h6>
                    <h6> - A partir desse momento, você já tem acesso às funcionalidades gerais da plataforma</h6>
                    <h6> - Seus dados serão avaliados por um gestor, e após a avaliação positiva do mesmo, serão liberados previlégios a sessões personalizadas da plataforma</h6>
                    <!--<h6> - Você será informado via e-mail quando sua ativação estiver completa</h6> -->
                    <a class="btn btn-link" style="color:#f9821E;" href="{{ route('home.index') }}">Clique aqui para ser Redirecionado a Página Principal da plataforma</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection