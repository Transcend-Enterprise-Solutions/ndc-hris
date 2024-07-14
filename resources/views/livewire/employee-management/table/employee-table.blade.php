<div class="p-10 flex justify-center w-full">
    <div class="w-full">
        <div class="inline-block w-full">
            <div class="overflow-hidden border rounded-lg border-neutral-500 dark:border-neutral-200">
                <table class="divide-y divide-neutral-500 dark:divide-neutral-200 w-full">
                    <thead class="text-neutral-500 dark:text-neutral-200">
                        <tr class="text-neutral-800 dark:text-neutral-200">
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/5">Name</th>
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/10">Sex</th>
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/5">Citizenship</th>
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/10">Civil Status</th>
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/10">Mobile Number</th>
                            <th class="px-5 py-3 text-sm font-medium text-right uppercase w-1/10">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-500">
                        @foreach($users as $user)
                        <tr class="text-neutral-800 dark:text-neutral-200">
                            <td class="px-5 py-4 text-sm font-medium whitespace-nowrap w-1/5">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-sm whitespace-nowrap w-1/10">{{ $user->sex }}</td>
                            <td class="px-5 py-4 text-sm whitespace-nowrap w-1/5">{{ $user->citizenship }}</td>
                            <td class="px-5 py-4 text-sm whitespace-nowrap w-1/10">{{ $user->civil_status }}</td>
                            <td class="px-5 py-4 text-sm whitespace-nowrap w-1/10">{{ $user->mobile_number }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap w-1/10">
                                <button wire:click="showUser({{ $user->id }})"
                                    class="text-blue-600 hover:text-blue-700">Show</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-white">
                        <tr>
                            <td colspan="6" class="p-0">
                                <div class="flex items-center justify-between h-16 px-3 border-t border-neutral-200">
                                    <p class="pl-2 text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ $users->firstItem() }}</span>
                                        out of <span class="font-medium">{{ $users->total() }}</span> results
                                    </p>
                                    <nav x-data="{}">
                                        <ul
                                            class="flex items-center text-sm leading-tight bg-white border divide-x rounded h-9 text-neutral-500 divide-neutral-200 border-neutral-200">
                                            {{ $users->links() }}
                                        </ul>
                                    </nav>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($selectedUser)
        <div class="bg-white overflow-hidden shadow rounded-lg border mt-10">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $selectedUserData->surname }}'s Profile
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="grid grid-cols-2 divide-x">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-r-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Full name
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $selectedUser->name }}
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Birth Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $selectedUserData->date_of_birth }}
                            </dd>
                        </div>
                    </div>



                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Sex at Birth
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUserData->sex }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Email address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUser->email }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Phone number
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUserData->mobile_number }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Citizenship
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUserData->citizenship }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Civil Status
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUserData->civil_status }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Height
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUserData->height }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Weight
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUserData->weight }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Blood Type
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $selectedUserData->blood_type }}
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $full_address }}
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="px-4 py-3 sm:px-6">
                <button wire:click="closeUserProfile" class="text-blue-600 hover:text-blue-700">Close</button>
            </div>
        </div>
        @endif
    </div>
</div>