<div class="app-empty" v-if="!items.length">
    <div class="icon">
        <i class="fa fa-{{ $icon or 'info-circle' }} fa-2x" aria-hidden="true"></i>
    </div>
    <div class="text">
        {{ $slot }}
    </div>
</div>
