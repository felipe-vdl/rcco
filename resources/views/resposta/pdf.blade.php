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
  position: relative;
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
      <div style="border: 1px solid black;">
        <br>
        <h3 style="text-align:center; color:rgb(66, 66, 66); margin: 0;">RELATÓRIO: {{$unidade->setor->nome}} — {{$unidade->nome}}</h3>
        @if(substr($inicio, 0, 10) === substr($fim, 0, 10))
          <h3 style="text-align:center; color:rgb(66, 66, 66); margin: 0; margin-top: 12px;">RCCO — {{date('d/m/Y', strtotime($inicio))}}</h3>
        @else
          <h3 style="text-align:center; color:rgb(66, 66, 66); margin: 0; margin-top: 12px;">RCCO — {{date('d/m/Y', strtotime($inicio))}} - {{date('d/m/Y', strtotime($fim))}}</h3>
        @endif
        <br>
        <div style="border-bottom: 1px solid black;"></div>
        <br>
        {{-- @if($marcador) <h3 style="text-align:center; color:rgb(66, 66, 66);">MARCADOR: <span style="color:{{$marcador->color}}">{{$marcador->nome}}</span></h3> @endif --}}
        <h3 style="color:rgb(66, 66, 66); margin: 0; margin-left: 13px;">EMITIDO POR:</h3>
        <span style="display:block; margin-left: 23px;">{{$usuario->name}}</span>
        <h3 style="color:rgb(66, 66, 66); margin: 0; margin-left: 13px; margin-top: 12px;">RELATOR (ES): @foreach($relatores as $relator)</h3>
        <span style="display:block; margin-left: 23px;">{{$relator->criador->name}}</span> @endforeach
        @if ($totalDeRelatorios > 1)
          <h3 style="color:rgb(66, 66, 66); margin: 0; margin-left: 13px; margin-top: 12px">TOTAL DE RELATÓRIOS:</h3>
          <span style="display:block; margin-left: 23px;">{{$totalDeRelatorios}}</span>
        @endif
        <br>
      </div>
    </header>
    <main>
      @php $optionsArray = array() @endphp
      @php $perguntasArray = array() @endphp
      @foreach($topicos as $topico)
        @if(count($topico->respostas) > 0)
          <div>
            <h3 style="text-align:center; border: 1px solid black; padding: 5px;">{{$topico->nome}}</h3>
            @foreach($topico->respostas as $resposta)
              {{-- Text String --}}
              @if ($resposta->pergunta->formato === "text" AND $resposta->pergunta->tipo === "string")
                @if (!in_array($resposta->pergunta->id, $perguntasArray))
                  <h4 style="margin-bottom: 5px;">{{$resposta->pergunta->nome}}: </h4>
                  @foreach($topico->respostas as $respostaAdd)
                  <p style="margin: 0; margin-bottom: 10px;">
                    {{date('d/m/Y', strtotime($respostaAdd->data))}} — @if($respostaAdd->valor){{$respostaAdd->valor}}@else <span style="color:red;">Não respondido</span> @endif
                  </p>
                  @endforeach
                  @php array_push($perguntasArray, $resposta->pergunta_id) @endphp
                @endif
              {{-- Text Number --}}
              @elseif ($resposta->pergunta->formato === "text" AND $resposta->pergunta->tipo === "number")
                @if (!in_array($resposta->pergunta->id, $perguntasArray))
                  @php $i = 0 @endphp
                  @foreach($topico->respostas as $respostaAdd)
                    @if($resposta->pergunta_id === $respostaAdd->pergunta_id) @php $i += (int) $resposta->valor @endphp @endif
                  @endforeach
                  <p style="margin: 0; margin-bottom: 10px;"><b>{{$resposta->pergunta->nome}}:</b> {{$i}}</p>
                  @php array_push($perguntasArray, $resposta->pergunta_id) @endphp
                @endif
              {{-- Textarea String --}}
              @elseif ($resposta->pergunta->formato === "textarea" AND $resposta->pergunta->tipo === "string")
                @if (!in_array($resposta->pergunta->id, $perguntasArray))
                  <h4 style="margin-bottom: 5px;">{{$resposta->pergunta->nome}}: </h4>
                  @foreach($topico->respostas as $respostaAdd)
                    @if($respostaAdd->pergunta_id === $resposta->pergunta_id)
                      <p style="margin: 0; margin-bottom: 10px;">
                        {{date('d/m/Y', strtotime($respostaAdd->data))}} — @if($respostaAdd->valor){{$respostaAdd->valor}}@else <span style="color:red;">Não respondido</span> @endif
                      </p>
                    @endif
                  @endforeach
                  @php array_push($perguntasArray, $resposta->pergunta_id) @endphp
                @endif
              {{-- Textarea Number --}}
              @elseif ($resposta->pergunta->formato === "textarea" AND $resposta->pergunta->tipo === "number")
                @if (!in_array($resposta->pergunta->id, $perguntasArray))
                  @php $i = 0 @endphp
                  @foreach($topico->respostas as $respostaAdd)
                    @if($resposta->pergunta_id === $respostaAdd->pergunta_id) @php $i += (int) $resposta->valor @endphp @endif
                  @endforeach
                  <p style="margin: 0; margin-bottom: 10px;"><b>{{$resposta->pergunta->nome}}:</b> {{$i}}</p>
                  @php array_push($perguntasArray, $resposta->pergunta_id) @endphp
                @endif
              {{-- Dropdown --}}
              @elseif ($resposta->pergunta->formato === "dropdown")
                @if (!in_array($resposta->pergunta->id, $perguntasArray))
                  <h4 style="margin-bottom: 5px;">{{$resposta->pergunta->nome}}: </h4>
                  @foreach($resposta->pergunta->label_options as $option)
                    @php $i = 0 @endphp
                    @foreach($topico->respostas as $respostaAdd)
                      @if($resposta->pergunta_id === $respostaAdd->pergunta_id AND $respostaAdd->valor === $option->nome) @php $i += 1 @endphp @endif
                    @endforeach
                    <span style="margin-left: 13px; color:rgb(66, 66, 66); display: block;"><b>{{$option->nome}}:</b> {{$i}}</span>
                  @endforeach
                  @php array_push($perguntasArray, $resposta->pergunta_id) @endphp
                @endif
              {{-- Radio --}}
              @elseif ($resposta->pergunta->formato === "radio")
                @if (!in_array($resposta->pergunta->id, $perguntasArray))
                  <h4 style="margin-bottom: 5px;">{{$resposta->pergunta->nome}}: </h4>
                  @foreach($resposta->pergunta->label_options as $option)
                    @php $i = 0 @endphp
                    @foreach($topico->respostas as $respostaAdd)
                      @if($resposta->pergunta_id === $respostaAdd->pergunta_id AND $respostaAdd->valor === $option->nome) @php $i += 1 @endphp @endif
                    @endforeach
                    <span style="margin-left: 13px; color:rgb(66, 66, 66); display: block;"><b>{{$option->nome}}:</b> {{$i}}</span>
                  @endforeach
                  @php array_push($perguntasArray, $resposta->pergunta_id) @endphp
                @endif
              {{-- Checkboxes --}}
              @elseif ($resposta->pergunta->formato === "checkbox")
                @if (!in_array($resposta->pergunta->id, $perguntasArray))
                  <p style="margin: 0; margin-bottom: 10px;">
                    <h4 style="margin-bottom: 5px;">{{$resposta->pergunta->nome}}: </h4>
                    @foreach($resposta->label_valors as $label)
                      @if (!in_array($label->label_option_id, $optionsArray))
                        @php $i = 0 @endphp
                        @foreach($topico->respostas as $respostaAdd)
                          @foreach($respostaAdd->label_valors as $labelAdd)
                            @if($labelAdd->label_option_id === $label->label_option_id) @php $i += $labelAdd->valor @endphp @endif
                          @endforeach
                        @endforeach
                        <span style="margin-left: 13px; color:rgb(66, 66, 66); display: block;"><b>{{$label->label_option->nome}}:</b> {{$i}}</span>
                        @php array_push($optionsArray, $label->label_option_id) @endphp
                      @endif
                    @endforeach
                  </p><br>
                  @php array_push($perguntasArray, $resposta->pergunta_id) @endphp
                @endif
              @endif
            @endforeach
          </div>
        @endif
      @endforeach
    </main>
    <footer id="footer" class="page-footer"><div class="page-number"></div></footer>
	</body>
</html>