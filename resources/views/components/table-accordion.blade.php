@props(['header', 'body', 'pagination'])

@php
$pagination = $pagination ?? null;
$class = 'table w-full';
if ($pagination) {
    $class .= ' ';
}
@endphp

<div x-data="initAccordion()" {{ $attributes->merge(['class' => $class]) }}>
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
                collapseRow(prefixColapseElementsId, targetId, prefixCollapseSecondElementId = null) {
                    let element = document.getElementById(prefixColapseElementsId);
                    let collapseElements = document.querySelectorAll(`[id^="${prefixColapseElementsId}"]`)
                    let collapseSecondElements = document.querySelectorAll(`[id^="${prefixCollapseSecondElementId}"]`)
                    let targetSvgs = document.querySelectorAll(`#${targetId} > div > div > svg`);

                    collapseElements.forEach(element => {
                        let elementSvgs = document.querySelectorAll(`#${element.id} > div > div > svg`);
                        if (prefixCollapseSecondElementId) {
                            collapseSecondElements.forEach(secondElement => {
                                secondElement.style.display = 'none';
                            });
                            elementSvgs[0].classList.remove('hidden');
                            elementSvgs[0].classList.add('block');
                            elementSvgs[1].classList.remove('block');
                            elementSvgs[1].classList.add('hidden');
                        }
                        if (element.style.display == 'none' || element.style.display == '') {
                            element.style.display = 'table-row';
                            targetSvgs[0].classList.add('block');
                            targetSvgs[0].classList.add('hidden');
                            targetSvgs[1].classList.remove('hidden');
                            targetSvgs[1].classList.add('block');
                        } else {
                            element.style.display = 'none';
                            targetSvgs[0].classList.remove('hidden');
                            targetSvgs[0].classList.add('block');
                            targetSvgs[1].classList.remove('block');
                            targetSvgs[1].classList.add('hidden');
                        }
                    });
                },
                itsOpen(elementId) {
                    element = document.getElementById(elementId);
                    return element.style.display != 'none' && element.style.display != ''
                }
            }
        }

    </script>
@endpush
