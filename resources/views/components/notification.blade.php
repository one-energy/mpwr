<div class="mt-5 bg-green-50 px-3 py-4 rounded text-green-dark text-sm">
    <div class="flex">
        <div>
            <x-icon :icon="$icon"
                    class="h-6 w-6 text-green-base mr-2"></x-icon>
        </div>
        <div class="self-center">
            {{ $description }}

            @if($hasDecision)
                <div class="mt-4">
                    <div class="-mx-2 -my-1.5 flex">

                        <x-form :route="$decisionUrl">
                            <input type="hidden" name="response" value="1"/>
                            <input type="hidden" name="notification" value="{{ $id }}"/>
                            <button type="submit"
                                    class="px-2 py-1.5 rounded-md text-sm leading-5 font-medium text-green-dark hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150">
                                I Accept!
                            </button>
                        </x-form>

                        <x-form :route="$decisionUrl">
                            <input type="hidden" name="response" value="0"/>
                            <input type="hidden" name="notification" value="{{ $id }}"/>
                            <button type="submit"
                                    class="ml-3 px-2 py-1.5 rounded-md text-sm leading-5 font-medium text-green-dark hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150">
                                No, thank you!
                            </button>
                        </x-form>
                    </div>
                </div>
            @endif
        </div>
        @if(!$hasDecision)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button wire:click="markAsRead('{{ $id }}')"
                            class="inline-flex rounded-md p-1.5 text-green-base hover:bg-green-100 focus:outline-none focus:bg-green-100 transition ease-in-out duration-150">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
