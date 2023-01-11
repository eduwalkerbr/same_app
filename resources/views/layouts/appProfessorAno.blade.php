<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/delete.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/solid.css') }}" rel="stylesheet">


    <!-- JavaScript Bundle with Popper -->




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div id="app">
        <!------------------------------------ Menu Superior ------------------->
        <header>
            <div class="container">
                <div class="row justify-content-center">
                    @include('layouts/professor.menusuperior');
                </div>
            </div>
        </header>

        <main class="py-4">
            @yield('content')
            @if (session('status'))
            <script>
                alert("{{ session('status') }}");
            </script>
            @endif
        </main>
        <!------------------------------------ Rodapé ------------------->
        @include('layouts/_parciais.footer')
    </div>
</body>

</html>
<!------------------------------------ Gráficos ------------------->
<script>
    function triggerHover(chart) {
        if (chart.getActiveElements().length > 0) {
            chart.setActiveElements([]);
        } else {
            chart.setActiveElements([{
                datasetIndex: 0,
                index: 0,
            }, {
                datasetIndex: 1,
                index: 0,
            }]);
        }
        chart.update();
    }

    // setup 
    const data = {
        datasets: [{
            label: "Proficiência Turma",
            data: <?php echo json_encode($dados_comparacao_turma) ?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(0, 0, 0, 0.2)'
            ],

            borderColor: [
                'rgba(255, 26, 104, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1,
            hoverBorderWidth: 2,
            hoverBorderColor: 'green',


            //   barPercentage: .9,
            //   categoryPercentage: 1
        }]
    };

    function colorize(opaque) {
        return (ctx) => {
            const v = ctx.parsed.y;
            const c = v < -50 ? '#D60000' :
                v < 0 ? '#F46300' :
                v < 50 ? '#0358B6' :
                '#44DE28';

            return opaque ? c : Utils.transparentize(c, 1 - Math.abs(v / 150));
        };
    }

    // config 
    let delayed;
    const configTurma = {
        type: 'bar',
        data,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "descricao",
                yAxisKey: "percentual"
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Comparativo de Proficiência da Turma com a Média de Turmas do ' + <?php echo $ano[0] ?> + 'º Ano',
                    font: {
                        size: 13,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'normal'
                    },
                },
                legend: {
                    display: true,
                    labels: {
                        color: 'rgb(255, 99, 132)',
                        font: {
                            size: 15
                        },
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let turma = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += turma.percentual || '';
                                label += '% ';
                            }
                            return label;
                        }
                    }
                },
            },
            elements: {
                bar: {
                    backgroundColor: colorize(false),
                    borderColor: colorize(true),
                    borderWidth: 2
                }
            }
        }
    };

    // render init block
    const ctxTurma = document.getElementById('graficoTurma');
    Chart.defaults.font.size = 13;
    const graficoTurma = new Chart(
        ctxTurma,
        configTurma
    );

    function clickHandler(click) {
        const points = graficoLink.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            console.log(firstPoint);
            const value = graficoLink.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
            console.log(value.colunas.link);
            location.href = value.colunas.link;
            window.open(location.href);
        }
    }
    ctxTurma.onclick = clickHandler;
</script>

<script>
    // setup 

    const dataTema = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_grafico) ?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(0, 0, 0, 0.2)'
            ],

            borderColor: [
                'rgba(255, 26, 104, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1,
            hoverBorderWidth: 2,
            hoverBorderColor: 'green',


            //   barPercentage: .9,
            //   categoryPercentage: 1
        }]
    };

    // config 
    //let delayed;
    const configTema = {
        type: 'bar',
        data: dataTema,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla_tema",
                yAxisKey: "percentual_tema"
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Proficiência por ' + '<?php if ($disciplina_selecionada[0]['id'] == 1) {
                                                        echo 'Tema';
                                                    } else {
                                                        echo 'Eixo/Tema';
                                                    }
                                                    ?>' + ' na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>',
                    font: {
                        size: 13,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'normal'
                    },
                },
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)',
                        font: {
                            size: 15
                        },
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let tema = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += tema.percentual_tema || '';
                                label += '% - ';
                                label += tema.nome_tema;
                            }
                            return label;
                        }
                    }
                },
            },
            elements: {
                bar: {
                    backgroundColor: colorize(false),
                    borderColor: colorize(true),
                    borderWidth: 2
                }
            }
        }
    };

    // render init block
    const ctxTema = document.getElementById('graficoTema');
    Chart.defaults.font.size = 13;
    const graficoTema = new Chart(
        ctxTema,
        configTema
    );

    function clickHandler(click) {
        const points = graficoLink.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            console.log(firstPoint);
            const value = graficoLink.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
            console.log(value.colunas.link);
            location.href = value.colunas.link;
            window.open(location.href);
        }
    }
    ctxTema.onclick = clickHandler;
