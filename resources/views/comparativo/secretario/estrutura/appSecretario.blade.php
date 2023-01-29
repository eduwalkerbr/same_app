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
            <div class="row">
                <div class="col-2 fixed-top">
                    <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4" style="margin-top:160px;margin-right:5px;">
                        <nav class="nav nav-pills flex-column" style="background-color:rgba(54, 162, 235, 0.2);">
                        <a id="link_municipio_comparativo" class="nav-link" onclick="manipularLink('municipio_comparativo')" href="#municipio_comparativo" style="font-size:15px;font-weight:bold;color:#0046AD;border: round 0;padding-top:20px;padding-bottom:20px;">Escolas</a>
                        <a id="link_graficoescoladisciplina" class="nav-link" onclick="manipularLink('graficoescoladisciplina')" href="#graficoescoladisciplina" style="font-size:15px;font-weight:bold;color:#0046AD;border: round 0;padding-top:20px;padding-bottom:20px;">Escolas por Disciplina</a>
                        <a id="link_graficodisciplina" onclick="manipularLink('graficodisciplina')" class="nav-link" href="#graficodisciplina" style="font-size:15px;font-weight:bold;color:#0046AD;border: round 0;padding-top:20px;padding-bottom:20px;">Disciplinas</a>
                        <a id="link_graficocurriculardisciplina" class="nav-link" onclick="manipularLink('graficocurriculardisciplina')" href="#graficocurriculardisciplina" style="font-size:15px;font-weight:bold;color:#0046AD;border: round 0;padding-top:20px;padding-bottom:20px;">Anos Curriculares por Disciplina</a>
                        <a id="link_graficotema" class="nav-link" onclick="manipularLink('graficotema')" href="#graficotema" style="font-size:15px;font-weight:bold;color:#0046AD;border: round 0;padding-top:20px;padding-bottom:20px;">Temas por Disciplina e Ano Curricular</a>
                        <a id="link_graficohabilidadeanodisciplina" class="nav-link" onclick="manipularLink('graficohabilidadeanodisciplina')" href="#graficohabilidadeanodisciplina" style="font-size:15px;font-weight:bold;color:#0046AD;border: round 0;padding-top:20px;padding-bottom:20px;">Habilidades por Disciplina e Ano Curricular</a>
                        </nav>
                    </nav>
                </div>
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
                            size: 14,
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

    //Copia os Labels Originais do Gráfico
    var labelsOriginais = [];
    graficoDisciplina.data.labels.forEach(element => labelsOriginais.push(element));

    //Copia os Dados Originais
    var dataOriginais = [];
    graficoDisciplina.data.datasets[0].data.forEach(element => dataOriginais.push(element));

    var anoRemDisciplina = [];
    var anoMantDisciplina = [];

    var labelRemDisciplina = [];
    var labelMantDisciplina = [];

    var nrLabelDisciplina = graficoDisciplina.data.labels.length;

    function manipularAno(ano){
        console.log("Alterando ...");
        //Reseta Array Mantidos
        labelMantDisciplina = [];
        anoMantDisciplina = [];
        
        anoFormatado = new String(ano);

        var component_button = document.getElementById("button_disc_" + anoFormatado);

        //Se tiver ao menos um Item no array
        if(graficoDisciplina.data.labels.length > 0){

            //Dentre os Dados Originais
            for($i = 0; $i < dataOriginais.length; $i++){
                if(dataOriginais[$i].x == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoDisciplina.data.datasets[0].data.includes(dataOriginais[$i])){
                        //Se não estiver adiciona
                        anoMantDisciplina.push(dataOriginais[$i]);
                        anoRemDisciplina = anoRemDisciplina.filter(dataItem => dataItem != dataOriginais[$i]);
                    } else {
                        if(graficoDisciplina.data.labels.length > 1){
                            anoRemDisciplina.push(dataOriginais[$i]);
                        } else {
                            anoMantDisciplina.push(dataOriginais[$i]);
                        }
                    }
                } else {
                    //Se diferente do Label Selecionado
                    if(!anoRemDisciplina.includes(dataOriginais[$i])){
                        anoMantDisciplina.push(dataOriginais[$i]);
                    }
                }
            }
            //Dentro da Lista de Labels Originais
            for ($i = 0; $i < labelsOriginais.length; $i++) {
                //Realiza a adequação dos Labels -------------------------------------------------------------------
                if(labelsOriginais[$i] == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoDisciplina.data.labels.includes(labelsOriginais[$i])){
                        //Se não estiver adiciona
                        labelMantDisciplina.push(labelsOriginais[$i]);
                        labelRemDisciplina = labelRemDisciplina.filter(label => label != labelsOriginais[$i]);
                    } else {
                        if(graficoDisciplina.data.labels.length > 1){
                            labelRemDisciplina.push(labelsOriginais[$i]);
                        } else {
                            labelMantDisciplina.push(labelsOriginais[$i]);
                        }
                    }
                    
                } else {
                    //Se diferente do Label Selecionado
                    if(!labelRemDisciplina.includes(labelsOriginais[$i])){
                        labelMantDisciplina.push(labelsOriginais[$i]);
                    }
                }
                //---------------------------------------------------------------------------------------------------
            } 
            graficoDisciplina.data.labels = [];
            labelMantDisciplina.forEach(element => graficoDisciplina.data.labels.push(element));

            for($i = 0; $i < labelsOriginais.length; $i++){
                graficoDisciplina.data.datasets[$i].data = anoMantDisciplina;
            }

            if (labelRemDisciplina.includes(ano)) {
                component_button.style.backgroundColor = 'white';
                component_button.style.color='black';
                component_button.innerHTML = "<i class=\"fa-solid fa-plus\"></i> " + component_button.textContent;
            } else {
                component_button.style.backgroundColor = '#f9821E';
                component_button.style.borderColor = '#f9821E';
                component_button.style.color='white';
                component_button.innerHTML = "<i class=\"fa-solid fa-minus\"></i> " + component_button.textContent;
            }
            graficoDisciplina.update();    
        }
    }

