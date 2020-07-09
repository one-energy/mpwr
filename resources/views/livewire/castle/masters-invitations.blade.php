<div>
    <x-table class="mt-6" :pagination="$invitations->links()">
        <x-slot name="header">
            <tr>
                <x-table.th>@lang('Email')</x-table.th>
                <x-table.th>@lang('Invited At')</x-table.th>
                <x-table.th></x-table.th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @foreach($invitations as $invite)
                <x-table.tr :loop="$loop">
                    <x-table.td>{{ $invite->email }}</x-table.td>
                    <x-table.td>{{ $invite->created_at->diffForHumans() }}</x-table.td>
                    <x-table.td class="text-right">

                        <x-button color="blue" class="text-sm mr-8" :x-copy="$invite->path()" @click="
                            Utils.copyText($event.target);
                            alert('Copied to the clipboard');
                        ">
                            @lang('Copy Invitation')
                        </x-button>

                        <x-button class="text-sm" :wire:click='"delete($invite->id)"'>
                            @lang('Delete')
                        </x-button>

                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot>
    </x-table>
</div>
