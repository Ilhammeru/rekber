<div class="card tiny-card cursor-pointer">
    <div class="card-body position-relative">
        <div class="d-flex align-items-center gap-4">
            <!-- Icon -->
            <div class="box-icon {{ $colorIcon }}">
                {{-- <i class="{{ $icon }}"></i> --}}
                <img src="{{ asset('images/' . $icon) }}" alt="">
            </div> <!-- End Icon -->

            <!-- Text -->
            <div class="text-group">
                <p class="mb-0 fs-14 {{ $headingColor }}" @if($idHeading) id="{{ $idHeading }}" @endif>
                    {{ $heading }}
                </p>
                <p class="mb-0 fs-12 {{ $textColor }}">{{ $text }}</p>
            </div>
        </div>

        <div class="position-absolute action-card @if($actionLink) onclick="{{ $actionLink }}" @endif">
            {{ $actionSlot ?? null }}
        </div>
    </div>
</div>
