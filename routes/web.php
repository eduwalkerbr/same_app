<?php

use Illuminate\Support\Facades\Route;
use App\Mail\MensagemTesteMail;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\DeslogarController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFilter;
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
use App\Http\Controllers\SobreController;
use App\Http\Controllers\AlterarRegistroController;
use App\Http\Controllers\cadastros\tipoquestao\TipoQuestaoController;
use App\Http\Controllers\cadastros\criterioquestao\CriterioQuestaoController;
use App\Http\Controllers\cadastros\criterioquestao\CriterioQuestaoFilterController;
use App\Http\Controllers\TermoController;
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

/*
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware('verified');
*/


/*Route::resource('tarefa', 'App\Http\Controllers\TarefaController')
    ->middleware('verified');


Route::get('/mensagem-teste', function () {
    return new MensagemTesteMail();
    //Mail::to('atendimento@jorgesantana.net.br')->send(new MensagemTesteMail());
    //return 'E-mail enviado com sucesso!';
});*/

//Deslogar
Route::any('/deslogar', [DeslogarController::class, 'index'])->name('deslogar');

//Home
//Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/', [HomeController::class, 'index'])->name('home.index');

//Home Cadastro
Route::get('/cadastro', [CadastroController::class, 'index'])->name('cadastro.index');

//Sobre
Route::get('/sobre', [SobreController::class, 'index'])->name('sobre.index');

//Alterar Registro
Route::get('/registro/alterar', [AlterarRegistroController::class, 'index'])->name('alterar_registro.index');
Route::get('/registro/create', [AlterarRegistroController::class, 'create'])->name('cadastro_registro');
Route::post('/registro/store', [AlterarRegistroController::class, 'store'])->name('registro.store');
Route::get('/registro/{id}/edit', [AlterarRegistroController::class, 'edit'])->name('registro.edit');
Route::put('/registro/{id}', [AlterarRegistroController::class, 'update'])->name('registro.update');


//CRUD Usuário
Route::get('/user/create', [UserController::class, 'create'])->name('cadastro_user');
Route::get('/user/list', [UserController::class, 'exibirLista'])->name('lista_user');
Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.delete');


Route::any('/user/filtrar', [UserFilter::class, 'filtrar'])->name('user.filtrar');

//CRUD Tipo Solicitação
Route::get('/tipo_solicitacao/create', [TipoSolicitacaoController::class, 'create'])->name('cadastro_tipo_solicitacao');
Route::get('/tipo_solicitacao/list', [TipoSolicitacaoController::class, 'exibirLista'])->name('lista_tipo_solicitacao');
Route::post('/tipo_solicitacao/store', [TipoSolicitacaoController::class, 'store'])->name('tipo_solicitacao.store');
Route::get('/tipo_solicitacao/{id}/edit', [TipoSolicitacaoController::class, 'edit'])->name('tipo_solicitacao.edit');
Route::put('/tipo_solicitacao/{id}', [TipoSolicitacaoController::class, 'update'])->name('tipo_solicitacao.update');

//CRUD Município
Route::get('/municipio/create', [MunicipioController::class, 'create'])->name('cadastro_municipio');
Route::get('/municipio/list', [MunicipioController::class, 'exibirLista'])->name('lista_municipio');
Route::post('/municipio/store', [MunicipioController::class, 'store'])->name('municipio.store');
Route::get('/municipio/{id}/{anosame}/edit', [MunicipioController::class, 'edit'])->name('municipio.edit');
Route::put('/municipio/{id}', [MunicipioController::class, 'update'])->name('municipio.update');
Route::get('/municipio/{id}/{anosame}/inativar', [MunicipioController::class, 'inativar'])->name('municipio.inativar');
Route::get('/municipio/{id}/{anosame}/ativar', [MunicipioController::class, 'ativar'])->name('municipio.ativar');
Route::any('/municipio/filtrar', [MunicipioFilterController::class, 'filtrar'])->name('municipio.filtrar');


