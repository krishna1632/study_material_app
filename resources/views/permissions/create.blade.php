<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Permission/Create
            </h2>
            <a
                href="{{ route('permissions.index') }}"class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="" class ="text-lg text-black font-medium">Name</label>
                            <div class="my-3">
                                <input value="{{ old('name') }}" placeholder="Enter Name" type="text"
                                    name="name" class="border-gray-300 shadow-sm w-1/2 rounded-lg text-black">
                                @error('name')
                                    <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <button class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
