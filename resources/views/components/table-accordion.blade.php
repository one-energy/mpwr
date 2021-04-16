@props(['header', 'body', 'pagination'])

@php
    $pagination = $pagination ?? null;
    $class = 'table w-full';
    if($pagination) {
        $class .= ' ';
    }
@endphp

<div x-data="initAccordion()"  {{ $attributes->merge(['class' => $class]) }} >
    <x-table-accordion.tr-th>
        {{ $header }}
    </x-table-accordion.tr-th>
    <div class="table-row-group">
        {{ $body }}
    </div>
    {!! $pagination !!}
</div>


@push('scripts')
    <script>
        function initAccordion() {
           return {
               collapseRow(elementId) {
                   element = document.getElementById(elementId);
                   console.log(element.style);
                   if (element.style.display == 'none') {
                        element.style.display = 'table-row'
                   } else {
                        element.style.display = 'none'
                   }
               }
           }
        }
    </script>
@endpush