//CRUD Escola
Route::get('/escola/create', [EscolaController::class, 'create'])->name('cadastro_escola');
Route::get('/escola/list', [EscolaController::class, 'exibirLista'])->name('lista_escola');
Route::post('/escola/store', [EscolaController::class, 'store'])->name('escola.store');
Route::get('/escola/{id}/{anosame}/edit', [EscolaController::class, 'edit'])->name('escola.edit');
Route::put('/escola/{id}', [EscolaController::class, 'update'])->name('escola.update');
Route::get('/escola/{id}/{anosame}/inativar', [EscolaController::class, 'inativar'])->name('escola.inativar');
Route::get('/escola/{id}/{anosame}/ativar', [EscolaController::class, 'ativar'])->name('escola.ativar');
Route::any('/escola/filtrar', [EscolaFilterController::class, 'filtrar'])->name('escola.filtrar');
Route::get('/escola/get_by_same_municipio', [EscolaController::class, 'get_by_same_municipio'])->name('escola.get_by_same_municipio');

//CRUD Turma
Route::get('/turma/create', [TurmaController::class, 'create'])->name('cadastro_turma');
Route::get('/turma/list', [TurmaController::class, 'exibirLista'])->name('lista_turma');
Route::post('/turma/store', [TurmaController::class, 'store'])->name('turma.store');
Route::get('/turma/{id}/edit', [TurmaController::class, 'edit'])->name('turma.edit');
Route::put('/turma/{id}', [TurmaController::class, 'update'])->name('turma.update');
Route::get('/turma/{id}/inativar', [TurmaController::class, 'inativar'])->name('turma.inativar');
Route::get('/turma/{id}/ativar', [TurmaController::class, 'ativar'])->name('turma.ativar');
Route::any('/turma/filtrar', [TurmaFilterController::class, 'filtrar'])->name('turma.filtrar');
Route::get('/turma/get_by_same_escola', [TurmaController::class, 'get_by_same_escola'])->name('escola.get_by_same_escola');

//CRUD Funcao
Route::get('/funcao/create', [FuncaoController::class, 'create'])->name('cadastro_funcao');
Route::get('/funcao/list', [FuncaoController::class, 'exibirLista'])->name('lista_funcao');
Route::post('/funcao/store', [FuncaoController::class, 'store'])->name('funcao.store');
Route::get('/funcao/{id}/edit', [FuncaoController::class, 'edit'])->name('funcao.edit');
Route::put('/funcao/{id}', [FuncaoController::class, 'update'])->name('funcao.update');

//CRUD Disciplina
Route::get('/disciplina/create', [DisciplinaController::class, 'create'])->name('cadastro_disciplina');
Route::get('/disciplina/list', [DisciplinaController::class, 'exibirLista'])->name('lista_disciplina');
Route::post('/disciplina/store', [DisciplinaController::class, 'store'])->name('disciplina.store');
Route::get('/disciplina/{id}/edit', [DisciplinaController::class, 'edit'])->name('disciplina.edit');
Route::put('/disciplina/{id}', [DisciplinaController::class, 'update'])->name('disciplina.update');


//CRUD Habilidade
Route::get('/habilidade/create', [HabilidadeController::class, 'create'])->name('cadastro_habilidade');
Route::get('/habilidade/list', [HabilidadeController::class, 'exibirLista'])->name('lista_habilidade');
Route::post('/habilidade/store', [HabilidadeController::class, 'store'])->name('habilidade.store');
Route::get('/habilidade/{id}/edit', [HabilidadeController::class, 'edit'])->name('habilidade.edit');
Route::put('/habilidade/{id}', [HabilidadeController::class, 'update'])->name('habilidade.update');
Route::any('/habilidade/filtrar', [HabilidadeFilterController::class, 'filtrar'])->name('habilidade.filtrar');

