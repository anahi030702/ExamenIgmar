<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="text-align: center;">
    <h1>{{$name_ev}}</h1>

    <table style="text-align: center; margin-left: 140px;">
  <thead>
    <tr>
      <th>Numero 1</th>
      <th>Operador</th>
      <th>Numero 2</th>
      <th>Respuesta</th>
      <th>Resultado correcto</th>
    </tr>
  </thead>
  <tbody style="color: white;">
    @foreach ($preguntas as $pregunta)
    @if($pregunta->res_final == 1)
    <tr style="background-color: green;">
    @else
    <tr style="background-color: red;">
    @endif
      <td>{{$pregunta->num1}}</td>
      <td>{{$pregunta->ope}}</td>
      <td>{{$pregunta->num2}}</td>
      <td>{{$pregunta->res_usu}}</td>
      <td>{{ $pregunta->res_final == 1 ? "" :$pregunta->res}}</td>
    </tr>
    @endforeach
  </tbody>
</table>

</body>
</html>