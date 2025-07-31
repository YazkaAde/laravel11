<div>
    @props(['errors'])

    @if ($errors->any())
    <div {{ $attributes->merge(['class' => 'bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-2 rounded']) }} role="alert">
        <div class="font-bold mb-1">Oops! There were some errors:</div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>