//CRUD Tema
Route::get('/tema/create', [TemaController::class, 'create'])->name('cadastro_tema');
Route::get('/tema/list', [TemaController::class, 'exibirLista'])->name('lista_tema');
Route::post('/tema/store', [TemaController::class, 'store'])->name('tema.store');
Route::get('/tema/{id}/edit', [TemaController::class, 'edit'])->name('tema.edit');
Route::put('/tema/{id}', [TemaController::class, 'update'])->name('tema.update');
Route::any('/tema/filtrar', [TemaFilterController::class, 'filtrar'])->name('tema.filtrar');

//CRUD Aluno
Route::get('/aluno/create', [AlunoController::class, 'create'])->name('cadastro_aluno');
Route::get('/aluno/list', [AlunoController::class, 'exibirLista'])->name('lista_aluno');
Route::post('/aluno/store', [AlunoController::class, 'store'])->name('aluno.store');
Route::get('/aluno/{id}/{anosame}/edit', [AlunoController::class, 'edit'])->name('aluno.edit');
Route::put('/aluno/{id}', [AlunoController::class, 'update'])->name('aluno.update');
Route::any('/aluno/filtrar', [AlunoFilterController::class, 'filtrar'])->name('aluno.filtrar');

Route::get('/aluno/get_by_escola', [AlunoController::class, 'get_by_escola'])->name('aluno.get_by_escola');
Route::get('/aluno/get_by_municipio', [AlunoController::class, 'get_by_municipio'])->name('aluno.get_by_municipio');
Route::get('/aluno/get_by_same_escolav2', [AlunoController::class, 'get_by_same_escolav2'])->name('aluno.get_by_same_escolav2');

//CRUD Ano SAME
Route::get('/anosame/create', [AnoSAMEController::class, 'create'])->name('cadastro_anosame');
Route::get('/anosame/list', [AnoSAMEController::class, 'exibirLista'])->name('lista_anosame');
Route::post('/anosame/store', [AnoSAMEController::class, 'store'])->name('anosame.store');
Route::get('/anosame/{id}/edit', [AnoSAMEController::class, 'edit'])->name('anosame.edit');
Route::put('/anosame/{id}', [AnoSAMEController::class, 'update'])->name('anosame.update');
Route::get('/anosame/{id}/inativar', [AnoSAMEController::class, 'inativar'])->name('anosame.inativar');
Route::get('/anosame/{id}/ativar', [AnoSAMEController::class, 'ativar'])->name('anosame.ativar');

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

//CRUD Previlégios
Route::get('/previlegio/create', [PrevilegioController::class, 'create'])->name('cadastro_previlegio');
Route::get('/previlegio/list', [PrevilegioController::class, 'exibirLista'])->name('lista_previlegio');
Route::post('/previlegio/store', [PrevilegioController::class, 'store'])->name('previlegio.store');
Route::get('/previlegio/{id}/edit', [PrevilegioController::class, 'edit'])->name('previlegio.edit');
Route::put('/previlegio/{id}', [PrevilegioController::class, 'update'])->name('previlegio.update');
Route::get('/previlegio/{id}/inativar', [PrevilegioController::class, 'inativar'])->name('previlegio.inativar');
Route::get('/previlegio/{id}/ativar', [PrevilegioController::class, 'ativar'])->name('previlegio.ativar');
Route::any('/previlegio/filtrar', [PrevilegioFilterController::class, 'filtrar'])->name('previlegio.filtrar');

