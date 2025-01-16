<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-10 divide-y divide-gray-900/10">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
                    <div class="px-4 sm:px-0">
                        {{-- @if ($ds24_user)
                        {{dd($ds24_user)}}
                        @endif --}}

                        @if (!empty($products->products))
                        {{-- {{ json_encode($products) }} --}}
                        @php
                        // Grupisanje proizvoda po 'product_group_name'
                        $groupedProducts = collect($products->products)->groupBy('product_group_name');
                        @endphp
                        <div class="text-2xl font-semibold text-indigo-600 mb-5 pb-5">
                            {{$ds24_user->user_name}}
                        </div>
                        <div class="space-y-8 border rounded-lg shadow-sm p-3">
                            @foreach ($groupedProducts as $groupName => $products)
                            <div x-data="{ open: false }" class="border-b pb-4">
                                <!-- Naslov grupe proizvoda -->
                                <button @click="open = !open"
                                    class="flex items-center justify-between w-full text-lg font-semibold text-gray-800 focus:outline-none">
                                    <span>{{ $groupName ? $groupName : 'Without Group' }}</span>
                                    <svg :class="open ? 'transform rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        class="w-5 h-5 transition-transform">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Proizvodi u grupi -->
                                <div x-show="open" x-collapse class="mt-4 space-y-4">
                                    @foreach ($products as $product)
                                    <div
                                        class="flex items-center gap-4 p-4 border rounded-lg shadow-sm hover:bg-gray-100 cursor-pointer">
                                        <input wire:model.live="selected_product_ids" type="checkbox"
                                            name="selected_product_ids[]" value="{{ $product->id }}"
                                            wire:key="{{ $product->id }}"
                                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            {{$allFieldsDisabled ? 'disabled' : '' }}>

                                        <!-- Thumbnail slika -->
                                        @if (!empty($product->image_url))
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                            class="w-16 h-16 object-cover rounded">
                                        @else
                                        <div
                                            class="w-16 h-16 flex items-center justify-center bg-gray-200 text-gray-500 rounded">
                                            No Image
                                        </div>
                                        @endif

                                        <!-- Informacije o proizvodu -->
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $product->id }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                        <div class="px-4 py-6 sm:p-8">

                            <div class=" max-w-full ">

                                <div class="col-span-full">
                                    <label for="street-address"
                                        class="block text-sm/6 font-medium {{$ds24_user ? 'text-green-500' : 'text-gray-900'}} ">Digistore24
                                        API Key</label>
                                    <div class="mt-2">
                                        <input wire:model.live='ds24_api_key' type="text" name="ds24_api_key"
                                            id="street-address" autocomplete="street-address"
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                            {{$allFieldsDisabled ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="col-span-full">
                                    <label for="street-address"
                                        class="block text-sm/6 font-medium text-gray-900">Integration Name</label>
                                    <div class="mt-2">
                                        <input wire:model.live='name' type="text" name="name" id="street-address"
                                            autocomplete=""
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                            {{$allFieldsDisabled ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="col-span-full">
                                    <label for="street-address" class="block text-sm/6 font-medium text-gray-900">IPN
                                        Password</label>
                                    <div class="mt-2">
                                        <input wire:model.live='ipn_pass' type="text" name="ipn_pass" id="ipn_pass"
                                            autocomplete=""
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                            {{$allFieldsDisabled ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="col-span-full">
                                    <label for="street-address" class="block text-sm/6 font-medium text-gray-900">HTTP
                                        Header</label>
                                    <div class="mt-2">
                                        <input wire:model.live='http_header' type="text" name="http_header"
                                            id="ipn_pass" autocomplete=""
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                            {{$allFieldsDisabled ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="mt-10">
                                    <div class="text-lg font-semibold text-indigo-600 mb-5 pb-5 border-b">
                                        Selected Products: {{ count($selected_product_ids) }}
                                    </div>
                                    <div>
                                        @if (!empty($selected_product_ids))
                                        {{-- {{json_encode($selected_product_ids)}} --}}
                                        <ul>
                                            @foreach($selected_product_ids as $productId)
                                            <li class="flex items-center justify-between mb-3 hover:bg-indigo-200 rounded-lg p-2"
                                                wire:key="{{ $productId }}">
                                                <div>
                                                    Product ID: {{ $productId }}
                                                </div>
                                                <button wire:click="removeProduct({{ $productId }})"
                                                    class="rounded-lg hover:bg-red-600 bg-red-400 p-1 text-white"
                                                    {{$allFieldsDisabled ? 'disabled' : '' }}>Remove</button>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <div class="sm:col-span-full sm:col-start-1">
                                        <label for="cr_api_user"
                                            class="block text-sm/6 font-medium text-gray-900">Cartrover
                                            API Username</label>
                                        <div class="mt-2">
                                            <input wire:model.live='cr_api_user' type="text" name="cr_api_user"
                                                id="city" autocomplete="address-level2"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                {{$allFieldsDisabled ? 'disabled' : '' }}>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-full">
                                        <label for="cr_api_pass"
                                            class="block text-sm/6 font-medium text-gray-900">Cartrover
                                            API Password</label>
                                        <div class="mt-2">
                                            <input wire:model.live='cr_api_pass' type="text" name="cr_api_pass"
                                                id="region" autocomplete="address-level1"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                {{$allFieldsDisabled ? 'disabled' : '' }}>
                                        </div>
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
                            @elseif (session()->has('connection-error'))
                            <div x-data="{ visible: true }" x-show="visible"
                                x-init="setTimeout(() => visible = false, 5000)" @click="visible = false"
                                class="rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm bg-red-300 mr-auto w-full transition ease-in-out duration-500 transform"
                                x-transition:enter="opacity-0 scale-90" x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="opacity-100 scale-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm bg-green-300 mr-auto w-full">
                                <div class="text-gray-900 text-center">
                                    {{ session('connection-error') }}
                                </div>
                            </div>
                            @endif
                            {{-- <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
                            --}}

                            @if ($connect_button)
                            <button type="submit" wire:click="connectToDigistore24"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                {{$allFieldsDisabled ? 'disabled' : '' }}>Connect
                                to Digistore24</button>
                            @endif

                            @if ($cr_api_user && $cr_api_pass && !$saved_integration)
                            <button type="submit" wire:click="saveIntegration"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                {{$allFieldsDisabled ? 'disabled' : '' }}>Save
                                Integration</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($saved_integration)
        <div x-data="{
            displayModal: @entangle('displayModal'),
            showNotification: false,
            copyAllText() {
            const texts = [
                'Integration Name: {{$saved_integration->name}}',
                'DS24 ID: {{$saved_integration->vendor->name}}',
                'IPN Password: {{$saved_integration->ipn_pass}}',
                'IPN Custom HTTP header: {{$saved_integration->http_header}}',
                'Included products: {{implode(',', $saved_integration->products)}}',
                'IPN URL: {{$saved_integration->ipn_url}}'
            ];
            const clipboardText = texts.join('\n');

            if (navigator.clipboard) {
                // Modern browser method
                navigator.clipboard.writeText(clipboardText).then(() => {
                    this.showNotification = true;
                    setTimeout(() => this.showNotification = false, 3000);
                }).catch(() => alert('Failed to copy text.'));

            } else {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = clipboardText;
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    this.showNotification = true;
                    setTimeout(() => this.showNotification = false, 3000);

                } catch (err) {
                    alert('Failed to copy text.');
                }
                document.body.removeChild(textarea);
            }
        }
    }" }">
            <!-- Modal -->
            <div x-show="displayModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative" @click.away="$wire.closeModal">
                    <!-- Close button -->
                    <button @click="displayModal = false"
                        class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                        &times;
                    </button>

                    <!-- Modal Header -->
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Integration Saved</h2>

                    <!-- Modal Content -->
                    <div class="space-y-4">
                        <div class="bg-gray-100 p-4 rounded">
                            {{$saved_integration->name}}
                            <small class="text-gray-500 block mt-1">Add this name to the IPN Generic connection
                                name</small>
                        </div>
                        <div class="bg-gray-100 p-4 rounded">
                            {{$saved_integration->vendor->name}}
                            <small class="text-gray-500 block mt-1">DS24 Vendor ID</small>
                        </div>
                        <div class="bg-gray-100 p-4 rounded">
                            {{$saved_integration->ipn_pass}}
                            <small class="text-gray-500 block mt-1">Use this password for the IPN password in DS24
                                Generic connection</small>
                        </div>
                        <div class="bg-gray-100 p-4 rounded">
                            {{$saved_integration->http_header}}
                            <small class="text-gray-500 block mt-1">Use this for the Generic IPN Custom HTTP
                                header</small>
                        </div>
                        <div class="bg-gray-100 p-4 rounded">
                            {{implode(',', $saved_integration->products)}}
                            <small class="text-gray-500 block mt-1">Check these products in the IPN Generic
                                connection</small>
                        </div>
                        <div class="bg-gray-100 p-4 rounded">
                            {{$saved_integration->ipn_url}}
                            <small class="text-gray-500 block mt-1">Use this URL for the IPN URL in the IPN Generic
                                connection</small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-end space-x-2">
                        <button @click="$wire.closeModal"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-indigo-700">
                            Close
                        </button>
                        <!-- Copy All Button -->
                        <button @click="copyAllText, $wire.closeModal"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-indigo-700">
                            Copy All
                        </button>
                    </div>


                    <!-- Notification Modal -->
                    <div x-show="showNotification" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
                        <div class="bg-green-200 text-green-800 px-6 py-3 rounded shadow-lg max-w-sm">
                            <p class="font-semibold text-center">All texts copied to clipboard!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
