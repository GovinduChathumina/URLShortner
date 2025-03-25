<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            URL Shortener
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <form method="POST" action="{{ route('shorten.handle') }}">
                    @csrf

                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Enter a long URL</label>
                    <input type="url" name="url" id="url" value="{{ old('url') }}" class="w-full p-2 border border-gray-300 rounded mb-4" placeholder="https://example.com" required>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Generate
                    </button>
                </form>

                @if(session('errors'))
                    <div class="text-red-500 mt-4">
                        {{ session('errors')->first('url') }}
                    </div>
                @endif

                @isset($shortUrl)
                    <div class="mt-6">
                        <p class="text-gray-700 mb-1">Original URL:</p>
                        <a href="{{ $originalUrl }}" target="_blank" class="text-blue-600 underline">{{ $originalUrl }}</a>

                        <p class="mt-4 text-gray-700 mb-1">Shortened URL:</p>
                        <div class="flex items-center gap-2">
                            <a id="short-url" href="{{ $shortUrl }}" target="_blank" class="text-green-600 underline">
                                {{ $shortUrl }}
                            </a>
                            <button
                                onclick="copyToClipboard()"
                                class="text-sm px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded"
                            >
                                Copy
                            </button>
                        </div>

                        <div id="copy-msg" class="text-green-500 mt-2 text-sm hidden">
                            ✅ Copied to clipboard!
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const text = document.getElementById("short-url").textContent;
            navigator.clipboard.writeText(text).then(() => {
                const msg = document.getElementById("copy-msg");
                msg.classList.remove("hidden");
                setTimeout(() => msg.classList.add("hidden"), 2000);
            });
        }
    </script>
</x-app-layout>
