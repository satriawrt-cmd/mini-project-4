@props(['type' => 'ok'])
<div class="alert {{ $type }}">
    {{ $slot }}
</div>
