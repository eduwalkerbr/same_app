<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\DeslogarController;
use App\Http\Controllers\registro\RegistroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\UserFilter;
use App\Http\Controllers\cadastros\tiposolicitacao\TipoSolicitacaoController;
use App\Http\Controllers\cadastros\municipio\MunicipioController;
use App\Http\Controllers\cadastros\municipio\MunicipioFilterController;
use App\Http\Controllers\cadastros\escola\EscolaController;
use App\Http\Controllers\cadastros\escola\EscolaFilterController;
use App\Http\Controllers\cadastros\turma\TurmaController;
use App\Http\Controllers\cadastros\turma\TurmaFilterController;
use App\Http\Controllers\cadastros\funcao\FuncaoController;
use App\Http\Controllers\cadastros\disciplina\DisciplinaController;
use App\Http\Controllers\cadastros\habilidade\HabilidadeController;
use App\Http\Controllers\cadastros\habilidade\HabilidadeFilterController;
use App\Http\Controllers\cadastros\tema\TemaController;
use App\Http\Controllers\cadastros\tema\TemaFilterController;
use App\Http\Controllers\cadastros\aluno\AlunoController;
use App\Http\Controllers\cadastros\aluno\AlunoFilterController;
use App\Http\Controllers\cadastros\solicitacao\SolicitacaoController;
use App\Http\Controllers\cadastros\solicitacao\SolicitacaoRegistroController;
use App\Http\Controllers\cadastros\solicitacao\SolicitacaoTurmaController;
use App\Http\Controllers\cadastros\previlegio\PrevilegioController;
use App\Http\Controllers\cadastros\previlegio\PrevilegioFilterController;
use App\Http\Controllers\cadastros\direcaoprofessor\DirecaoProfessorController;
use App\Http\Controllers\cadastros\direcaoprofessor\DirecaoProfessorFilterController;
use App\Http\Controllers\cadastros\gabarito\ProvaGabaritoController;
use App\Http\Controllers\cadastros\gabarito\ProvaGabaritoFilterController;
use App\Http\Controllers\cadastros\questao\QuestaoController;
use App\Http\Controllers\cadastros\questao\QuestaoFilterController;
use App\Http\Controllers\cadastros\sugestao\SugestaoController;
use App\Http\Controllers\cadastros\destaque\DestaqueController;
use App\Http\Controllers\proficiencia\professor\ProfessorController;
use App\Http\Controllers\proficiencia\diretor\DiretorController;
use App\Http\Controllers\proficiencia\secretario\SecretarioController;
use App\Http\Controllers\comparativo\secretario\SecretarioComparativoController;
use App\Http\Controllers\comparativo\diretor\DiretorComparativoController;
use App\Http\Controllers\cadastros\legenda\LegendaController;
use App\Http\Controllers\sobre\SobreController;
use App\Http\Controllers\registro\AlterarRegistroController;
use App\Http\Controllers\cadastros\tipoquestao\TipoQuestaoController;
use App\Http\Controllers\cadastros\criterioquestao\CriterioQuestaoController;
use App\Http\Controllers\cadastros\criterioquestao\CriterioQuestaoFilterController;
use App\Http\Controllers\termo\TermoController;
use App\Http\Controllers\cadastros\turmaprevia\TurmaPreviaController;
use App\Http\Controllers\cadastros\turmaprevia\TurmaPreviaFilterController;
use App\Http\Controllers\cadastros\anosame\AnoSAMEController;
use App\Http\Controllers\cadastros\manutencao\ManutencaoController;
use App\Http\Controllers\cadastros\manutencao\CacheMunicipioController;
use App\Http\Controllers\cadastros\manutencao\CacheCompMunicipioController;
use App\Http\Controllers\cadastros\manutencao\CacheCompEscolaController;
use App\Http\Controllers\cadastros\manutencao\CacheEscolaController;
use App\Http\Controllers\cadastros\manutencao\CacheTurmaController;
use App\Http\Controllers\gestaoescolar\GestaoEscPrevilegioController;
use App\Http\Controllers\gestaoescolar\GestaoEscDirProfessorController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Auth::routes(['verify' => true]);

//Deslogar
Route::any('/deslogar', [DeslogarController::class, 'index'])->name('deslogar');

//Home
Route::get('/', [HomeController::class, 'index'])->name('home.index');

