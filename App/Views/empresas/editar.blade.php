<form action="/accion.php?a=empresa&b=editar&ID={{$empresa->ID}}" method="post">
    <input type="hidden" name="return" value="{{ $empresa->categoria->url }}/{{ $empresa->url }}" />

    <p class="amarillo">{{ _('Fundador') }}: 
        <b> {!! crear_link($empresa->user->nick) !!}  </b>
        {{ _('el') }} <em> {{ explodear(' ', $empresa->time, 0) }}</em>,
        {{ _('sector') }}: <a href="/empresas/{{ $empresa->categoria->url }}"> {{ $empresa->categoria->nombre }} </a>
    </p>

    <p class="amarillo">
        {!! editor_enriquecido('txt', $empresa->descripcion) !!}
    </p>
</form>
<p><input type="submit" value="{{ _('Guardar') }}" /> &nbsp; <a href="/empresas"><b>{{ _('Ver empresas') }}</b></a></p>


