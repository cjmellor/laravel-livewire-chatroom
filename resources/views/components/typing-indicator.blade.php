@props([
    'color' => 'bg-gray-400',
    'height' => 'h-2',
    'width' => 'w-2',
])

<div {{ $attributes }}>
    <div class="{{ $color }} {{ $height }} {{ $width }} rounded-full inline-block animate-bounce-delay !animate-delay"></div>
    <div class="{{ $color }} {{ $height }} {{ $width }} rounded-full inline-block animate-bounce-delay"></div>
    <div class="{{ $color }} {{ $height }} {{ $width }} rounded-full inline-block animate-bounce-delay"></div>
</div>