</script>

<script>

    // setup 
    const dataTema = {
        labels: <?php echo json_encode($label_tema) ?>,
        datasets: <?php echo json_encode($dados_tema) ?>,
    };

    // config 
    const configTema = {
        type: 'bar',
        data: dataTema,
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
                    text: 'Comparativo de Proficiência do Município nos Temas em ' + <?php echo json_encode($disciplina_selecionada[0]->desc) ?> + ' no ' + <?php echo json_encode($ano[0]) ?> + 'º Ano entre os Anos SAME',
                    font: {
                        size: 14,
                        family: 'arial',
                        weight: 'bold',
                        style: 'normal'
                    },
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 14,
                        boxHeight: 8,
                        font: {
                            size: 12,
                        },
                        usePointStyle: true,
                        pointStyle: 'rect',
                        boxWidth: 5,
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
    const ctxTema = document.getElementById('graficoTema');
    Chart.defaults.font.size = 13;
    var graficoTema = new Chart(
        ctxTema,
        configTema
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
    ctxTema.onclick = clickHandler;

    //Copia os Labels Originais do Gráfico
    var labelsOriginaisTema = [];
    graficoTema.data.labels.forEach(element => labelsOriginaisTema.push(element));

    //Copia os Dados Originais
    var dataOriginaisTema = [];
    graficoTema.data.datasets[0].data.forEach(element => dataOriginaisTema.push(element));

    var anoRemTema = [];
    var anoMantTema = [];

    var labelRemTema = [];
    var labelMantTema = [];

    var nrDataTema = graficoTema.data.datasets.length;

    function manipularAnoTema(ano){
        console.log("Alterando ...");
        //Reseta Array Mantidos
        labelMantTema = [];
        anoMantTema = [];
        
        anoFormatado = new String(ano);

        var component_button = document.getElementById("button_tema_" + anoFormatado);

        //Se tiver ao menos um Item no array
        if(graficoTema.data.labels.length > 0){

            //Dentre os Dados Originais
            for($i = 0; $i < dataOriginaisTema.length; $i++){
                if(dataOriginaisTema[$i].x == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoTema.data.datasets[0].data.includes(dataOriginaisTema[$i])){
                        //Se não estiver adiciona
                        anoMantTema.push(dataOriginaisTema[$i]);
                        anoRemTema = anoRemTema.filter(dataItem => dataItem != dataOriginaisTema[$i]);
                    } else {
                        if(graficoTema.data.labels.length > 1){
                            anoRemTema.push(dataOriginaisTema[$i]);
                        } else {
                            anoMantTema.push(dataOriginaisTema[$i]);
                        }
                    }
                } else {
                    //Se diferente do Label Selecionado
                    if(!anoRemTema.includes(dataOriginaisTema[$i])){
                        anoMantTema.push(dataOriginaisTema[$i]);
                    }
                }
            }
            //Dentro da Lista de Labels Originais
            for ($i = 0; $i < labelsOriginaisTema.length; $i++) {
                //Realiza a adequação dos Labels -------------------------------------------------------------------
                if(labelsOriginaisTema[$i] == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoTema.data.labels.includes(labelsOriginaisTema[$i])){
                        //Se não estiver adiciona
                        labelMantTema.push(labelsOriginaisTema[$i]);
                        labelRemTema = labelRemTema.filter(label => label != labelsOriginaisTema[$i]);
                    } else {
                        if(graficoTema.data.labels.length > 1){
                            labelRemTema.push(labelsOriginaisTema[$i]);
                        } else {
                            labelMantTema.push(labelsOriginaisTema[$i]);
                        }
                    }
                    
                } else {
                    //Se diferente do Label Selecionado
                    if(!labelRemTema.includes(labelsOriginaisTema[$i])){
                        labelMantTema.push(labelsOriginaisTema[$i]);
                    }
                }
                //---------------------------------------------------------------------------------------------------
            } 
            graficoTema.data.labels = [];
            labelMantTema.forEach(element => graficoTema.data.labels.push(element));

            for($i = 0; $i < nrDataTema; $i++){
                graficoTema.data.datasets[$i].data = anoMantTema;
            }

            if (labelRemTema.includes(ano)) {
                component_button.style.backgroundColor = 'white';
                component_button.style.color='black';
                component_button.innerHTML = "<i class=\"fa-solid fa-plus\"></i> " + component_button.textContent;
            } else {
                component_button.style.backgroundColor = '#f9821E';
                component_button.style.borderColor = '#f9821E';
                component_button.style.color='white';
                component_button.innerHTML = "<i class=\"fa-solid fa-minus\"></i> " + component_button.textContent;
            }
            graficoTema.update();    
        }
    }