//Home Cadastro
Route::get('/cadastro', [CadastroController::class, 'index'])->name('cadastro.index');

//Sobre
Route::get('/sobre', [SobreController::class, 'index'])->name('sobre.index')->withoutMiddleware('auth');

//Alterar Registro
Route::resource('registro', AlterarRegistroController::class)->only('index','update');

//------------------------------------------------------ CRUDS -------------------------------------------------------------------

//CRUD Usuário
Route::prefix('user')->group(function () {
    Route::name('user.')->group(function () {
        Route::get('/list', [UserController::class, 'exibirLista'])->name('list');
        Route::any('/filtrar', [UserFilter::class, 'filtrar'])->name('filtrar');
    });    
});
Route::resource('user', UserController::class)->except('index','show');

//CRUD Tipo Solicitação
Route::get('/tipo_solicitacao/list', [TipoSolicitacaoController::class, 'exibirLista'])->name('lista_tipo_solicitacao');
Route::resource('tipo_solicitacao', TipoSolicitacaoController::class)->only('create','store','edit','update');

//CRUD Município
Route::prefix('municipio')->group(function () {
    Route::get('/list', [MunicipioController::class, 'exibirLista'])->name('lista_municipio');
    Route::name('municipio.')->group(function () {
        Route::get('/create', [MunicipioController::class, 'create'])->name('create');
        Route::post('/store', [MunicipioController::class, 'store'])->name('store');
        Route::get('/{id}/{anosame}/edit', [MunicipioController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MunicipioController::class, 'update'])->name('update');
        Route::get('/{id}/{anosame}/inativar', [MunicipioController::class, 'inativar'])->name('inativar');
        Route::get('/{id}/{anosame}/ativar', [MunicipioController::class, 'ativar'])->name('ativar');
        Route::any('/filtrar', [MunicipioFilterController::class, 'filtrar'])->name('filtrar');
        Route::get('/get_by_same', [MunicipioController::class, 'get_by_same'])->name('get_by_same');
    });    
});

//CRUD Escola
Route::prefix('escola')->group(function () {
    Route::get('/list', [EscolaController::class, 'exibirLista'])->name('lista_escola');
    Route::name('escola.')->group(function () {
        Route::get('/create', [EscolaController::class, 'create'])->name('create');
        Route::post('/store', [EscolaController::class, 'store'])->name('store');
        Route::get('/{id}/{anosame}/edit', [EscolaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EscolaController::class, 'update'])->name('update');
        Route::get('/{id}/{anosame}/inativar', [EscolaController::class, 'inativar'])->name('inativar');
        Route::get('/{id}/{anosame}/ativar', [EscolaController::class, 'ativar'])->name('ativar');
        Route::any('/filtrar', [EscolaFilterController::class, 'filtrar'])->name('filtrar');
        Route::get('/get_by_municipio', [EscolaController::class, 'get_by_municipio'])->name('get_by_municipio');
    });    
});

//CRUD Turma
Route::prefix('turma')->group(function () {
    Route::get('/list', [TurmaController::class, 'exibirLista'])->name('lista_turma');
    Route::name('turma.')->group(function () {
        Route::get('/create', [TurmaController::class, 'create'])->name('create');
        Route::post('/store', [TurmaController::class, 'store'])->name('store');
        Route::get('/{id}/{anosame}/edit', [TurmaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TurmaController::class, 'update'])->name('update');
        Route::get('/{id}/{anosame}/inativar', [TurmaController::class, 'inativar'])->name('inativar');
        Route::get('/{id}/{anosame}/ativar', [TurmaController::class, 'ativar'])->name('ativar');
        Route::any('/filtrar', [TurmaFilterController::class, 'filtrar'])->name('filtrar');
        Route::get('/get_by_same_escola', [TurmaController::class, 'get_by_same_escola'])->name('get_by_same_escola');
    });    
});

//CRUD Funcao
Route::get('/funcao/list', [FuncaoController::class, 'exibirLista'])->name('lista_funcao');
Route::resource('funcao', FuncaoController::class)->except('show','index');

//CRUD Disciplina
Route::get('/disciplina/list', [DisciplinaController::class, 'exibirLista'])->name('lista_disciplina');
Route::resource('disciplina', DisciplinaController::class)->except('show','index');


