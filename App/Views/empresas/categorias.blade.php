<table border="0" cellspacing="0" cellpadding="2">

    <tr class="amarillo">
        <td>
            <a href="/empresas/{{ $categoria->url }}"><b>{{ $categoria->nombre }}</b></a>
        </td>
        <td>{{ $categoria->num }}</td>
        <td></td>
    </tr>

    @foreach ($categoria->empresas as $empresa)
    <tr>
        <td align="right">{!! crear_link($empresa->user->nick) !!}</td>
        <td><a href="/empresas/{{ $categoria->url }}/{{ $empresa->url }}"><b>{{ $empresa->nombre }}</b></a></td>
        <td align="right"><b> {{  $empresa->pv }} </b> {{ _('visitas') }} </td>
    </tr>
    @endforeach

</table>

<p><a href="/empresas"><b>{{ _('Ver empresas') }}</b></a></p>