</script>

<script>

    // setup 
    const dataEscola = {
        labels: <?php echo json_encode($label_escola) ?>,
        datasets: <?php echo json_encode($dados_escola) ?>,
    };

    // config 
    const configEscola = {
        type: 'bar',
        data: dataEscola,
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
                    text: 'Comparativo de Proficiência do Município entre as Escolas nos Anos SAME',
                    font: {
                        size: 14,
                        family: 'arial',
                        weight: 'bold',
                        style: 'normal'
                    },
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 14,
                        boxHeight: 10,
                        font: {
                            size: 12,
                        },
                        usePointStyle: true,
                        pointStyle: 'rect',
                        boxWidth: 5,
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
    const ctxEscola = document.getElementById('graficoEscola');
    Chart.defaults.font.size = 13;
    var graficoEscola = new Chart(
        ctxEscola,
        configEscola
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
    ctxEscola.onclick = clickHandler;

    //Copia os Labels Originais do Gráfico
    var labelsOriginaisEscola = [];
    graficoEscola.data.labels.forEach(element => labelsOriginaisEscola.push(element));

    //Copia os Dados Originais
    var dataOriginaisEscola = [];
    graficoEscola.data.datasets[0].data.forEach(element => dataOriginaisEscola.push(element));

    var anoRemEscola = [];
    var anoMantEscola = [];

    var labelRemEscola = [];
    var labelMantEscola = [];

    var nrDataEscola = graficoEscola.data.datasets.length;

    function manipularAnoEscola(ano){
        console.log("Alterando ...");
        //Reseta Array Mantidos
        labelMantEscola = [];
        anoMantEscola = [];
        
        anoFormatado = new String(ano);

        var component_button = document.getElementById("button_escola_" + anoFormatado);

        //Se tiver ao menos um Item no array
        if(graficoEscola.data.labels.length > 0){

            //Dentre os Dados Originais
            for($i = 0; $i < dataOriginaisEscola.length; $i++){
                if(dataOriginaisEscola[$i].x == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoEscola.data.datasets[0].data.includes(dataOriginaisEscola[$i])){
                        //Se não estiver adiciona
                        anoMantEscola.push(dataOriginaisEscola[$i]);
                        anoRemEscola = anoRemEscola.filter(dataItem => dataItem != dataOriginaisEscola[$i]);
                    } else {
                        if(graficoEscola.data.labels.length > 1){
                            anoRemEscola.push(dataOriginaisEscola[$i]);
                        } else {
                            anoMantEscola.push(dataOriginaisEscola[$i]);
                        }
                    }
                } else {
                    //Se diferente do Label Selecionado
                    if(!anoRemEscola.includes(dataOriginaisEscola[$i])){
                        anoMantEscola.push(dataOriginaisEscola[$i]);
                    }
                }
            }
            //Dentro da Lista de Labels Originais
            for ($i = 0; $i < labelsOriginaisEscola.length; $i++) {
                //Realiza a adequação dos Labels -------------------------------------------------------------------
                if(labelsOriginaisEscola[$i] == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoEscola.data.labels.includes(labelsOriginaisEscola[$i])){
                        //Se não estiver adiciona
                        labelMantEscola.push(labelsOriginaisEscola[$i]);
                        labelRemEscola = labelRemEscola.filter(label => label != labelsOriginaisEscola[$i]);
                    } else {
                        if(graficoEscola.data.labels.length > 1){
                            labelRemEscola.push(labelsOriginaisEscola[$i]);
                        } else {
                            labelMantEscola.push(labelsOriginaisEscola[$i]);
                        }
                    }
                    
                } else {
                    //Se diferente do Label Selecionado
                    if(!labelRemEscola.includes(labelsOriginaisEscola[$i])){
                        labelMantEscola.push(labelsOriginaisEscola[$i]);
                    }
                }
                //---------------------------------------------------------------------------------------------------
            } 
            graficoEscola.data.labels = [];
            labelMantEscola.forEach(element => graficoEscola.data.labels.push(element));

            for($i = 0; $i < nrDataEscola; $i++){
                graficoEscola.data.datasets[$i].data = anoMantEscola;
            }

            if (labelRemEscola.includes(ano)) {
                component_button.style.backgroundColor = 'white';
                component_button.style.color='black';
                component_button.innerHTML = "<i class=\"fa-solid fa-plus\"></i> " + component_button.textContent;
            } else {
                component_button.style.backgroundColor = '#f9821E';
                component_button.style.borderColor = '#f9821E';
                component_button.style.color='white';
                component_button.innerHTML = "<i class=\"fa-solid fa-minus\"></i> " + component_button.textContent;
            }
            graficoEscola.update();    
        }
    }

