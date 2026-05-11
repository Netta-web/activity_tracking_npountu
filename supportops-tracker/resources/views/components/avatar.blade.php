{{--
    <x-avatar name="Alice Mensah" size="lg" />
    <x-avatar name="Bob Asante"   size="xl" class="ring-2 ring-white" />

    Sizes:  xs(16px)  sm(20px)  md(24px)  lg(32px)  xl(36px)  2xl(48px)

    Extra classes (ring, margin, etc.) flow through $attributes.
--}}
@props(['name' => 'U', 'size' => 'md'])

@php
    $initial = strtoupper(substr(trim($name), 0, 1)) ?: 'U';
    $sizeClass = match($size) {
        'xs'  => 'ui-av-xs',
        'sm'  => 'ui-av-sm',
        'md'  => 'ui-av-md',
        'lg'  => 'ui-av-lg',
        'xl'  => 'ui-av-xl',
        '2xl' => 'ui-av-2xl',
        default => 'ui-av-md',
    };
@endphp

{{-- Inject the stylesheet exactly once per page, no matter how many avatars render --}}
@once
@push('styles')
<style>
/* ── Avatar component — server-rendered, Tailwind-CDN-independent ── */
.ui-av {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;           /* never compress inside flex parents      */
    border-radius: 9999px;    /* perfect circle                          */
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%);
    line-height: 1;           /* text never adds hidden height           */
    aspect-ratio: 1 / 1;     /* guarantees square even if parent fights */
    user-select: none;
    -webkit-user-select: none;
    vertical-align: middle;
    letter-spacing: 0;
}
/* Explicit pixel dimensions so flex/grid cannot stretch or squash them */
.ui-av-xs  { width: 1rem;    height: 1rem;    min-width: 1rem;    font-size: .5rem;    }
.ui-av-sm  { width: 1.25rem; height: 1.25rem; min-width: 1.25rem; font-size: .5625rem; }
.ui-av-md  { width: 1.5rem;  height: 1.5rem;  min-width: 1.5rem;  font-size: .625rem;  }
.ui-av-lg  { width: 2rem;    height: 2rem;    min-width: 2rem;    font-size: .75rem;   }
.ui-av-xl  { width: 2.25rem; height: 2.25rem; min-width: 2.25rem; font-size: .75rem;   }
.ui-av-2xl { width: 3rem;    height: 3rem;    min-width: 3rem;    font-size: 1rem;     }
</style>
@endpush
@endonce

<div {{ $attributes->merge(['class' => "ui-av {$sizeClass}"]) }} aria-hidden="true">{{ $initial }}</div>
