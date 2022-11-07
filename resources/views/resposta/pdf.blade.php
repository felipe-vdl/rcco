<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

<title>Relatório</title>
<style type="text/css">
@page {
	margin: 0.5cm;
	margin-top: 50px; 
	margin-bottom: 110px;
}
body {
    font-family: sans-serif;
    font-size:15px;
	 margin: 2.5cm 0;
	 text-align: justify;
}
#header { 
	position: fixed; 
	top: -30px; 
	left: 0px; 
	right: 0px;  
	height: 50px; }
#footer {
	position: fixed;
	left: 0;
	right: 0;
	color: #000000;
	font-size: 0.9em;
}
#footer {
  bottom: -20px;
}
#header table {
}

table {
  font-family: arial, sans-serif;
  font-size: 12px;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 5px;
}
/* #footer table {
	width: 100%;
	border-collapse: collapse;
	border: none;
} */
#header td {
}
/* #footer td {
  padding: 0;
	width: 10%;
} */
.page-footer {
  text-align: center;
}
hr {
  page-break-after: always;
  border: 0;
}
/* table.separate {
  border-collapse: separate;
  border-spacing: 20pt;
  
} */
/* td{
    padding: 4px;
} */
.container{
	 justify-content: flex-start;
}
.semsopimagem {
  margin-top: 1cm;
  height: 260px !important;
  width: 260px !important;
}
.Imangemsemsop{
  margin: 0 auto !important;
}
.page-number {
  text-align: center;
}
.page-number:before {
  content: "Página " counter(page);
}
#watermark { position: fixed; bottom: 150px; width: 650px; height: 600px; opacity: .1; }
</style>
</head>
	<body>
    <header>
      <div id="watermark"><img src="{{ asset("img/lgo2.png") }}" height="120%" width="110%"></div>
      <div id="header">
        <table>
          <tr>
            <center><img src="{{ asset("img/logo.png") }}" height="250%" width="50%"/></center>
          </tr>
        </table>
      </div>
      <br>
      <div style="border: 1px solid black; padding: 5px;">
        <h3 style="text-align:center; color:rgb(66, 66, 66);">RELATÓRIO: {{$unidade->nome}}</h3>
        <h3 style="text-align:center; color:rgb(66, 66, 66);">RCCO — {{date('d/m/Y', strtotime($data))}}</h3>
        @if($marcador) <h3 style="text-align:center; color:rgb(66, 66, 66);">MARCADOR: <span style="color:{{$marcador->color}}">{{$marcador->nome}}</span></h3> @endif
      </div>
    </header>
    <main>
      @foreach($topicos as $topico)
        @if(count($topico->respostas) > 0)
          <div>
            <h3 style="text-align:center; border: 1px solid black; padding: 5px;">{{$topico->nome}}</h3>
            @foreach($topico->respostas as $resposta)
              @if ($resposta->pergunta->formato === "text")
              <p>
                <h4 style="display:inline;">{{$resposta->pergunta->nome}}: </h4>@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif
              </p>
              @elseif ($resposta->pergunta->formato === "textarea")
                <p>
                  <h4>{{$resposta->pergunta->nome}}: </h4>
                  @if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif
                </p>
              @elseif ($resposta->pergunta->formato === "dropdown")
                <p>
                  <h4 style="display:inline;">{{$resposta->pergunta->nome}}: </h4>@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif
                </p>
              @elseif ($resposta->pergunta->formato === "radio")
                <p>
                  <h4 style="display:inline;">{{$resposta->pergunta->nome}}: </h4>@if($resposta->valor){{$resposta->valor}}@else <span style="color:red;">Não respondido</span> @endif
                </p>
              @elseif ($resposta->pergunta->formato === "checkbox")
                <p>
                  <h4>{{$resposta->pergunta->nome}}: </h4>
                  @foreach($resposta->label_valors as $label)
                  <span style="margin: 100px;"><b>{{$label->label_option->nome}}:</b> {{$label->valor ? 'Sim' : 'Não'}}</span>
                  @endforeach
                </p><br>
              @endif
            @endforeach
          </div>
        @endif
      @endforeach
    </main>
    <footer id="footer" class="page-footer"><div class="page-number"></div></footer>
	</body>
</html>