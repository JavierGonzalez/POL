<br /><div class="amarillo">{!! html_entity_decode($empresa->descripcion,ENT_COMPAT , 'UTF-8') !!}</div>

<p class="azul">{{ _('Fundador') }}: <b> {!! crear_link($empresa->user->nick ) !!}</b> | {{ _('creación') }}: <em> {{ explodear(" ", $empresa->time, 0) }} </em> | {{ _('sector') }} : <a href="/empresas/{{ $empresa->categoria->url }} ">{{ $empresa->categoria->nombre }}</a> | {{ _('visitas') }}: {{ $empresa->pv }}</p>

@if ($empresa->user->ID == $pol['user_ID'])
<table width="100%">
    <tr>
 	    <td>
            <form action="/accion.php?a=empresa&b=acciones&ID={{ $empresa->ID }}" method="post">
            {{ _('Ceder acciones a') }}: <input type="text" name="nick" size="8" maxlength="20" value="" /> <br>
            {{ _('Cantidad de acciones a') }}: <input type="text" name="cantidad" size="8" maxlength="3" value="" /> <br>
            {!! boton( _('Ceder'), 'submit', false, 'small' )  !!} [ {{ _('En desarrollo') }} ]
            </form>
        </td>

        <td align="right">
            <form action="/accion.php?a=empresa&b=ceder&ID={{ $empresa->ID }}" method="post">
                {!! boton( _('Ceder a'), 'submit', false, 'small') !!}
                <input type="text" name="nick" size="8" maxlength="20" value="" />
            </form>

            {!! boton('X', "/accion.php?a=empresa&b=eliminar&ID=$empresa->ID", '¿Estas seguro de querer ELIMINAR definitivamente esta empresa?', 'red') !!} 

            {!! boton(_('Editar'), "/empresas/editar/$empresa->ID") !!}
        </td>

    </tr>
</table>
@endif