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
                    <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="padding: 0.5em 0 0.5em 0; background-color: white;box-shadow: 5px 5px 5px rgba(0,0,139);font-size: 17px;">
                        <div class="container">
                            <a class="navbar-brand" href="{{route('home.index')}}">
                                <img src="{{ asset('img/logo.png') }}" width="70" height="70" class="d-inline-block align-center" alt="" loading="lazy"></a>
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-weight:bold;">
                                <!-- Left Side Of Navbar -->
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item">
                                        <a id="navbar" class="nav-link" href="{{ route('home.index')}}" role="button" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Home
                                        </a>
                                    </li>
                                </ul>
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item">
                                        <a id="navbar" class="nav-link" href="{{route('sobre.index')}}" role="button" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Sobre Nós
                                        </a>
                                    </li>
                                </ul>
                                @php
                                $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                                @endphp
                                @if((isset($previlegio) && $previlegio->funcaos_id == 6) || Auth::user()->perfil == 'Administrador')
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Solicitações Pendentes <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;">{{sizeof($solRegistro) + sizeof($solAltCadastral) + sizeof($solAddTurma)}}</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="font-size: 15px;color: black;">
                                            @if(isset($solRegistro) && count($solRegistro) > 0)
                                            <a class="dropdown-item" href="{{ route('lista_registros_usuario') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Registros de Usuário <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solRegistro)}}</span>
                                            </a>
                                            @endif
                                            @if(isset($solAltCadastral) && count($solAltCadastral) > 0)
                                            <a class="dropdown-item" href="" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Alteração Cadastral <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solAltCadastral)}}</span>
                                            </a>
                                            @endif
                                            @if(isset($solAddTurma) && count($solAddTurma) > 0)
                                            <a class="dropdown-item" href="{{ route('lista_solicitacao_turma') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Adição de Turmas <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solAddTurma)}}</span>
                                            </a>
                                            @endif
                                        </div>
                                    </li>

                                </ul>
                                @endif

                                @php
                                $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                                @endphp
                                @if(isset($previlegio) && ($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador'))
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Solicitações
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="font-size: 15px;color: black;">
                                            <a class="dropdown-item" href="{{ route('solicitacao_turma.index') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Adição de Turma
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                                @endif
                                <!-- Right Side Of Navbar -->
                                <ul class="navbar-nav ml-auto">
                                    <!-- Authentication Links -->
                                    @guest
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="font-size: 15px;color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Convidado
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;font-size: 15px">
                                            <a class="dropdown-item" href="{{ route('registro_base.index') }}" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Registrar
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('login') }}" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>{{ __('Login') }}</a>
                                        </div>

                                    </li>
                                    @else
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"' class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;font-size: 16px">
                                            <a class="dropdown-item" href="{{ route('alterar_registro.index')}}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Alterar Registro
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" style="font-size: 15px;color: black;" href="{{ route('home.index') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                {{ __('Logout') }}
                                            </a>


                                            <form id="logout-form" action="{{ route('deslogar') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                    @endguest
                                </ul>
                            </div>
                        </div>
                    </nav>
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
    var labelsItem = <?php echo json_encode(array_column($percentuais_ano_graf, 'ano')) ?>;
    var valuesItem = <?php echo json_encode(array_column($percentuais_ano_graf, 'percentual')) ?>;
    const dataAno = {

        labels: labelsItem,
        datasets: [{
            data: valuesItem,
            backgroundColor: ["#2196F3", "#FFC107", "#1976D2", "#FFA000", "#0D47A1", '#ff5606', '#228b22'],
            hoverBackgroundColor: ["#ffffff"]
        }]
    };

    // Append '4d' to the colors (alpha channel), except for the hovered index
    function handleHover(evt, item, legend) {
        legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
            colors[index] = index === item.index || color.length === 9 ? color : color + '4D';
        });
        legend.chart.update();
    }

    // Removes the alpha channel from background colors
    function handleLeave(evt, item, legend) {
        legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
            colors[index] = color.length === 9 ? color.slice(0, -2) : color;
        });
        legend.chart.update();
    }

    // config 
    const config = {
        type: 'pie',
        data: dataAno,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', //'left' 'right' 'bottom'
                    onHover: handleHover,
                    onLeave: handleLeave,
                    hoverBorderWidth: 5,
                    hoverBorderColor: 'green',
                },
                title: {
                    display: true,
                    text: ''
                }
            }
        },
    };

    // render init block
    const ctx = document.getElementById('graficoAno');
    const grafico = new Chart(
        ctx,
        config
    );
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
    const data = {
        datasets: [{
            label: "",
            data: <?php echo json_encode($dados_base_qestoes) ?>,
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
    const configProva = {
        type: 'bar',
        data,
        options: {
            responsive: true,
            parsing: {
                xAxisKey: "numero",
                yAxisKey: "acertos"
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
                    text: 'Acertos por Questão',
                    font: {
                        size: 16,
                        family: 'tahoma',
                        weight: 'normal',
                        style: 'italic'
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
    const ctxProva = document.getElementById('graficoProva');
    Chart.defaults.font.size = 13;
    const graficoProva = new Chart(
        ctxProva,
        configProva
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
    ctx.onclick = clickHandler;
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
    var labelsItemGeralProva = <?php echo json_encode(array_column($dados_base_provas, 'descricao')) ?>;
    var valuesItemGeralProva = <?php echo json_encode(array_column($dados_base_provas, 'qtd_dados')) ?>;
    const dataGeralProva = {

        labels: labelsItemGeralProva,
        datasets: [{
            data: valuesItemGeralProva,
            backgroundColor: ["#2196F3", "#FFC107", "#1976D2", "#FFA000", "#0D47A1", '#ff5606', '#228b22'],
            hoverBackgroundColor: ["#ffffff"]
        }]
    };

    // config 
    const configGeralProva = {
        type: 'pie',
        data: dataGeralProva,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', //'left' 'right' 'bottom'
                    onHover: handleHover,
                    onLeave: handleLeave,
                    hoverBorderWidth: 5,
                    hoverBorderColor: 'green',
                },
                title: {
                    display: true,
                    text: ''
                }
            }
        },
    };

    // render init block
    const ctxGeralProva = document.getElementById('graficoGeralProva');
    const graficoGeralProva = new Chart(
        ctxGeralProva,
        configGeralProva
    );
</script>