//CRUD Direção Professores
Route::get('/direcao_professor/create', [DirecaoProfessorController::class, 'create'])->name('cadastro_direcao_professor');
Route::get('/direcao_professor/list', [DirecaoProfessorController::class, 'exibirLista'])->name('lista_direcao_professor');
Route::post('/direcao_professor/store', [DirecaoProfessorController::class, 'store'])->name('direcao_professor.store');
Route::get('/direcao_professor/{id}/edit', [DirecaoProfessorController::class, 'edit'])->name('direcao_professor.edit');
Route::delete('/direcao_professor/{id}', [DirecaoProfessorController::class, 'destroy'])->name('direcao_professor.delete');
Route::put('/direcao_professor/{id}', [DirecaoProfessorController::class, 'update'])->name('direcao_professor.update');
Route::any('/direcao_professor/filtrar', [DirecaoProfessorFilterController::class, 'filtrar'])->name('direcao_professor.filtrar');
Route::get('/direcao_professor/get_by_escola', [DirecaoProfessorFilterController::class, 'get_by_escola'])->name('direcao_professor.get_by_escola');
Route::get('/direcao_professor/get_by_same_escolav3', [DirecaoProfessorController::class, 'get_by_same_escolav3'])->name('direcao_professor.get_by_same_escolav3');

//CRUD Prova Gabaritos
Route::get('/prova_gabarito/create', [ProvaGabaritoController::class, 'create'])->name('cadastro_prova_gabarito');
Route::get('/prova_gabarito/list', [ProvaGabaritoController::class, 'exibirLista'])->name('lista_prova_gabarito');
Route::post('/prova_gabarito/store', [ProvaGabaritoController::class, 'store'])->name('prova_gabarito.store');
Route::get('/prova_gabarito/{id}/edit', [ProvaGabaritoController::class, 'edit'])->name('prova_gabarito.edit');
Route::put('/prova_gabarito/{id}', [ProvaGabaritoController::class, 'update'])->name('prova_gabarito.update');
Route::get('/prova_gabarito/{id}/inativar', [ProvaGabaritoController::class, 'inativar'])->name('prova_gabarito.inativar');
Route::get('/prova_gabarito/{id}/ativar', [ProvaGabaritoController::class, 'ativar'])->name('prova_gabarito.ativar');
Route::any('/prova_gabarito/filtrar', [ProvaGabaritoFilterController::class, 'filtrar'])->name('prova_gabarito.filtrar');

//CRUD Questões
Route::get('/questao/create', [QuestaoController::class, 'create'])->name('cadastro_questao');
Route::get('/questao/list', [QuestaoController::class, 'exibirLista'])->name('lista_questao');
Route::post('/questao/store', [QuestaoController::class, 'store'])->name('questao.store');
Route::get('/questao/{id}/{SAME}/edit', [QuestaoController::class, 'edit'])->name('questao.edit');
Route::put('/questao/{id}', [QuestaoController::class, 'update'])->name('questao.update');
Route::any('/questao/filtrar', [QuestaoFilterController::class, 'filtrar'])->name('questao.filtrar');

//CRUD Sugestão
Route::post('/sugestao/store', [SugestaoController::class, 'store'])->name('sugestao.store');
Route::delete('/sugestao/{id}', [SugestaoController::class, 'destroy'])->name('sugestao.delete');
Route::get('/sugestao/list', [SugestaoController::class, 'exibirLista'])->name('lista_sugestoes');

//CRUD Prova Destaques
Route::get('/destaque/create', [DestaqueController::class, 'create'])->name('cadastro_destaque');
Route::get('/destaque/list', [DestaqueController::class, 'exibirLista'])->name('lista_destaque');
Route::post('/destaque/store', [DestaqueController::class, 'store'])->name('destaque.store');
Route::get('/destaque/{id}/edit', [DestaqueController::class, 'edit'])->name('destaque.edit');
Route::put('/destaque/{id}', [DestaqueController::class, 'update'])->name('destaque.update');

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

//CRUD Legenda
Route::get('/legenda/create', [LegendaController::class, 'create'])->name('cadastro_legenda');
Route::get('/legenda/list', [LegendaController::class, 'exibirLista'])->name('lista_legenda');
Route::post('/legenda/store', [LegendaController::class, 'store'])->name('legenda.store');
Route::get('/legenda/{id}/edit', [LegendaController::class, 'edit'])->name('legenda.edit');
Route::put('/legenda/{id}', [LegendaController::class, 'update'])->name('legenda.update');

