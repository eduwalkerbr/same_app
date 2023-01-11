<div class="col-md-8">
    <form method="get" action="{{ route('previlegio.create') }}">
        @csrf
        <input type="hidden" id="custId" name="fase" value=1>
        <div class="card">
            <div class="card-header">Identificaçao de Usuario (municipio.blade) </div>
                <div class="container text-center">
                    <div class="row">
                        @error('funcao_id')
                            <div class="col-md-6 text-center text-danger">                       
                        @else
                            <div class="col-md-6 text-center">    
                        @enderror
                            Selecone a sua Funcão
                        </div>
                        @error('municipio_id')
                            <div class="col-md-6 text-center text-danger">                       
                        @else
                            <div class="col-md-6 text-center">    
                        @enderror
                            Selecione o seu Município
                        </div>
                    </div>
      
                    <div class="row">
                        <div class="col-md-6  text-center ">
                            <div class="card-body">
                                <select name="funcao_id" class="form-select" size="4" aria-label="size 4 select ">
                                    @foreach($funcoes as $key => $item)
                                        <option value="{{$item->id}}" {{ old('funcao_id') == $item->id ? 'selected' : '' }}>{{$item->desc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6  text-center ">
                            <div class="card-body">
                                <select name="municipio_id" class="form-select" size="4" aria-label="size 3 select example">
                                    @foreach($municipios as $key => $item)
                                        <option value="{{$item->id}}" {{ old('municipio_id') == $item->id ? 'selected' : '' }}>{{$item->nome}}</option>
                                    @endforeach
                                </select>                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4  text-center">
                                     
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
