
<form action="/accion.php?a=empresa&b=crear" method="post">

<p>{{ _('Sector') }}: 
    <select name="cat">
        @foreach ($categories as $categoria)
            <option value="{{ $categoria->ID }}">{{ $categoria->nombre }}</option>
        @endforeach
    </select> ({{ _('No modificable') }})
</p>

<p>{{ _('Nombre') }}: <input type="text" name="nombre" size="20" maxlength="20" /> ({{ _('No modificable') }})</p>

<p>{!! boton('Crear Empresa', false, false, '', $pols_empresa) !!}</p>

<p><a href="/empresas"><b>{{ _('Ver empresas') }}</b></a></p>

</form>