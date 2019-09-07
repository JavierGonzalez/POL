<p>{!! boton(_('Crear Empresa'), '/empresas/crear-empresa', false, '', $pols_empresa) !!}</p> 

<table border="0" cellspacing="0" cellpadding="2">

@foreach ($categories as  $category)
    <tr class="amarillo">
        <td><a href="/empresas/{{ $category->url }}" style="font-size:19px;"><b> {{ $category->nombre }}</b></a></td>
        <td align="right"><b>{{ $category->num }}</b> {{ _('empresas') }}</td>
        <td align="right"><b>{{ $category->count_visitas() }}</b> {{ _('visitas') }} </td>
    </tr>
    <tr><td colspan="3" height="6"></td></tr>
@endforeach

    
</table>

<p>{!! boton(_('Crear Empresa'), '/empresas/crear-empresa', false, '', $pols_empresa) !!}</p> 