//CRUD Habilidade
Route::prefix('habilidade')->group(function () {
    Route::get('/list', [HabilidadeController::class, 'exibirLista'])->name('lista_habilidade');
    Route::any('/filtrar', [HabilidadeFilterController::class, 'filtrar'])->name('habilidade.filtrar');    
});
Route::resource('habilidade', HabilidadeController::class)->except('show','index');

//CRUD Tema
Route::prefix('tema')->group(function () {
    Route::get('/list', [TemaController::class, 'exibirLista'])->name('lista_tema');
    Route::any('/filtrar', [TemaFilterController::class, 'filtrar'])->name('tema.filtrar');
});
Route::resource('tema', TemaController::class)->except('show','index');

//CRUD Aluno
Route::prefix('aluno')->group(function () {
    Route::get('/list', [AlunoController::class, 'exibirLista'])->name('lista_aluno');
    Route::name('aluno.')->group(function () {
        Route::get('/create', [AlunoController::class, 'create'])->name('create');
        Route::post('/store', [AlunoController::class, 'store'])->name('store');
        Route::get('/{id}/{anosame}/edit', [AlunoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AlunoController::class, 'update'])->name('update');
        Route::any('/filtrar', [AlunoFilterController::class, 'filtrar'])->name('filtrar');
        Route::get('/get_by_same', [AlunoController::class, 'get_by_same'])->name('get_by_same');
        Route::get('/get_by_escola', [AlunoController::class, 'get_by_escola'])->name('get_by_escola');
        Route::get('/get_by_municipio', [AlunoController::class, 'get_by_municipio'])->name('get_by_municipio');
    });    
});

//CRUD Ano SAME
Route::prefix('anosame')->group(function () {
    Route::get('/list', [AnoSAMEController::class, 'exibirLista'])->name('lista_anosame');
    Route::name('anosame.')->group(function () {
        Route::get('/{id}/inativar', [AnoSAMEController::class, 'inativar'])->name('inativar');
        Route::get('/{id}/ativar', [AnoSAMEController::class, 'ativar'])->name('ativar');
    });    
});
Route::resource('anosame', AnoSAMEController::class)->except('index','show');

//CRUD Previlégios
Route::prefix('previlegio')->group(function () {
    Route::get('/list', [PrevilegioController::class, 'exibirLista'])->name('lista_previlegio');
    Route::name('previlegio.')->group(function () {
        Route::get('/{id}/inativar', [PrevilegioController::class, 'inativar'])->name('inativar');
        Route::get('/{id}/ativar', [PrevilegioController::class, 'ativar'])->name('ativar');
        Route::any('/filtrar', [PrevilegioFilterController::class, 'filtrar'])->name('filtrar');
    });    
});
Route::resource('previlegio', PrevilegioController::class)->except('index','show');

//CRUD Direção Professores
Route::prefix('direcao_professor')->group(function () {
    Route::get('/list', [DirecaoProfessorController::class, 'exibirLista'])->name('lista_direcao_professor');
    Route::name('direcao_professor.')->group(function () {
        Route::any('/filtrar', [DirecaoProfessorFilterController::class, 'filtrar'])->name('filtrar');
    });    
});
Route::resource('direcao_professor', DirecaoProfessorController::class)->except('index','show');

//CRUD Prova Gabaritos
Route::prefix('prova_gabarito')->group(function () {
    Route::get('/list', [ProvaGabaritoController::class, 'exibirLista'])->name('lista_prova_gabarito');
    Route::name('prova_gabarito.')->group(function () {
        Route::get('/{id}/inativar', [ProvaGabaritoController::class, 'inativar'])->name('inativar');
        Route::get('/{id}/ativar', [ProvaGabaritoController::class, 'ativar'])->name('ativar');
        Route::any('/filtrar', [ProvaGabaritoFilterController::class, 'filtrar'])->name('filtrar');
    });    
});
Route::resource('prova_gabarito', ProvaGabaritoController::class)->except('index','show','destroy');

//CRUD Questões
Route::prefix('questao')->group(function () {
    Route::get('/list', [QuestaoController::class, 'exibirLista'])->name('lista_questao');
    Route::name('questao.')->group(function () {
        Route::get('/questao/create', [QuestaoController::class, 'create'])->name('create');
        Route::post('/questao/store', [QuestaoController::class, 'store'])->name('store');
        Route::get('/questao/{id}/{anosame}/edit', [QuestaoController::class, 'edit'])->name('edit');
        Route::put('/questao/{id}', [QuestaoController::class, 'update'])->name('update');
        Route::any('/filtrar', [QuestaoFilterController::class, 'filtrar'])->name('filtrar');
    });    
});

