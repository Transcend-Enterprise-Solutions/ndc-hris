<div class="w-full">
    <div class="w-full flex justify-center">
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Leave Monetization
            </h1>
            <div>
                <button wire:click="openRequestForm"
                    class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white mb-2">
                    <span
                        class="relative px-4 py-2 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        Request to Monetize Credits
                    </span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                        <tr class="whitespace-nowrap">
                            <th scope="col" class="px-4 py-2 text-center">Requested Credits (VL)</th>
                            <th scope="col" class="px-4 py-2 text-center">Requested Credits (SL)</th>
                            <th scope="col" class="px-4 py-2 text-center">Monetize Credits (VL)</th>
                            <th scope="col" class="px-4 py-2 text-center">Monetize Credits (SL)</th>
                            <th scope="col" class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr class="whitespace-nowrap">
                                <td class="px-4 py-2 text-center">{{ $request->vl_credits_requested }}</td>
                                <td class="px-4 py-2 text-center">{{ $request->sl_credits_requested }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span style="font-family: 'Arial', sans-serif; font-weight: bold;">&#8369;</span>
                                    {{ number_format($request->vl_monetize_credits, 2) }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span style="font-family: 'Arial', sans-serif; font-weight: bold;">&#8369;</span>
                                    {{ number_format($request->sl_monetize_credits, 2) }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span
                                        class="inline-block px-3 py-1 text-sm font-semibold
                                    {{ $request->status === 'Approved'
                                        ? 'text-green-800 bg-green-200'
                                        : ($request->status === 'Disapproved'
                                            ? 'text-red-800 bg-red-200'
                                            : ($request->status === 'Pending'
                                                ? 'text-yellow-800 bg-yellow-200'
                                                : '')) }} rounded-lg">
                                        {{ $request->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    <x-modal maxWidth="lg" wire:model="monetizationForm">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                    Monetization Request
                </h3>
                <button wire:click="closeRequestForm"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form class="space-y-6" wire:submit.prevent="submitMonetizationRequest">
                <!-- Vacation Leave Part -->
                <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0">
                    <legend class="text-gray-700 dark:text-slate-100">Vacation Leave</legend>
                    <div class="flex space-x-4">
                        <!-- Claimable Credits -->
                        <div class="w-full">
                            <label for="vlCredits"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Input Credits</label>
                            <input id="vlCredits" type="number" wire:model="vlCredits"
                                class="w-full p-2 mt-1 border rounded-md text-gray-700 dark:text-gray-300 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter credits">
                            @error('vlCredits')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Sick Leave Part -->
                <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0">
                    <legend class="text-gray-700 dark:text-slate-100">Sick Leave</legend>
                    <div class="flex space-x-4">
                        <!-- Claimable Credits -->
                        <div class="w-full">
                            <label for="slCredits"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Input Credits</label>
                            <input id="slCredits" type="number" wire:model="slCredits"
                                class="w-full p-2 mt-1 border rounded-md text-gray-700 dark:text-gray-300 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter credits">
                            @error('slCredits')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <!-- Save Button -->
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                        Save
                    </button>

                    <!-- Close Button -->
                    <button type="button" wire:click="closeRequestForm"
                        class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

</div>