</script>

<script>

    // setup 
    const dataEscolaDisciplina = {
        labels: <?php echo json_encode($label_escola_disc) ?>,
        datasets: <?php echo json_encode($dados_escola_disc) ?>,
    };

    // config 
    const configEscolaDisciplina = {
        type: 'bar',
        data: dataEscolaDisciplina,
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
                    text: 'Comparativo de Proficiência do Município na Disciplina de ' + <?php echo json_encode($disciplina_selecionada[0]->desc) ?> + ' entre as Escolas nos Anos SAME',
                    font: {
                        size: 14,
                        family: 'arial',
                        weight: 'bold',
                        style: 'normal'
                    },
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxHeight: 10,
                        padding: 14,
                        font: {
                            size: 12,
                        },
                        usePointStyle: true,
                        pointStyle: 'rect',
                        boxWidth: 5,
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
    const ctxEscolaDisciplina = document.getElementById('graficoEscolaDisciplina');
    Chart.defaults.font.size = 13;
    var graficoEscolaDisciplina = new Chart(
        ctxEscolaDisciplina,
        configEscolaDisciplina
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
    ctxEscolaDisciplina.onclick = clickHandler;

    //Copia os Labels Originais do Gráfico
    var labelsOriginaisEscolaDisciplina = [];
    graficoEscolaDisciplina.data.labels.forEach(element => labelsOriginaisEscolaDisciplina.push(element));

    //Copia os Dados Originais
    var dataOriginaisEscolaDisciplina = [];
    graficoEscolaDisciplina.data.datasets[0].data.forEach(element => dataOriginaisEscolaDisciplina.push(element));

    var anoRemEscolaDisciplina = [];
    var anoMantEscolaDisciplina = [];

    var labelRemEscolaDisciplina = [];
    var labelMantEscolaDisciplina = [];

    var nrDataEscolaDisciplina = graficoEscolaDisciplina.data.datasets.length;

    function manipularAnoEscolaDisciplina(ano){
        console.log("Alterando ...");
        //Reseta Array Mantidos
        labelMantEscolaDisciplina = [];
        anoMantEscolaDisciplina = [];
        
        anoFormatado = new String(ano);

        var component_button = document.getElementById("button_escola_disc_" + anoFormatado);

        //Se tiver ao menos um Item no array
        if(graficoEscolaDisciplina.data.labels.length > 0){

            //Dentre os Dados Originais
            for($i = 0; $i < dataOriginaisEscolaDisciplina.length; $i++){
                if(dataOriginaisEscolaDisciplina[$i].x == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoEscolaDisciplina.data.datasets[0].data.includes(dataOriginaisEscolaDisciplina[$i])){
                        //Se não estiver adiciona
                        anoMantEscolaDisciplina.push(dataOriginaisEscolaDisciplina[$i]);
                        anoRemEscolaDisciplina = anoRemEscolaDisciplina.filter(dataItem => dataItem != dataOriginaisEscolaDisciplina[$i]);
                    } else {
                        if(graficoEscolaDisciplina.data.labels.length > 1){
                            anoRemEscolaDisciplina.push(dataOriginaisEscolaDisciplina[$i]);
                        } else {
                            anoMantEscolaDisciplina.push(dataOriginaisEscolaDisciplina[$i]);
                        }
                    }
                } else {
                    //Se diferente do Label Selecionado
                    if(!anoRemEscolaDisciplina.includes(dataOriginaisEscolaDisciplina[$i])){
                        anoMantEscolaDisciplina.push(dataOriginaisEscolaDisciplina[$i]);
                    }
                }
            }
            //Dentro da Lista de Labels Originais
            for ($i = 0; $i < labelsOriginaisEscolaDisciplina.length; $i++) {
                //Realiza a adequação dos Labels -------------------------------------------------------------------
                if(labelsOriginaisEscolaDisciplina[$i] == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoEscolaDisciplina.data.labels.includes(labelsOriginaisEscolaDisciplina[$i])){
                        //Se não estiver adiciona
                        labelMantEscolaDisciplina.push(labelsOriginaisEscolaDisciplina[$i]);
                        labelRemEscolaDisciplina = labelRemEscolaDisciplina.filter(label => label != labelsOriginaisEscolaDisciplina[$i]);
                    } else {
                        if(graficoEscolaDisciplina.data.labels.length > 1){
                            labelRemEscolaDisciplina.push(labelsOriginaisEscolaDisciplina[$i]);
                        } else {
                            labelMantEscolaDisciplina.push(labelsOriginaisEscolaDisciplina[$i]);
                        }
                    }
                    
                } else {
                    //Se diferente do Label Selecionado
                    if(!labelRemEscolaDisciplina.includes(labelsOriginaisEscolaDisciplina[$i])){
                        labelMantEscolaDisciplina.push(labelsOriginaisEscolaDisciplina[$i]);
                    }
                }
                //---------------------------------------------------------------------------------------------------
            } 
            graficoEscolaDisciplina.data.labels = [];
            labelMantEscolaDisciplina.forEach(element => graficoEscolaDisciplina.data.labels.push(element));

            for($i = 0; $i < nrDataEscolaDisciplina; $i++){
                graficoEscolaDisciplina.data.datasets[$i].data = anoMantEscolaDisciplina;
            }

            if (labelRemEscolaDisciplina.includes(ano)) {
                component_button.style.backgroundColor = 'white';
                component_button.style.color='black';
                component_button.innerHTML = "<i class=\"fa-solid fa-plus\"></i> " + component_button.textContent;
            } else {
                component_button.style.backgroundColor = '#f9821E';
                component_button.style.borderColor = '#f9821E';
                component_button.style.color='white';
                component_button.innerHTML = "<i class=\"fa-solid fa-minus\"></i> " + component_button.textContent;
            }
            graficoEscolaDisciplina.update();    
        }
    }