//CRUD Sugestão
Route::get('/sugestao/list', [SugestaoController::class, 'exibirLista'])->name('lista_sugestoes');
Route::resource('sugestao', SugestaoController::class)->only('store','update','destroy');

//CRUD Prova Destaques
Route::get('/destaque/list', [DestaqueController::class, 'exibirLista'])->name('lista_destaque');
Route::resource('destaque', DestaqueController::class)->except('index','show','destroy');

//CRUD Legenda
Route::get('/legenda/list', [LegendaController::class, 'exibirLista'])->name('lista_legenda');
Route::resource('legenda', LegendaController::class)->except('index','show','destroy');

//CRUD Tipo Questão
Route::get('/tipoquestao/list', [TipoQuestaoController::class, 'exibirLista'])->name('lista_tipoquestao');
Route::resource('tipoquestao', TipoQuestaoController::class)->except('index','show','destroy');

//CRUD Critério Questão
Route::prefix('criterios_questao')->group(function () {
    Route::get('/list', [CriterioQuestaoController::class, 'exibirLista'])->name('lista_criterios_questao');
    Route::name('criterios_questao.')->group(function () {
        Route::any('/filtrar', [CriterioQuestaoFilterController::class, 'filtrar'])->name('filtrar');
    });    
});
Route::resource('criterios_questao', CriterioQuestaoController::class)->except('index','show','destroy');

//CRUD Termo
Route::get('/termo/list', [TermoController::class, 'exibirLista'])->name('lista_termo');
Route::resource('termo', TermoController::class)->except('index','show','destroy');

//CRUD Turma Prévia
Route::prefix('turma_previa')->group(function () {
    Route::get('/list', [TurmaPreviaController::class, 'exibirLista'])->name('lista_turma_previa');
    Route::name('turma_previa.')->group(function () {
        Route::get('/{id}/inativar', [TurmaPreviaController::class, 'inativar'])->name('inativar');
        Route::get('/{id}/ativar', [TurmaPreviaController::class, 'ativar'])->name('ativar');
        Route::get('/get_by_escola', [TurmaPreviaController::class, 'get_by_escola'])->name('get_by_escola');
        Route::any('/filtrar', [TurmaPreviaFilterController::class, 'filtrar'])->name('filtrar');
    });    
});
Route::resource('turma_previa', TurmaPreviaController::class)->except('index','show');

//------------------------------------------------------ CRUDS -------------------------------------------------------------------

//Home Registro
Route::get('/registro_base', [RegistroController::class, 'index'])->name('registro_base.index');
Route::post('/registro_complementar', [RegistroController::class, 'store'])->name('registro_complementar');

//Solicitacao
Route::get('/solicitacao/{id}/visualizar', [SolicitacaoController::class, 'show'])->name('exibe_registro_usuario');
Route::get('/solicitacao/{id}/negar', [SolicitacaoController::class, 'negar'])->name('negar_solicitacao');
Route::get('/solicitacao/lista_registro_usuario', [SolicitacaoController::class, 'listar_registros_usuario'])->name('lista_registros_usuario');
Route::get('/solicitacao/lista_solicitacao_turma', [SolicitacaoController::class, 'listar_solicitacao_turma'])->name('lista_solicitacao_turma');
Route::post('/solicitacao/aprovar', [SolicitacaoController::class, 'store'])->name('solicitacao.store');

//SolicitacaoRegistro
Route::post('/solicitacao/aprovar', [SolicitacaoRegistroController::class, 'store'])->name('solicitacao_registro.store');

//Solicitacao Turma
Route::get('/solicitacao/turma', [SolicitacaoTurmaController::class, 'index'])->name('solicitacao_turma.index');
Route::get('/solicitacao/get_by_escola', [SolicitacaoTurmaController::class, 'get_by_escola'])->name('solicitacao_turma.get_by_escola');
Route::get('/solicitacao/get_by_municipio', [SolicitacaoTurmaController::class, 'get_by_municipio'])->name('solicitacao_turma.get_by_municipio');
Route::post('/solicitacao/turma/aprovar', [SolicitacaoTurmaController::class, 'store'])->name('solicitacao_turma.store');