</script>


<script>
    // setup 

    const dataHabilidadeDisciplina = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_habilidade_disciplina_grafico) ?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(0, 0, 0, 0.2)'
            ],

            borderColor: [
                'rgba(255, 26, 104, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1,
            hoverBorderWidth: 2,
            hoverBorderColor: 'green',


            //   barPercentage: .9,
            //   categoryPercentage: 1
        }]
    };

    // config 
    //let delayed;
    const configHabilidadeDisciplina = {
        type: 'bar',
        data: dataHabilidadeDisciplina,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla_habilidade",
                yAxisKey: "percentual_habilidade"
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Proficiência por Habilidade na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>',
                    font: {
                        size: 13,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'normal'
                    },
                },
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)',
                        font: {
                            size: 15
                        },
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let habilidade = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += habilidade.percentual_habilidade || '';
                                label += '% - ';
                                label += habilidade.nome_habilidade;
                            }
                            return label;
                        }
                    }
                },
            },
            elements: {
                bar: {
                    backgroundColor: colorize(false),
                    borderColor: colorize(true),
                    borderWidth: 2
                }
            }
        }
    };

    // render init block
    const ctxHabilidadeDisciplina = document.getElementById('graficoHabilidadeDisciplina');
    Chart.defaults.font.size = 13;
    const graficoHabilidadeDisciplina = new Chart(
        ctxHabilidadeDisciplina,
        configHabilidadeDisciplina
    );

    function clickHandler(click) {
        const points = graficoHabilidadeDisciplina.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            $('#H' + (firstPoint.index + 1)).modal('show')
            //document.querySelectorAll(".modal")[0].style.display = 'block'
        }
    }
    ctxHabilidadeDisciplina.onclick = clickHandler;
</script>

<script>
    // setup 

    const dataHabilidadeDisciplinaAno = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_habilidade_disciplina_ano_grafico) ?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(0, 0, 0, 0.2)'
            ],

            borderColor: [
                'rgba(255, 26, 104, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1,
            hoverBorderWidth: 2,
            hoverBorderColor: 'green',


            //   barPercentage: .9,
            //   categoryPercentage: 1
        }]
    };

    // config 
    //let delayed;
    const configHabilidadeDisciplinaAno = {
        type: 'bar',
        data: dataHabilidadeDisciplinaAno,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla_habilidade",
                yAxisKey: "percentual_habilidade"
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Proficiência por Habilidade na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>' + 'no ' + <?php echo $ano_selecionado[0] ?> + 'º Ano',
                    font: {
                        size: 13,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'normal'
                    },
                },
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)',
                        font: {
                            size: 15
                        },
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let habilidade = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += habilidade.percentual_habilidade || '';
                                label += '% - ';
                                label += habilidade.nome_habilidade;
                            }
                            return label;
                        }
                    }
                },
            },
            elements: {
                bar: {
                    backgroundColor: colorize(false),
                    borderColor: colorize(true),
                    borderWidth: 2
                }
            }
        }
    };

    // render init block
    const ctxHabilidadeDisciplinaAno = document.getElementById('graficoHabilidadeDisciplinaAno');
    Chart.defaults.font.size = 13;
    const graficoHabilidadeDisciplinaAno = new Chart(
        ctxHabilidadeDisciplinaAno,
        configHabilidadeDisciplinaAno
    );

    function clickHandler(click) {
        const points = graficoHabilidadeDisciplinaAno.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            $('#HAB' + (firstPoint.index + 1)).modal('show')
            //document.querySelectorAll(".modal")[0].style.display = 'block'
        }
    }
    ctxHabilidadeDisciplinaAno.onclick = clickHandler;
</script>

<script>
    // setup 

    const dataQuestoesDisciplina = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_questao_grafico_disciplina) ?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(0, 0, 0, 0.2)'
            ],

            borderColor: [
                'rgba(255, 26, 104, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1,
            hoverBorderWidth: 2,
            hoverBorderColor: 'green',


            //   barPercentage: .9,
            //   categoryPercentage: 1
        }]
    };


    // config 
    //let delayed;
    const configQuestoesDisciplina = {
        type: 'bar',
        data: dataQuestoesDisciplina,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla_questao",
                yAxisKey: "percentual_questao"
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Proficiência por Questão na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>',
                    font: {
                        size: 13,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'normal'
                    },
                },
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)',
                        font: {
                            size: 15
                        },
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let questao = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += questao.percentual_questao || '';
                                label += '% - ';
                                label += questao.nome_questao;
                            }
                            return label;
                        }
                    }
                },
            },
            elements: {
                bar: {
                    backgroundColor: colorize(false),
                    borderColor: colorize(true),
                    borderWidth: 2
                }
            }
        }
    };

    // render init block
    const ctxQuestoesDisciplina = document.getElementById('graficoQuestoesDisciplina');
    Chart.defaults.font.size = 13;
    const graficoQuestoesDisciplina = new Chart(
        ctxQuestoesDisciplina,
        configQuestoesDisciplina
    );

    function clickHandler(click) {
        const points = graficoQuestoesDisciplina.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            $('#Q' + (firstPoint.index + 1)).modal('show')
            //document.querySelectorAll(".modal")[0].style.display = 'block'
        }
    }
    ctxQuestoesDisciplina.onclick = clickHandler;