</script>

<script>

    // setup 
    const dataCurricularDisciplina = {
        labels: <?php echo json_encode($label_curricular_disc) ?>,
        datasets: <?php echo json_encode($dados_curricular_disc) ?>,
    };

    // config 
    const configCurricularDisciplina = {
        type: 'bar',
        data: dataCurricularDisciplina,
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
                    text: 'Comparativo de Proficiência do Município na Disciplina de ' + <?php echo json_encode($disciplina_selecionada[0]->desc) ?> + ' entre os Anos Curriculares nos Anos SAME',
                    font: {
                        size: 14,
                        family: 'arial',
                        weight: 'bold',
                        style: 'normal'
                    },
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxHeight: 10,
                        font: {
                            size: 13,
                        },
                        usePointStyle: true,
                        pointStyle: 'rect',
                        boxWidth: 5,
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
    const ctxCurricularDisciplina = document.getElementById('graficoCurricularDisciplina');
    Chart.defaults.font.size = 13;
    var graficoCurricularDisciplina = new Chart(
        ctxCurricularDisciplina,
        configCurricularDisciplina
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
    ctxCurricularDisciplina.onclick = clickHandler;

    //Copia os Labels Originais do Gráfico
    var labelsOriginaisCurricularDisciplina = [];
    graficoCurricularDisciplina.data.labels.forEach(element => labelsOriginaisCurricularDisciplina.push(element));

    //Copia os Dados Originais
    var dataOriginaisCurricularDisciplina = [];
    graficoCurricularDisciplina.data.datasets[0].data.forEach(element => dataOriginaisCurricularDisciplina.push(element));

    var anoRemCurricularDisciplina = [];
    var anoMantCurricularDisciplina = [];

    var labelRemCurricularDisciplina = [];
    var labelMantCurricularDisciplina = [];

    var nrDataCurricularDisciplina = graficoCurricularDisciplina.data.datasets.length;

    function manipularAnoCurricularDisciplina(ano){
        console.log("Alterando ...");
        //Reseta Array Mantidos
        labelMantCurricularDisciplina = [];
        anoMantCurricularDisciplina = [];
        
        anoFormatado = new String(ano);

        var component_button = document.getElementById("button_curricular_disc_" + anoFormatado);

        //Se tiver ao menos um Item no array
        if(graficoCurricularDisciplina.data.labels.length > 0){

            //Dentre os Dados Originais
            for($i = 0; $i < dataOriginaisCurricularDisciplina.length; $i++){
                if(dataOriginaisCurricularDisciplina[$i].x == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoCurricularDisciplina.data.datasets[0].data.includes(dataOriginaisCurricularDisciplina[$i])){
                        //Se não estiver adiciona
                        anoMantCurricularDisciplina.push(dataOriginaisCurricularDisciplina[$i]);
                        anoRemCurricularDisciplina = anoRemCurricularDisciplina.filter(dataItem => dataItem != dataOriginaisCurricularDisciplina[$i]);
                    } else {
                        if(graficoCurricularDisciplina.data.labels.length > 1){
                            anoRemCurricularDisciplina.push(dataOriginaisCurricularDisciplina[$i]);
                        } else {
                            anoMantCurricularDisciplina.push(dataOriginaisCurricularDisciplina[$i]);
                        }
                    }
                } else {
                    //Se diferente do Label Selecionado
                    if(!anoRemCurricularDisciplina.includes(dataOriginaisCurricularDisciplina[$i])){
                        anoMantCurricularDisciplina.push(dataOriginaisCurricularDisciplina[$i]);
                    }
                }
            }
            //Dentro da Lista de Labels Originais
            for ($i = 0; $i < labelsOriginaisCurricularDisciplina.length; $i++) {
                //Realiza a adequação dos Labels -------------------------------------------------------------------
                if(labelsOriginaisCurricularDisciplina[$i] == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoCurricularDisciplina.data.labels.includes(labelsOriginaisCurricularDisciplina[$i])){
                        //Se não estiver adiciona
                        labelMantCurricularDisciplina.push(labelsOriginaisCurricularDisciplina[$i]);
                        labelRemCurricularDisciplina = labelRemCurricularDisciplina.filter(label => label != labelsOriginaisCurricularDisciplina[$i]);
                    } else {
                        if(graficoCurricularDisciplina.data.labels.length > 1){
                            labelRemCurricularDisciplina.push(labelsOriginaisCurricularDisciplina[$i]);
                        } else {
                            labelMantCurricularDisciplina.push(labelsOriginaisCurricularDisciplina[$i]);
                        }
                    }
                    
                } else {
                    //Se diferente do Label Selecionado
                    if(!labelRemCurricularDisciplina.includes(labelsOriginaisCurricularDisciplina[$i])){
                        labelMantCurricularDisciplina.push(labelsOriginaisCurricularDisciplina[$i]);
                    }
                }
                //---------------------------------------------------------------------------------------------------
            } 
            graficoCurricularDisciplina.data.labels = [];
            labelMantCurricularDisciplina.forEach(element => graficoCurricularDisciplina.data.labels.push(element));

            for($i = 0; $i < nrDataCurricularDisciplina; $i++){
                graficoCurricularDisciplina.data.datasets[$i].data = anoMantCurricularDisciplina;
            }

            if (labelRemCurricularDisciplina.includes(ano)) {
                component_button.style.backgroundColor = 'white';
                component_button.style.color='black';
                component_button.innerHTML = "<i class=\"fa-solid fa-plus\"></i> " + component_button.textContent;
            } else {
                component_button.style.backgroundColor = '#f9821E';
                component_button.style.borderColor = '#f9821E';
                component_button.style.color='white';
                component_button.innerHTML = "<i class=\"fa-solid fa-minus\"></i> " + component_button.textContent;
            }
            graficoCurricularDisciplina.update();    
        }
    }

