<?php

namespace App\Http\Controllers\cadastros\manutencao;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class ManutencaoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Adiciona autenticação para Acesso a Página
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem das Rotinas de Manutenção
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        return view('cadastro/manutencao/list_manutencao');
    }

    /**
     * Método que realiza a limpeza da Cache como um todo
     */
    public function limparCache(){

        Cache::flush();
        return redirect()->route('lista_manutencao')->with('status', 'Cache limpa com Sucesso!');
    }

    /**
     * Método que realiza a limpeza dos Dados Unificados
     */
    public function limparDadosUnificados(){

        DB::select('DELETE FROM dado_unificados');

        return redirect()->route('lista_manutencao')->with('status', 'Dados Unificados limpos com Sucesso!');
    }

    /**
     * Método que realiza o carregamento dos Dados Unificados
     */
    public function carregarDadosUnificados(){

        DB::select('INSERT INTO dado_unificados 
        (id_aluno, nome_aluno, id_turma, nome_turma, turma_resumo, id_escola, nome_escola, id_municipio, nome_municipio,
        id_prova_gabarito, nome_prova, ano, gabarito_prova, id_disciplina, nome_disciplina, id_prova, respostaDoAluno,
        pontuacao, presenca, id_questao, numero_questao, desc_questao, id_tipo_questao, tipo_questao, imagem_questao, id_habilidade, 
        nome_habilidade, sigla_habilidade, id_tema, nome_tema, acerto, resposta, correta, created_at, updated_at, SAME)
        select a.id AS id_aluno, a.nome AS nome_aluno, t.id AS id_turma, t.DESCR_TURMA AS nome_turma, t.TURMA as turma_resumo, 
        e.id AS id_escola, e.nome AS nome_escola, m.id AS id_municipio, m.nome AS nome_municipio,
        pg.id AS id_prova_gabarito, pg.DESCR_PROVA AS nome_prova, pg.ano AS ano, pg.gabarito AS gabarito_prova,
        dc.id AS id_disciplina, dc.desc AS nome_disciplina, pv.id AS id_prova, pv.respostaDoAluno, pv.pontuacao,
        pv.status AS presenca, q.id AS id_questao, q.num_questao AS numero_questao, q.desc AS desc_questao, tq.id AS id_tipo_questao, 
        q.tipo AS tipo_questao, q.imagem AS imagem_questao, h.id AS id_habilidade, h.desc AS nome_habilidade, 
        SUBSTRING(h.desc, (LOCATE(\'(\', h.desc)+1), (LOCATE(\')\', h.desc)-2)) AS sigla_habilidade, tem.id AS id_tema, 
        tem.desc AS nome_tema, (CASE SUBSTRING(pv.respostaDoAluno,q.num_questao,1) WHEN SUBSTRING(pg.gabarito,q.num_questao,1) THEN 1 ELSE 0 END) AS acerto,
        SUBSTRING(pv.respostaDoAluno,q.num_questao,1) AS resposta, SUBSTRING(pg.gabarito,q.num_questao,1) AS correta, 
        NOW(), NOW(), q.SAME as SAME
        FROM resposta_teoricas rt
        INNER JOIN alunos a ON (a.id = rt.alunos_id AND a.SAME = rt.SAME)
        INNER JOIN turmas t ON (t.id = a.turmas_id AND t.SAME = a.SAME)
        INNER JOIN escolas e ON (e.id = a.turmas_escolas_id AND e.SAME = a.SAME)
        INNER JOIN municipios m ON (m.id = a.turmas_escolas_municipios_id AND m.SAME = a.SAME)
        INNER JOIN prova_gabaritos pg ON (pg.id = rt.prova_gabaritos_id AND pg.SAME = rt.SAME)
        INNER JOIN disciplinas dc ON dc.id = pg.disciplinas_id
        INNER JOIN provas pv ON (pv.alunos_id = rt.alunos_id AND pv.prova_gabaritos_id = rt.prova_gabaritos_id AND pv.SAME = rt.SAME)
        INNER JOIN questaos q ON (q.prova_gabaritos_id = rt.prova_gabaritos_id AND (q.modelo = (CASE pv.alunos_turmas_escolas_municipios_id WHEN 3 THEN 2 ELSE 1 END) OR q.modelo = 3) AND q.SAME = rt.SAME)
        INNER JOIN tipo_questaos tq ON tq.titulo = q.tipo
        INNER JOIN habilidades h ON h.id = q.habilidades_id
        INNER JOIN temas tem ON tem.id = q.temas_id 
        GROUP BY a.id, a.nome, t.id, e.id, m.id, pg.id, pg.DESCR_PROVA, pg.ano, pg.gabarito, dc.id, dc.desc, pv.id, pv.respostaDoAluno, pv.pontuacao, 
        pv.status, q.id, q.num_questao, q.desc, tq.id, q.tipo, q.imagem, h.id, h.desc, 
        SUBSTRING(h.desc, (LOCATE(\'(\', h.desc)+1), (LOCATE(\')\', h.desc)-2)), tem.id, tem.desc, 
        (CASE SUBSTRING(pv.respostaDoAluno,q.num_questao,1) WHEN SUBSTRING(pg.gabarito,q.num_questao,1) THEN 1 ELSE 0 END),
        SUBSTRING(pv.respostaDoAluno,q.num_questao,1), SUBSTRING(pg.gabarito,q.num_questao,1), q.SAME
        ORDER BY a.id, pv.id, q.num_questao');

        return redirect()->route('lista_manutencao')->with('status', 'Dados Unificados carregados com Sucesso!');

    }

    /**
     * Método que realiza o carregamento dos Dados Unificados
     */
    public function carregarDadosUnificadosV2(){

        DB::select('INSERT INTO dado_unificados 
        (id_aluno, nome_aluno, id_turma, nome_turma, turma_resumo, id_escola, nome_escola, id_municipio, nome_municipio,
        id_prova_gabarito, nome_prova, ano, gabarito_prova, id_disciplina, nome_disciplina, id_prova, respostaDoAluno,
        pontuacao, presenca, id_questao, numero_questao, desc_questao, id_tipo_questao, tipo_questao, imagem_questao, id_habilidade, 
        nome_habilidade, sigla_habilidade, id_tema, nome_tema, acerto, resposta, correta, created_at, updated_at, SAME)
        select a.id AS id_aluno, a.nome AS nome_aluno, t.id AS id_turma, t.DESCR_TURMA AS nome_turma, t.TURMA as turma_resumo, 
        e.id AS id_escola, e.nome AS nome_escola, m.id AS id_municipio, m.nome AS nome_municipio,
        pg.id AS id_prova_gabarito, pg.DESCR_PROVA AS nome_prova, SUBSTRING(REPLACE(t.DESCR_TURMA,\'\t\',\'\'),1,1) AS ano, pg.gabarito AS gabarito_prova,
        dc.id AS id_disciplina, dc.desc AS nome_disciplina, pv.id AS id_prova, pv.respostaDoAluno, pv.pontuacao,
        pv.status AS presenca, q.id AS id_questao, q.num_questao AS numero_questao, q.desc AS desc_questao, tq.id AS id_tipo_questao, 
        q.tipo AS tipo_questao, q.imagem AS imagem_questao, h.id AS id_habilidade, h.desc AS nome_habilidade, 
        SUBSTRING(h.desc, (LOCATE(\'(\', h.desc)+1), (LOCATE(\')\', h.desc)-2)) AS sigla_habilidade, tem.id AS id_tema, 
        tem.desc AS nome_tema, (CASE SUBSTRING(pv.respostaDoAluno,q.num_questao,1) WHEN SUBSTRING(pg.gabarito,q.num_questao,1) THEN 1 ELSE 0 END) AS acerto,
        SUBSTRING(pv.respostaDoAluno,q.num_questao,1) AS resposta, SUBSTRING(pg.gabarito,q.num_questao,1) AS correta, 
        NOW(), NOW(), q.SAME as SAME
        FROM resposta_teoricas rt
        INNER JOIN alunos a ON (a.id = rt.alunos_id AND a.SAME = rt.SAME)
        INNER JOIN turmas t ON (t.id = a.turmas_id AND t.SAME = a.SAME)
        INNER JOIN escolas e ON (e.id = a.turmas_escolas_id AND e.SAME = a.SAME)
        INNER JOIN municipios m ON (m.id = a.turmas_escolas_municipios_id AND m.SAME = a.SAME)
        INNER JOIN prova_gabaritos pg ON (pg.id = rt.prova_gabaritos_id AND pg.SAME = rt.SAME)
        INNER JOIN disciplinas dc ON dc.id = pg.disciplinas_id
        INNER JOIN provas pv ON (pv.alunos_id = rt.alunos_id AND pv.prova_gabaritos_id = rt.prova_gabaritos_id AND pv.SAME = rt.SAME)
        INNER JOIN questaos q ON (q.prova_gabaritos_id = rt.prova_gabaritos_id AND (q.modelo = (CASE pv.alunos_turmas_escolas_municipios_id WHEN 3 THEN 2 ELSE 1 END) OR q.modelo = 3) AND q.SAME = rt.SAME)
        INNER JOIN tipo_questaos tq ON tq.titulo = q.tipo
        INNER JOIN habilidades h ON h.id = q.habilidades_id
        INNER JOIN temas tem ON tem.id = q.temas_id 
        GROUP BY a.id, a.nome, t.id, e.id, m.id, pg.id, pg.DESCR_PROVA, pg.ano, pg.gabarito, dc.id, dc.desc, pv.id, pv.respostaDoAluno, pv.pontuacao, 
        pv.status, q.id, q.num_questao, q.desc, tq.id, q.tipo, q.imagem, h.id, h.desc, 
        SUBSTRING(h.desc, (LOCATE(\'(\', h.desc)+1), (LOCATE(\')\', h.desc)-2)), tem.id, tem.desc, 
        (CASE SUBSTRING(pv.respostaDoAluno,q.num_questao,1) WHEN SUBSTRING(pg.gabarito,q.num_questao,1) THEN 1 ELSE 0 END),
        SUBSTRING(pv.respostaDoAluno,q.num_questao,1), SUBSTRING(pg.gabarito,q.num_questao,1), q.SAME
        ORDER BY a.id, pv.id, q.num_questao');

        return redirect()->route('lista_manutencao')->with('status', 'Dados Unificados carregados com Sucesso!');

    }

}
