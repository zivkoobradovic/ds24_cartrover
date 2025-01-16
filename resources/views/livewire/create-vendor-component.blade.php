<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-10 divide-y divide-gray-900/10">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
                    <div class="px-4 sm:px-0">
                        <h2 class="text-base/7 font-semibold text-gray-900">Create a Vendor</h2>
                        <p class="mt-1 text-sm/6 text-gray-600">Please use the Digistore24 ID</p>
                    </div>

                    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                        <div class="px-4 py-6 sm:p-8">

                            <div class="grid max-w-full grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                                <div class="sm:col-span-3">
                                    <label for="vendor_name" class="block text-sm/6 font-medium text-gray-900">DS24 Vendor ID</label>
                                    <div class="mt-2">
                                        <input wire:model.live='vendor_name' type="text" name="vendor_name" id="vendor_name"
                                            autocomplete="name"
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                            @if ($errors->any()) <span
                                class="text-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm bg-red-400 mr-auto w-full">
                                @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                                @endforeach
                            </span>
                            @endif
                            @if (session()->has('success'))
                            <div x-data="{ visible: true }" x-show="visible"
                                x-init="setTimeout(() => visible = false, 5000)" @click="visible = false"
                                class="rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm bg-green-300 mr-auto w-full transition ease-in-out duration-500 transform"
                                x-transition:enter="opacity-0 scale-90" x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="opacity-100 scale-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm bg-green-300 mr-auto w-full">
                                <div class="text-gray-900 text-center">
                                    {{ session('success') }}
                                </div>
                            </div>
                            @endif
                            <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
                            <button type="submit" wire:click="createVendor"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
