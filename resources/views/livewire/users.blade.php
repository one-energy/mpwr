<div>
    <table>
        <thead>
        <tr>
            <td>Name</td>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
