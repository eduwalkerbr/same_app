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


    <!-- This following line is optional. Only necessary if you use the option css3:false and you want to use other easing effects rather than "easeInOutCubic". -->

    


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
                    @include('layouts/secretario.menucabecalho');
                </div>
            </div>
        </header>

        <main class="py-4">
            <div class="row">
            @include('layouts/secretario.menulateral');
                <div  class="scrollspy-example-2 col-12">
                    @yield('content')
                    @if (session('status'))
                    <script>
                        alert("{{ session('status') }}");
                    </script>
                    @endif
                </div>
            </div>
        </main>
        <!------------------------------------ Rodap?? ------------------->
        @include('layouts/_parciais.footer')
    </div>
</body>

</html>
<!------------------------------------ Gr??ficos ------------------->
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
    const dataDisciplina = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_grafico_disciplina) ?>,
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
    const configDisciplina = {
        type: 'bar',
        data: dataDisciplina,
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
                    text: 'Profici??ncia do Munic??pio entre as Disciplinas',
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
                            let disciplina = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += disciplina.percentual || '';
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
    const ctxDisciplina = document.getElementById('graficoDisciplina');
    Chart.defaults.font.size = 13;
    const graficoDisciplina = new Chart(
        ctxDisciplina,
        configDisciplina
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
    ctxDisciplina.onclick = clickHandler;
</script>

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
    const dataEscola = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_grafico_escola) ?>,
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
    //let delayed;
    const configEscola = {
        type: 'bar',
        data: dataEscola,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla",
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
                    text: 'Profici??ncia do Munic??pio entre as Escolas',
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
                            let escola = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += escola.descricao;
                                label += ' - ';
                                label += escola.percentual || '';
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
    const ctxEscola = document.getElementById('graficoEscola');
    Chart.defaults.font.size = 13;
    const graficoEscola = new Chart(
        ctxEscola,
        configEscola
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
    ctxEscola.onclick = clickHandler;
</script>

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
    const dataEscolaDisciplina = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_grafico_escola_disciplina) ?>,
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
    //let delayed;
    const configEscolaDisciplina = {
        type: 'bar',
        data: dataEscolaDisciplina,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "sigla",
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
                    text: 'Profici??ncia do Munic??pio entre as Escolas na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>',
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
                label: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let escola = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += escola.descricao;
                                label += ' - ';
                                label += escola.percentual || '';
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
    const ctxEscolaDisciplina = document.getElementById('graficoEscolaDisciplina');
    Chart.defaults.font.size = 13;
    const graficoEscolaDisciplina = new Chart(
        ctxEscolaDisciplina,
        configEscolaDisciplina
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
    ctxEscolaDisciplina.onclick = clickHandler;
</script>


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
    const dataAnoDisciplina = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_anos_disciplina_grafico) ?>,
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
    //let delayed;
    const configAnoDisciplina = {
        type: 'bar',
        data: dataAnoDisciplina,
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
                    text: 'Profici??ncia do Munic??pio entre os Anos Curriculares na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>',
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
                            let AnoDisciplina = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += AnoDisciplina.percentual || '';
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
    const ctxAnoDisciplina = document.getElementById('graficoAnoDisciplina');
    Chart.defaults.font.size = 13;
    const graficoAnoDisciplina = new Chart(
        ctxAnoDisciplina,
        configAnoDisciplina
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
    ctxAnoDisciplina.onclick = clickHandler;
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
                    text: 'Profici??ncia por Habilidade na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>' + 'no ' + <?php echo $ano[0] ?> + '?? Ano',
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

    const dataHabilidadeDisciplinaHabilidade = {
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
                    text: 'Profici??ncia da Habilidade ' + '<?php echo $habilidade_selecionada[0]['sigla_habilidade'] ?>' + ' na Disciplina de ' + '<?php echo $disciplina_selecionada[0]['desc'] ?>' + ' no transcorrer dos Anos',
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
<!------------------------------------ Posi????o ao Abrir o Site ------------------->

<script>
    var sessao_historico = '';

    function manipularLink(sessao) {
        if(sessao_historico != ''){
            var component_link = document.getElementById('link_' + sessao_historico);
            component_link.style.color='#0046AD';
            component_link.style.backgroundColor='transparent';
        }

        sessao_historico = sessao;

        var component_link = document.getElementById('link_' + sessao);
        component_link.style.color='white';
        component_link.style.backgroundColor='#0046AD';
    }    
</script>

<script>
    window.onload = function () {
        manipularLink(<?php echo json_encode($sessao_inicio) ?>);
    }
</script>

<!------------------------------------ Posi????o ao Abrir o Site ------------------->
<script>
    window.location.href = "#" + <?php echo json_encode($sessao_inicio) ?>;
</script>