//Professor
Route::get('/turma_principal', [ProfessorController::class, 'index'])->name('professor.index');
Route::get('/turma_principal/{id}/{id_disciplina}/{id_escola}/{ano_same}', [ProfessorController::class, 'exibirTurma'])->name('professor.exibirTurma');
Route::get('/turma_principal/{id_disciplina}/{id_escola}/{ano_same}', [ProfessorController::class, 'exibirTurmaAnoSame'])->name('professor.exibirTurmaAnoSame');
Route::get('/turma_principal/{id}/{id_disciplina}/{id_escola}/{ano}/{ano_same}', [ProfessorController::class, 'exibirTurmaAno'])->name('professor.exibirTurmaAno');
Route::get('/turma_principal/{id}/{id_disciplina}/{id_escola}/{ano}/{id_habilidade}/{ano_same}', [ProfessorController::class, 'exibirTurmaHabilidade'])->name('professor.exibirTurmaHabilidade');

//Diretor
Route::get('/escola_principal', [DiretorController::class, 'index'])->name('diretor.index');
Route::get('/escola_principal/{id}/{id_municipio}/{id_disciplina}/{ano_same}', [DiretorController::class, 'exibirEscola'])->name('diretor.exibirEscola');
Route::get('/escola_principal/{id}/{id_municipio}/{id_disciplina}/{ano}/{ano_same}', [DiretorController::class, 'exibirEscolaAno'])->name('diretor.exibirEscolaAno');
Route::get('/escola_principal/{id}/{id_municipio}/{id_disciplina}/{ano}/{id_habilidade}/{ano_same}', [DiretorController::class, 'exibirEscolaHabilidade'])->name('diretor.exibirEscolaHabilidade');

//Diretor Comparativo
Route::get('/escola_principal/comparativo', [DiretorComparativoController::class, 'index'])->name('diretor_comparativo.index');
Route::get('/escola_comparativo/comparativo/{id}/{id_municipio}/{id_disciplina}/{sessao}', [DiretorComparativoController::class, 'exibirEscolaComparativo'])->name('diretor_comparativo.exibirEscolaComparativo');
Route::get('/escola_comparativo/comparativo/{id}/{id_municipio}/{id_disciplina}/{ano}/{sessao}', [DiretorComparativoController::class, 'exibirEscolaComparativoAno'])->name('diretor_comparativo.exibirEscolaComparativoAno');

//Secretario
Route::get('/municipio_principal', [SecretarioController::class, 'index'])->name('secretario.index');
Route::get('/municipio_principal/{id}/{id_disciplina}/{ano_same}', [SecretarioController::class, 'exibirMunicipio'])->name('secretario.exibirMunicipio');
Route::get('/municipio_principal/{id}/{id_disciplina}/{ano}/{ano_same}', [SecretarioController::class, 'exibirMunicipioAno'])->name('secretario.exibirMunicipioAno');
Route::get('/municipio_principal/{id}/{id_disciplina}/{ano}/{id_habilidade}/{ano_same}', [SecretarioController::class, 'exibirMunicipioHabilidade'])->name('secretario.exibirMunicipioHabilidade');

//Secretario Comparativo
Route::get('/municipio_principal/comparativo', [SecretarioComparativoController::class, 'index'])->name('secretario_comparativo.index');
Route::get('/municipio_comparativo/comparativo/{id}/{id_disciplina}/{sessao}', [SecretarioComparativoController::class, 'exibirMunicipioComparativo'])->name('secretario_comparativo.exibirMunicipioComparativo');
Route::get('/municipio_comparativo/comparativo/{id}/{id_disciplina}/{ano}/{sessao}', [SecretarioComparativoController::class, 'exibirMunicipioComparativoAno'])->name('secretario_comparativo.exibirMunicipioComparativoAno');

//CRUD Manutenção
Route::get('/manutencao/list', [ManutencaoController::class, 'exibirLista'])->name('lista_manutencao');
Route::get('/manutencao/cache', [ManutencaoController::class, 'limparCache'])->name('cache.limpar');
Route::get('/manutencao/dados_unificados/limpar', [ManutencaoController::class, 'limparDadosUnificados'])->name('dados_unificados.limpar');
Route::get('/manutencao/dados_unificados/carregar', [ManutencaoController::class, 'carregarDadosUnificados'])->name('dados_unificados.carregar');

