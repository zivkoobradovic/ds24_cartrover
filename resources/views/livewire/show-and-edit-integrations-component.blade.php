<div class="px-4 sm:px-6 lg:px-8">

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-indigo-100">
                        <tr class="">
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                               <span class="ml-2">Integration Name</span>
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                ID
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Vendor ID
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Orders
                                Created</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Errors
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last Error
                            </th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last
                                Tracking Cron
                            </th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($integrations as $integration)
                        <tr>
                            <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $integration->name }}</div>
                                        {{-- <div class="mt-1 text-gray-500">lindsay.walton@example.com</div> --}}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900">{{ $integration->id }}</div>
                                        {{-- <div class="mt-1 text-gray-500">lindsay.walton@example.com</div> --}}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                <div class="text-gray-900">{{ $integration->vendor->name }}</div>
                                {{-- <div class="mt-1 text-gray-500">Optimization</div> --}}
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                <span
                                    class="{{ $integration->active ? 'text-green-700 ring-1 ring-inset ring-green-600/20 bg-green-50' : 'text-red-400 ring-1 ring-inset ring-red-600/20 bg-red-50' }} inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ">{{
                                    $integration->active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                <div>
                                    <div>
                                        {{$integration->created_at->diffForHumans()}}
                                    </div>
                                    <div class="text-gray-500 text-xs">
                                        {{$integration->created_at->format('d/m/Y (h:m:s)')}}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                34254245
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">65</td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">1/1/2025</td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">cron date</td>
                            <td
                                class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <button class="text-white bg-indigo-500 hover:bg-indigo-700 py-2 px-5 mr-5 rounded-xl"
                                    wire:key="{{$integration->id}}">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
