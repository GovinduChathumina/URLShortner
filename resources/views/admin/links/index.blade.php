<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Manage Shortened URLs</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" class="mb-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search links..." class="p-2 border border-gray-300 rounded w-full sm:w-1/2">
        </form>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm table-auto">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Original URL</th>
                        <th class="p-3">Short URL</th>
                        <th class="p-3">Created</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($links as $link)
                        <tr class="border-t">
                            <td class="p-3">{{ $link->id }}</td>
                            <td class="p-3 break-words">{{ $link->original_url }}</td>
                            <td class="p-3 text-blue-600">
                                <a href="{{ url($link->short_code) }}" target="_blank">{{ url($link->short_code) }}</a>
                            </td>
                            <td class="p-3">{{ $link->created_at->diffForHumans() }}</td>
                            <td class="p-3">
                                <form method="POST" action="{{ route('admin.links.destroy', $link->id) }}" onsubmit="return confirm('Delete this link?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">No links found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $links->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