//Gestão Escolar Previlégios
Route::get('/gestao_previlegio/create', [GestaoEscPrevilegioController::class, 'create'])->name('gest_cadastro_previlegio');
Route::post('/gestao_previlegio/store', [GestaoEscPrevilegioController::class, 'store'])->name('gest_previlegio.store');
Route::get('/gestao_previlegio/{id}/edit', [GestaoEscPrevilegioController::class, 'edit'])->name('gest_previlegio.edit');
Route::put('/gestao_previlegio/{id}', [GestaoEscPrevilegioController::class, 'update'])->name('gest_previlegio.update');
Route::get('/gestao_previlegio/{id}/inativar', [GestaoEscPrevilegioController::class, 'inativar'])->name('gest_previlegio.inativar');
Route::get('/gestao_previlegio/{id}/ativar', [GestaoEscPrevilegioController::class, 'ativar'])->name('gest_previlegio.ativar');
Route::any('/gestao_previlegio/filtrar', [GestaoEscPrevilegioController::class, 'filtrar'])->name('gest_previlegio.filtrar');
Route::get('/gestao_previlegio/listar', [GestaoEscPrevilegioController::class, 'exibirLista'])->name('gest_previlegio.listar');

//Gestão Escolar Direção Professor
Route::get('/gestao_direcao_professor/create', [GestaoEscDirProfessorController::class, 'create'])->name('gest_cadastro_direcao_professor');
Route::post('/gestao_direcao_professor/store', [GestaoEscDirProfessorController::class, 'store'])->name('gest_direcao_professor.store');
Route::get('/gestao_direcao_professor/{id}/edit', [GestaoEscDirProfessorController::class, 'edit'])->name('gest_direcao_professor.edit');
Route::put('/gestao_direcao_professor/{id}', [GestaoEscDirProfessorController::class, 'update'])->name('gest_direcao_professor.update');
Route::any('/gestao_direcao_professor/filtrar', [GestaoEscDirProfessorController::class, 'filtrar'])->name('gest_direcao_professor.filtrar');
Route::delete('/gestao_direcao_professor/{id}', [GestaoEscDirProfessorController::class, 'destroy'])->name('gest_direcao_professor.delete');
Route::get('/gestao_direcao_professor/list', [GestaoEscDirProfessorController::class, 'exibirLista'])->name('gest_direcao_professor.listar');


//------------------------------------------------------ CACHES -------------------------------------------------------------------

//Cache Município
Route::get('/manutencao/cache/municipio_dados_base', [CacheMunicipioController::class, 'carregarCacheMunDadosBase'])->name('cache.municipio_dados_base');
Route::get('/manutencao/cache/municipio_hab_ano_mat', [CacheMunicipioController::class, 'carregarCacheMunHabAnoMat'])->name('cache.municipio_hab_ano_mat');
Route::get('/manutencao/cache/municipio_hab_ano_port', [CacheMunicipioController::class, 'carregarCacheMunHabAnoPort'])->name('cache.municipio_hab_ano_port');
Route::get('/manutencao/cache/municipio_ano_hab', [CacheMunicipioController::class, 'carregarCacheMunAnoHab'])->name('cache.municipio_ano_hab');

//Cache Escola
Route::get('/manutencao/cache/escola_dados_base', [CacheEscolaController::class, 'carregarCacheEscDadosBase'])->name('cache.escola_dados_base');
Route::get('/manutencao/cache/escola_media_escola', [CacheEscolaController::class, 'carregarCacheEscSesMediaEscola'])->name('cache.media_escola');
Route::get('/manutencao/cache/escola_comp_disc', [CacheEscolaController::class, 'carregarCacheEscSesCompDisc'])->name('cache.escola_comp_disc');
Route::get('/manutencao/cache/escola_ano_cur_turma', [CacheEscolaController::class, 'carregarCacheEscAnoCurTurmas'])->name('cache.escola_ano_cur_turma');
Route::get('/manutencao/cache/escola_anos_hab_port', [CacheEscolaController::class, 'carregarCacheEscSesAnoHabPort'])->name('cache.escola_anos_hab_port');
Route::get('/manutencao/cache/escola_anos_hab_mat', [CacheEscolaController::class, 'carregarCacheEscSesAnoHabMat'])->name('cache.escola_anos_hab_mat');
Route::get('/manutencao/cache/escola_hab_anos_port', [CacheEscolaController::class, 'carregarCacheEscSesHabAnoPort'])->name('cache.escola_hab_anos_port');
Route::get('/manutencao/cache/escola_hab_anos_mat', [CacheEscolaController::class, 'carregarCacheEscSesHabAnoMat'])->name('cache.escola_hab_anos_mat');

