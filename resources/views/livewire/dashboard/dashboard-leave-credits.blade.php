<div class="flex flex-col sm:flex-row gap-2 mt-4">

    <div
        class="p-6 flex-1 bg-gradient-to-br from-indigo-100 to-white dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md">
        <div>
            <h2 class="text-md mb-2 text-gray-900 dark:text-gray-100">
                Vacation Leave Credits
            </h2>
            <p class="text-3xl font-bold text-blue-600 dark:text-gray-200">
                {{ number_format($vlClaimableCredits, 3) }}
            </p>
        </div>
    </div>

    <div
        class="p-6 flex-1 bg-gradient-to-br from-indigo-100 to-white dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md">
        <div>
            <h2 class="text-md mb-2 text-gray-900 dark:text-gray-100">
                Forced Leave Credits
            </h2>
            <p class="text-3xl font-bold text-blue-600 dark:text-gray-200">
                {{ number_format($flClaimableCredits, 3) }}
            </p>
        </div>
    </div>

    <div
        class="p-6 flex-1 bg-gradient-to-br from-indigo-100 to-white dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md">
        <div>
            <h2 class="text-md mb-2 text-gray-900 dark:text-gray-100">
                Sick Leave Credits
            </h2>
            <p class="text-3xl font-bold text-blue-600 dark:text-gray-200">
                {{ number_format($slClaimableCredits, 3) }}
            </p>
        </div>
    </div>

    <div
        class="p-6 flex-1 bg-gradient-to-br from-indigo-100 to-white dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md">
        <div>
            <h2 class="text-md mb-2 text-gray-900 dark:text-gray-100">
                Special Privilege Leave Credits
            </h2>
            <p class="text-3xl font-bold text-blue-600 dark:text-gray-200">
                {{ number_format($splClaimableCredits, 3) }}
            </p>
        </div>
    </div>

    {{-- <div
        class="p-6 flex-1 bg-gradient-to-br from-indigo-100 to-white dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md">
        <div>
            <h2 class="text-xl sm:text-2xl mb-6 text-gray-900 dark:text-gray-100">
                CTO Credits
            </h2>
            <p class="text-3xl font-semi-bold text-blue-600 dark:text-gray-200">
                {{ $ctoCredits }}
            </p>
        </div>
    </div> --}}

</div>