</script>

<script>

    // setup 
    const dataHabilidadeAnoDisciplina = {
        labels: <?php echo json_encode($label_hab_ano_disc) ?>,
        datasets: <?php echo json_encode($dados_hab_ano_disc) ?>,
    };

    // config 
    const configHabilidadeAnoDisciplina = {
        type: 'bar',
        data: dataHabilidadeAnoDisciplina,
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
                    text: 'Comparativo de Proficiência do Município nas Habilidades em ' + <?php echo json_encode($disciplina_selecionada[0]->desc) ?> + ' no ' + <?php echo json_encode($ano[0]) ?> + 'º Ano entre os Anos SAME',
                    font: {
                        size: 14,
                        family: 'arial',
                        weight: 'bold',
                        style: 'normal'
                    },
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxHeight: 10,
                        font: {
                            size: 13,
                        },
                        usePointStyle: true,
                        pointStyle: 'rect',
                        boxWidth: 5,
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
                                label += '% (';
                                label += valor.nome_habilidade;
                                label += ')';
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
    const ctxHabilidadeAnoDisciplina = document.getElementById('graficoHabilidadeAnoDisciplina');
    Chart.defaults.font.size = 13;
    var graficoHabilidadeAnoDisciplina = new Chart(
        ctxHabilidadeAnoDisciplina,
        configHabilidadeAnoDisciplina
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
    ctxHabilidadeAnoDisciplina.onclick = clickHandler;

    //Copia os Labels Originais do Gráfico
    var labelsOriginaisHabilidadeAnoDisciplina = [];
    graficoHabilidadeAnoDisciplina.data.labels.forEach(element => labelsOriginaisHabilidadeAnoDisciplina.push(element));

    //Copia os Dados Originais
    var dataOriginaisHabilidadeAnoDisciplina = [];
    graficoHabilidadeAnoDisciplina.data.datasets[0].data.forEach(element => dataOriginaisHabilidadeAnoDisciplina.push(element));

    var anoRemHabilidadeAnoDisciplina = [];
    var anoMantHabilidadeAnoDisciplina = [];

    var labelRemHabilidadeAnoDisciplina = [];
    var labelMantHabilidadeAnoDisciplina = [];

    var nrDataHabilidadeAnoDisciplina = graficoHabilidadeAnoDisciplina.data.datasets.length;

    function manipularHabilidadeAnoDisciplina(ano){
        console.log("Alterando ...");
        //Reseta Array Mantidos
        labelMantHabilidadeAnoDisciplina = [];
        anoMantHabilidadeAnoDisciplina = [];
        
        anoFormatado = new String(ano);

        var component_button = document.getElementById("button_hab_ano_disc_" + anoFormatado);

        //Se tiver ao menos um Item no array
        if(graficoHabilidadeAnoDisciplina.data.labels.length > 0){

            //Dentre os Dados Originais
            for($i = 0; $i < dataOriginaisHabilidadeAnoDisciplina.length; $i++){
                if(dataOriginaisHabilidadeAnoDisciplina[$i].x == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoHabilidadeAnoDisciplina.data.datasets[0].data.includes(dataOriginaisHabilidadeAnoDisciplina[$i])){
                        //Se não estiver adiciona
                        anoMantHabilidadeAnoDisciplina.push(dataOriginaisHabilidadeAnoDisciplina[$i]);
                        anoRemHabilidadeAnoDisciplina = anoRemHabilidadeAnoDisciplina.filter(dataItem => dataItem != dataOriginaisHabilidadeAnoDisciplina[$i]);
                    } else {
                        if(graficoHabilidadeAnoDisciplina.data.labels.length > 1){
                            anoRemHabilidadeAnoDisciplina.push(dataOriginaisHabilidadeAnoDisciplina[$i]);
                        } else {
                            anoMantHabilidadeAnoDisciplina.push(dataOriginaisHabilidadeAnoDisciplina[$i]);
                        }
                    }
                } else {
                    //Se diferente do Label Selecionado
                    if(!anoRemHabilidadeAnoDisciplina.includes(dataOriginaisHabilidadeAnoDisciplina[$i])){
                        anoMantHabilidadeAnoDisciplina.push(dataOriginaisHabilidadeAnoDisciplina[$i]);
                    }
                }
            }
            //Dentro da Lista de Labels Originais
            for ($i = 0; $i < labelsOriginaisHabilidadeAnoDisciplina.length; $i++) {
                //Realiza a adequação dos Labels -------------------------------------------------------------------
                if(labelsOriginaisHabilidadeAnoDisciplina[$i] == ano){
                    //Caso seja, verifica se está na Listagem atual de Labels
                    if(!graficoHabilidadeAnoDisciplina.data.labels.includes(labelsOriginaisHabilidadeAnoDisciplina[$i])){
                        //Se não estiver adiciona
                        labelMantHabilidadeAnoDisciplina.push(labelsOriginaisHabilidadeAnoDisciplina[$i]);
                        labelRemHabilidadeAnoDisciplina = labelRemHabilidadeAnoDisciplina.filter(label => label != labelsOriginaisHabilidadeAnoDisciplina[$i]);
                    } else {
                        if(graficoHabilidadeAnoDisciplina.data.labels.length > 1){
                            labelRemHabilidadeAnoDisciplina.push(labelsOriginaisHabilidadeAnoDisciplina[$i]);
                        } else {
                            labelMantHabilidadeAnoDisciplina.push(labelsOriginaisHabilidadeAnoDisciplina[$i]);
                        }
                    }
                    
                } else {
                    //Se diferente do Label Selecionado
                    if(!labelRemHabilidadeAnoDisciplina.includes(labelsOriginaisHabilidadeAnoDisciplina[$i])){
                        labelMantHabilidadeAnoDisciplina.push(labelsOriginaisHabilidadeAnoDisciplina[$i]);
                    }
                }
                //---------------------------------------------------------------------------------------------------
            } 
            graficoHabilidadeAnoDisciplina.data.labels = [];
            labelMantHabilidadeAnoDisciplina.forEach(element => graficoHabilidadeAnoDisciplina.data.labels.push(element));

            for($i = 0; $i < nrDataHabilidadeAnoDisciplina; $i++){
                graficoHabilidadeAnoDisciplina.data.datasets[$i].data = anoMantHabilidadeAnoDisciplina;
            }

            if (labelRemHabilidadeAnoDisciplina.includes(ano)) {
                component_button.style.backgroundColor = 'white';
                component_button.style.color='black';
                component_button.innerHTML = "<i class=\"fa-solid fa-plus\"></i> " + component_button.textContent;
            } else {
                component_button.style.backgroundColor = '#f9821E';
                component_button.style.borderColor = '#f9821E';
                component_button.style.color='white';
                component_button.innerHTML = "<i class=\"fa-solid fa-minus\"></i> " + component_button.textContent;
            }
            graficoHabilidadeAnoDisciplina.update();    
        }
    }

</script>

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

<!------------------------------------ Posição ao Abrir o Site ------------------->
<script>
    window.location.href ="#" + <?php echo json_encode($sessao_inicio) ?>;
</script>