//CRUD Tipo Questão
Route::get('/tipoquestao/create', [TipoQuestaoController::class, 'create'])->name('cadastro_tipoquestao');
Route::get('/tipoquestao/list', [TipoQuestaoController::class, 'exibirLista'])->name('lista_tipoquestao');
Route::post('/tipoquestao/store', [TipoQuestaoController::class, 'store'])->name('tipoquestao.store');
Route::get('/tipoquestao/{id}/edit', [TipoQuestaoController::class, 'edit'])->name('tipoquestao.edit');
Route::put('/tipoquestao/{id}', [TipoQuestaoController::class, 'update'])->name('tipoquestao.update');

//CRUD Critério Questão
Route::get('/criterios_questao/create', [CriterioQuestaoController::class, 'create'])->name('cadastro_criterios_questao');
Route::get('/criterios_questao/list', [CriterioQuestaoController::class, 'exibirLista'])->name('lista_criterios_questao');
Route::post('/criterios_questao/store', [CriterioQuestaoController::class, 'store'])->name('criterios_questao.store');
Route::get('/criterios_questao/{id}/edit', [CriterioQuestaoController::class, 'edit'])->name('criterios_questao.edit');
Route::put('/criterios_questao/{id}', [CriterioQuestaoController::class, 'update'])->name('criterios_questao.update');
Route::any('/criterios_questao/filtrar', [CriterioQuestaoFilterController::class, 'filtrar'])->name('criterios_questao.filtrar');

//CRUD Termo
Route::get('/termo/create', [TermoController::class, 'create'])->name('cadastro_termo');
Route::get('/termo/list', [TermoController::class, 'exibirLista'])->name('lista_termo');
Route::post('/termo/store', [TermoController::class, 'store'])->name('termo.store');
Route::get('/termo/{id}/edit', [TermoController::class, 'edit'])->name('termo.edit');
Route::put('/termo/{id}', [TermoController::class, 'update'])->name('termo.update');

//CRUD Turma Prévia
Route::get('/turma_previa/create', [TurmaPreviaController::class, 'create'])->name('cadastro_turma_previa');
Route::get('/turma_previa/list', [TurmaPreviaController::class, 'exibirLista'])->name('lista_turma_previa');
Route::post('/turma_previa/store', [TurmaPreviaController::class, 'store'])->name('turma_previa.store');
Route::get('/turma_previa/{id}/edit', [TurmaPreviaController::class, 'edit'])->name('turma_previa.edit');
Route::put('/turma_previa/{id}', [TurmaPreviaController::class, 'update'])->name('turma_previa.update');
Route::get('/turma_previa/{id}/inativar', [TurmaPreviaController::class, 'inativar'])->name('turma_previa.inativar');
Route::get('/turma_previa/{id}/ativar', [TurmaPreviaController::class, 'ativar'])->name('turma_previa.ativar');
Route::get('/turma_previa/get_by_escola', [TurmaPreviaController::class, 'get_by_escola'])->name('turma_previa.get_by_escola');
Route::any('/turma_previa/filtrar', [TurmaPreviaFilterController::class, 'filtrar'])->name('turma_previa.filtrar');

//CRUD Manutenção
Route::get('/manutencao/list', [ManutencaoController::class, 'exibirLista'])->name('lista_manutencao');
Route::get('/manutencao/cache', [ManutencaoController::class, 'limparCache'])->name('cache.limpar');
Route::get('/manutencao/dados_unificados/limpar', [ManutencaoController::class, 'limparDadosUnificados'])->name('dados_unificados.limpar');
Route::get('/manutencao/dados_unificados/carregar', [ManutencaoController::class, 'carregarDadosUnificados'])->name('dados_unificados.carregar');

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

