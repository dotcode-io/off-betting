<div class="p-3 sm:p-6 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 min-h-screen transition-colors duration-300">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-64"></div>
                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-80 mt-2"></div>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-lg animate-pulse w-32"></div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-40"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse w-24"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Overview Loading -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-2 mb-4 pl-1 sm:pl-3">
            <div class="w-5 h-5 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-48"></div>
        </div>

        <!-- Mobile Card Layout Loading -->
        <div class="block lg:hidden space-y-4">
            @for($i = 0; $i < 5; $i++)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <div class="w-5 h-5 bg-gray-200 dark:bg-gray-600 rounded animate-pulse"></div>
                            </div>
                            <div>
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-32 mb-1"></div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse w-16"></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-24"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Desktop Table Loading -->
        <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700 transition-colors duration-300">
            <div class="bg-slate-50 dark:bg-slate-700 p-4 border-b border-slate-200 dark:border-slate-600">
                <div class="flex justify-between">
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-20"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-20"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-20"></div>
                </div>
            </div>
            @for($i = 0; $i < 5; $i++)
                <div class="p-4 border-b border-slate-200 dark:border-slate-600">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-gray-200 dark:bg-gray-600 rounded animate-pulse"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-32"></div>
                        </div>
                        <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-24"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded-full animate-pulse w-16"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Daily Overview Loading -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-2 mb-4 pl-1 sm:pl-3">
            <div class="w-5 h-5 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-40"></div>
        </div>

        <!-- Mobile Card Layout Loading -->
        <div class="block lg:hidden space-y-4">
            @for($i = 0; $i < 7; $i++)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <div class="w-5 h-5 bg-gray-200 dark:bg-gray-600 rounded animate-pulse"></div>
                            </div>
                            <div>
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-32 mb-1"></div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse w-16 mb-1"></div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-20"></div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-md animate-pulse w-24"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Desktop Table Loading -->
        <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700 transition-colors duration-300">
            <div class="bg-slate-50 dark:bg-slate-700 p-4 border-b border-slate-200 dark:border-slate-600">
                <div class="flex justify-between">
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-20"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-20"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-20"></div>
                    <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-28"></div>
                </div>
            </div>
            @for($i = 0; $i < 7; $i++)
                <div class="p-4 border-b border-slate-200 dark:border-slate-600">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-gray-200 dark:bg-gray-600 rounded animate-pulse"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-32"></div>
                        </div>
                        <div class="h-5 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-24"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded-full animate-pulse w-16"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded-md animate-pulse w-20"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
