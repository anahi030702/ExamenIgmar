<style>
    .pagination {
    display: flex;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 5px;
}

.pagination li a, .pagination li span {
    display: block;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
}

.pagination li a:hover {
    background-color: #f0f0f0;
}

.pagination li.active span {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.pagination li.disabled span {
    color: #ccc;
}

</style>
@if ($paginator->hasPages())
    <div class="btn-group" role="group" aria-label="Paginación">
        {{-- Botón de Anterior --}}
        @if ($paginator->onFirstPage())
            <button type="button" class="btn btn-primary disabled" aria-disabled="true">Anterior</button>
        @else
            <button type="submit" class="btn btn-primary" name="page" value="{{ $paginator->currentPage() - 1 }}" aria-label="Previous">Anterior</button>
        @endif

        {{-- Enlaces de Paginación --}}
        @foreach ($elements as $element)
            {{-- Tres Puntos --}}
            @if (is_string($element))
                <button type="button" class="btn btn-primary disabled" aria-disabled="true">{{ $element }}</button>
            @endif

            {{-- Arreglo de Páginas --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button type="button" class="btn btn-primary active" aria-current="page">{{ $page }}</button>
                    @else
                        <button type="submit" class="btn btn-primary" name="page" value="{{ $page }}">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Botón de Siguiente --}}
        @if ($paginator->hasMorePages())
            <button type="submit" class="btn btn-primary" name="page" value="{{ $paginator->currentPage() + 1 }}" aria-label="Next">Siguiente</button>
        @else
            <button type="button" class="btn btn-primary disabled" aria-disabled="true">Siguiente</button>
        @endif
    </div>
@endif

