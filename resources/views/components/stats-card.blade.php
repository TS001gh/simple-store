{{-- we can use this instead of passing the data from the component --}}
{{-- @props(['color', 'title', 'count', 'percentage', 'icon']) --}}

<div class="col-md-6 mb-4">
    <div @class(['card', 'text-white', 'h-100', "bg-$color" => $color])>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-right fs-1">{{ $title }}</h6>
                    <h2 class="text-right">{{ $count }}</h2>
                    @if ($percentage)
                        <strong class="d-block text-right fs-4 text-decoration-underline">
                            {{ $percentage }} من الإجمالي
                        </strong>
                    @endif
                </div>
                @if ($icon)
                    <div class="text-right fs-1">
                        <i @class(['la', $icon, 'fa-3x', 'opacity-50'])></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
