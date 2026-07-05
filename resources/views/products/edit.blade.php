<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Barang') }}
        </h2>
    </x-slot>

    <div class="flex w-full min-h-screen bg-gray-50">

        @include('partials.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white border-b border-gray-200 px-8 py-4">
                <h1 class="text-xl font-bold text-gray-800">Edit Barang: {{ $product->name }}</h1>
            </header>

            <div class="p-8 overflow-y-auto flex-1">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 max-w-3xl">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @include('products._form')
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>