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
    <script src="{{ asset('js/utils.js') }}" defer></script>

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
                    @include('comparativo/secretario/menu.menucabecalho');
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

    const actions = [{
            name: 'Add Dataset',
            handler(chart) {
                const data = chart.data;
                const dsColor = Utils.namedColor(chart.data.datasets.length);
                const newDataset = {
                    label: 'Dataset ' + (data.datasets.length + 1),
                    backgroundColor: Utils.transparentize(dsColor, 0.5),
                    borderColor: dsColor,
                    borderWidth: 1,
                    data: Utils.numbers({
                        count: data.labels.length,
                        min: -100,
                        max: 100
                    }),
                };
                chart.data.datasets.push(newDataset);
                chart.update();
            }
        },
        {
            name: 'Remove Dataset',
            handler(chart) {
                chart.data.datasets.pop();
                chart.update();
            }
        },
    ];

    // setup 
    const dataDisciplina = {
        labels: <?php echo json_encode($label_disc) ?>,
        datasets: <?php echo json_encode($dados_disc) ?>,
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

            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Comparativo de Proficiência do Município entre as Disciplinas nos Anos SAME',
                    font: {
                        size: 14,
                        family: 'arial',
                        weight: 'bold',
                        style: 'normal'
                    },
                },
                legend: {
                    display: true,
                    labels: {
                        //color: 'rgb(255, 99, 132)',
                        font: {
                            size: 14
                        },
                        usePointStyle: true,
                        pointStyle: 'rect',
                    },
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let valor = context.dataset.data[context.dataIndex];
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += valor[context.dataset.label] || '';
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
    var graficoDisciplina = new Chart(
        ctxDisciplina,
        configDisciplina
    );

    function clickHandler(click) {
        const points = graficoLink.getElementsAtEventForMode(click, 'nearest', {
            intersect: true
        }, true);
        if (points.length) {
            const firstPoint = points[0];
            const value = graficoLink.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
            location.href = value.colunas.link;
            window.open(location.href);
        }
    }
    ctxDisciplina.onclick = clickHandler;

    var anoRemDisciplina = [];
    var labelRemDisciplina = [];
    var nrLabelDisciplina = graficoDisciplina.data.labels.length;

    function removerAno(){
        console.log("Removendo ...");
        if(graficoDisciplina.data.labels.length > 1){
            var setDataSet = false;
            for ($i = 0; $i < graficoDisciplina.data.labels.length; $i++) {
                if(!setDataSet){
                    anoRemDisciplina.push(graficoDisciplina.data.datasets[$i].data[graficoDisciplina.data.datasets[$i].data.length-1]);
                    setDataSet = true;
                }
                graficoDisciplina.data.datasets[$i].data.pop();
            } 
            labelRemDisciplina.push(graficoDisciplina.data.labels[graficoDisciplina.data.labels.length-1]);
            graficoDisciplina.data.labels.pop();
            graficoDisciplina.update();    
        }
    }

    function adicionarAno(){
        console.log("Adicionando ...");
        if(labelRemDisciplina.length > 0){
            for ($i = 0; $i < nrLabelDisciplina; $i++) {
                graficoDisciplina.data.datasets[$i].data.push(anoRemDisciplina[anoRemDisciplina.length-1]);
            }
            anoRemDisciplina.pop();

            graficoDisciplina.data.labels.push(labelRemDisciplina[labelRemDisciplina.length-1]);
            labelRemDisciplina.pop();

            graficoDisciplina.update(); 
        }
    }

    function resetar(){
        console.log("Resetando...");
        anoRemDisciplina.forEach(element => {
            for ($i = 0; $i < nrLabelDisciplina; $i++) {
                graficoDisciplina.data.datasets[$i].data.push(element);
            }
        });
        labelRemDisciplina.forEach(element => {
            graficoDisciplina.data.labels.push(element);
        });
        anoRemDisciplina = [];
        labelRemDisciplina = [];
        graficoDisciplina.update(); 
    }

</script>


<!------------------------------------ Posição ao Abrir o Site ------------------->
<script>
    window.location.href = '#fullpage';
</script>