//Cache Turma
Route::get('/manutencao/cache/turma_dados_base', [CacheTurmaController::class, 'cacheDadosBase'])->name('cache.turma_dados_base');
Route::get('/manutencao/cache/turma_media', [CacheTurmaController::class, 'cacheMediaTurma'])->name('cache.media_turma');
Route::get('/manutencao/cache/turma_tema', [CacheTurmaController::class, 'cacheTemaTurma'])->name('cache.turma_tema');
Route::get('/manutencao/cache/turma_hab_mat', [CacheTurmaController::class, 'cacheHabilidadeMatTurma'])->name('cache.turma_hab_mat');
Route::get('/manutencao/cache/turma_hab_port', [CacheTurmaController::class, 'cacheHabilidadePortTurma'])->name('cache.turma_hab_port');
Route::get('/manutencao/cache/turma_hab_ano_mat', [CacheTurmaController::class, 'cacheHabilidadeAnoMatTurma'])->name('cache.turma_hab_ano_mat');
Route::get('/manutencao/cache/turma_hab_ano_port', [CacheTurmaController::class, 'cacheHabilidadeAnoPortTurma'])->name('cache.turma_hab_ano_port');
Route::get('/manutencao/cache/turma_hab_sel_mat', [CacheTurmaController::class, 'cacheHabilidadeSelMatTurma'])->name('cache.turma_hab_sel_mat');
Route::get('/manutencao/cache/turma_hab_sel_port', [CacheTurmaController::class, 'cacheHabilidadeSelPortTurma'])->name('cache.turma_hab_sel_port');
Route::get('/manutencao/cache/turma_quest_mat', [CacheTurmaController::class, 'cacheQuestaoMatTurma'])->name('cache.turma_quest_mat');
Route::get('/manutencao/cache/turma_quest_port', [CacheTurmaController::class, 'cacheQuestaoPortTurma'])->name('cache.turma_quest_port');
Route::get('/manutencao/cache/turma_alunos', [CacheTurmaController::class, 'cacheAlunosTurma'])->name('cache.turma_alunos');

//Cache Município Comparativo
Route::get('/manutencao/cache/municipio_comp_disc', [CacheCompMunicipioController::class, 'carregarDisciplinaMunicipio'])->name('cache.disc_municipio');
Route::get('/manutencao/cache/municipio_comp_tema', [CacheCompMunicipioController::class, 'carregarTemaMunicipio'])->name('cache.tema_municipio');
Route::get('/manutencao/cache/municipio_comp_esc', [CacheCompMunicipioController::class, 'carregarEscolaMunicipio'])->name('cache.esc_municipio');
Route::get('/manutencao/cache/municipio_comp_esc_disc', [CacheCompMunicipioController::class, 'carregarEscolaDisciplinaMunicipio'])->name('cache.esc_disc_municipio');
Route::get('/manutencao/cache/municipio_comp_curricular', [CacheCompMunicipioController::class, 'carregarAnoCurricularDisciplinaMunicipio'])->name('cache.curricular_municipio');
Route::get('/manutencao/cache/municipio_comp_hab_anos_disc', [CacheCompMunicipioController::class, 'carregarHabAnosDisciplinaMunicipio'])->name('cache.hab_anos_disc_municipio');

//Cache Escola Comparativo
Route::get('/manutencao/cache/comp_disc_escola', [CacheCompEscolaController::class, 'carregarDisciplinaEscola'])->name('cache.disc_escola');
Route::get('/manutencao/cache/comp_tema_escola', [CacheCompEscolaController::class, 'carregarTemaEscola'])->name('cache.tema_escola');
Route::get('/manutencao/cache/comp_curricular_escola', [CacheCompEscolaController::class, 'carregarAnoCurricularDisciplinaEscola'])->name('cache.curricular_escola');
Route::get('/manutencao/cache/comp_hab_anos_disc_escola', [CacheCompEscolaController::class, 'carregarHabAnosDisciplinaEscola'])->name('cache.hab_anos_disc_escola');

//------------------------------------------------------ CACHES -------------------------------------------------------------------
