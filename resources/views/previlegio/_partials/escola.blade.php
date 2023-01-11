<div class="col-md-8">
    <form method="get" action="{{ route('previlegio.create') }}">
        @csrf
        <input type="hidden" id="custId" name="fase" value="2">
        <div class="card">
            <div class="card-header">Identificaçao de Usuario  (escola.blade)</div>
                <div class="container text-center">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            Funcão:
                        </div>
                        <div class="col-md-3 text-center">
                            Município:
                        </div>
                        @error('funcao_id')
                           <div class="col-md-6 text-center text-danger">                       
                        @else
                             <div class="col-md-6 text-center">    
                        @enderror
                            Selecione Escola onde atua:
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-md-3  text-center ">
                            <div class="card-body">
                                 {{$funcao->desc}}                                         
                            </div>
                        </div>
                        <div class="col-md-3  text-center ">
                            <div class="card-body">
                                 {{$municipio->nome}}                                         
                            </div>
                        </div>
                        <div class="col-md-6  text-center ">
                            <div class="card-body">
                                <select name="escola_id" class="form-select" size="4" aria-label="size 4 select ">
                                    @foreach($escolas as $key => $item)
                                      <option value="{{$item->id}}" {{ old('escola_id') == $item->id ? 'selected' : '' }}>{{$item->nome}}</option>
                                    @endforeach
                                </select>                   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4  text-center">
                        <br> 
                        <button type="cancel" class="btn btn-primary">Voltar</button>
                        <br>               
                    </div>
                    <div class="col-md-4  text-center">
                        
                    </div>
                     <div class="col-md-4  text-center">
                        <br> 
                        <button type="submit" class="btn btn-primary">Proximo</button>
                        <br>
                    </div>
                </div>
                <br>                    
            </div>
        </div>  
    </form>
</div>