</script>

<script>
    // setup 

    const dataAlunosDisciplina = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_aluno_grafico_disciplina) ?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(0, 0, 0, 0.2)'
            ],

            borderColor: [
                'rgba(255, 26, 104, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1,
            hoverBorderWidth: 2,
            hoverBorderColor: 'green',


            //   barPercentage: .9,
            //   categoryPercentage: 1
        }]
    };


    // config 
    //let delayed;
    const configAlunosDisciplina = {
        type: 'bar',
        data: dataAlunosDisciplina,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla_aluno",
                yAxisKey: "percentual_aluno"
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Proficiência por Aluno na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>',
                    font: {
                        size: 13,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'normal'
                    },
                },
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)',
                        font: {
                            size: 15
                        },
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let aluno = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += aluno.percentual_aluno || '';
                                label += '% - ';
                                label += aluno.nome_aluno;
                            }
                            return label;
                        }
                    }
                },
            },
            elements: {
                bar: {
                    backgroundColor: colorize(false),
                    borderColor: colorize(true),
                    borderWidth: 2
                }
            }
        }
    };

    // render init block
    const ctxAlunosDisciplina = document.getElementById('graficoAlunosDisciplina');
    Chart.defaults.font.size = 13;
    const graficoAlunosDisciplina = new Chart(
        ctxAlunosDisciplina,
        configAlunosDisciplina
    );

    function clickHandler(click) {
        const points = graficoAlunosDisciplina.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            $('#A' + (firstPoint.index + 1)).modal('show')
            //document.querySelectorAll(".modal")[0].style.display = 'block'
        }
    }
    ctxAlunosDisciplina.onclick = clickHandler;
</script>

<script>
    // setup 

    const dataHabilidadeDisciplinaHabilidade = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_habilidade_disciplina_grafico_habilidade) ?>,
            backgroundColor: [
                'rgba(255, 26, 104, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(0, 0, 0, 0.2)'
            ],

            borderColor: [
                'rgba(255, 26, 104, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1,
            hoverBorderWidth: 2,
            hoverBorderColor: 'green',


            //   barPercentage: .9,
            //   categoryPercentage: 1
        }]
    };

    // config 
    //let delayed;
    const configHabilidadeDisciplinaHabilidade = {
        type: 'bar',
        data: dataHabilidadeDisciplinaHabilidade,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla_ano",
                yAxisKey: "percentual_habilidade"
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Proficiência da Habilidade ' + '<?php echo $habilidade_selecionada[0]['sigla_habilidade'] ?>' + ' na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>' + ' no transcorrer dos Anos',
                    font: {
                        size: 13,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'normal'
                    },
                    fullsize: true,
                },
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)',
                        font: {
                            size: 15
                        },
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let habilidade = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += habilidade.percentual_habilidade || '';
                                label += '% - ';
                                label += habilidade.nome_habilidade;
                            }
                            return label;
                        }
                    }
                },
            },
            elements: {
                bar: {
                    backgroundColor: colorize(false),
                    borderColor: colorize(true),
                    borderWidth: 2
                }
            }
        }
    };

    // render init block
    const ctxHabilidadeDisciplinaHabilidade = document.getElementById('graficoHabilidadeDisciplinaHabilidade');
    Chart.defaults.font.size = 13;
    const graficoHabilidadeDisciplinaHabilidade = new Chart(
        ctxHabilidadeDisciplinaHabilidade,
        configHabilidadeDisciplinaHabilidade
    );

    function clickHandler(click) {
        const points = graficoHabilidadeDisciplinaHabilidade.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            $('#HABILIDADE' + (firstPoint.index + 1)).modal('show')
            //document.querySelectorAll(".modal")[0].style.display = 'block'
        }
    }
    ctxHabilidadeDisciplinaHabilidade.onclick = clickHandler;
</script>
<!------------------------------------ Posição ao Abrir o Site ------------------->
<script>
    window.location.href = '#questaomatematica';
</script>