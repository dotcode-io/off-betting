<div class="p-3 sm:p-6 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 min-h-screen transition-colors duration-300">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
                <flux:heading size="xl" class="text-slate-800 dark:text-slate-100">Dashboard Report</flux:heading>
                <flux:subheading class="text-slate-600 dark:text-slate-300">Professional financial overview and statistics</flux:subheading>
            </div>

            <!-- Controls Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                <!-- Dark Mode Toggle -->
                <button
                    onclick="toggleDarkMode()"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 shadow-sm hover:shadow-md transition-all duration-200"
                    title="Toggle Dark Mode"
                >
                    <svg class="w-4 h-4 text-slate-600 dark:text-slate-300 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg class="w-4 h-4 text-slate-300 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-sm text-slate-600 dark:text-slate-300 hidden sm:inline">
                        <span class="dark:hidden">Dark</span>
                        <span class="hidden dark:inline">Light</span>
                    </span>
                </button>

                <!-- Date Picker -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <flux:input
                        type="date"
                        wire:model.live="selectedDate"
                        class="shadow-sm border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400 w-full sm:w-auto"
                    />
                    <flux:badge color="blue" size="sm" class="whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                    </flux:badge>
                </div>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Script -->
    <script>
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        }

        // Initialize dark mode based on localStorage or system preference
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Monthly Overview -->
    <div class="mb-6 sm:mb-8">
        <flux:heading size="lg" class="mb-4 text-slate-700 dark:text-slate-200 flex items-center gap-2 pl-1 sm:pl-3">
            <flux:icon name="calendar" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
            Monthly Overview
        </flux:heading>

        <!-- Mobile Card Layout (Hidden on Desktop) -->
        <div class="block lg:hidden space-y-4">
            <!-- Monthly Earning Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <flux:icon name="arrow-trending-up" class="w-5 h-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Monthly Earning</h3>
                            <flux:badge color="green" size="sm">Positive</flux:badge>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold text-green-600 dark:text-green-400">
                            ₱{{ number_format($this->monthlyEarnings->total_earnings ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Draw Earnings Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                @php
                    $monthlyDrawEarnings = $this->monthlyEarnings->total_draw_earnings ?? 0;
                    $isNegative = $monthlyDrawEarnings < 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <flux:icon name="chart-bar" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Monthly Draw Earnings</h3>
                            <flux:badge color="{{ $isNegative ? 'red' : 'blue' }}" size="sm">
                                {{ $isNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold {{ $isNegative ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }}">
                            ₱{{ number_format($monthlyDrawEarnings, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Total Earning Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                @php
                    $monthlyTotal = ($this->monthlyEarnings->total_earnings ?? 0) + ($this->monthlyEarnings->total_draw_earnings ?? 0);
                    $isTotalNegative = $monthlyTotal < 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <flux:icon name="currency-dollar" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Monthly Total Earning</h3>
                            <flux:badge color="{{ $isTotalNegative ? 'red' : 'purple' }}" size="sm">
                                {{ $isTotalNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold {{ $isTotalNegative ? 'text-red-600 dark:text-red-400' : 'text-purple-600 dark:text-purple-400' }}">
                            ₱{{ number_format($monthlyTotal, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Ghostbet Earnings Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300 border-t-4 border-t-orange-500 dark:border-t-orange-400">
                @php
                    $monthlyGhostbet = $this->monthlyGhostbetEarnings->total_win_amount ?? 0;
                    $isGhostbetNegative = $monthlyGhostbet < 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                            <flux:icon name="user" class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Monthly Ghostbet Earnings</h3>
                            <flux:badge color="{{ $isGhostbetNegative ? 'red' : 'orange' }}" size="sm">
                                {{ $isGhostbetNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold {{ $isGhostbetNegative ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }}">
                            ₱{{ number_format($monthlyGhostbet, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Table Layout (Hidden on Mobile) -->
        <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700 transition-colors duration-300">
            <flux:table class="w-full">
                <flux:table.columns>
                    <flux:table.column class="bg-slate-50 dark:bg-slate-700 font-semibold text-slate-700 dark:text-slate-200 !pl-5">Metric</flux:table.column>
                    <flux:table.column class="bg-slate-50 dark:bg-slate-700 font-semibold text-slate-700 dark:text-slate-200 text-right">Amount</flux:table.column>
                    <flux:table.column class="bg-slate-50 dark:bg-slate-700 font-semibold text-slate-700 dark:text-slate-200 text-center">Status</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    <!-- Monthly Earning -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="arrow-trending-up" class="w-4 h-4 text-green-600 dark:text-green-400" />
                                Monthly Earning
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <flux:text class="text-lg font-semibold text-green-600 dark:text-green-400">
                                ₱{{ number_format($this->monthlyEarnings->total_earnings ?? 0, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="green" size="sm">Positive</flux:badge>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Monthly Draw Earnings -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="chart-bar" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                Monthly Draw Earnings
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            @php
                                $monthlyDrawEarnings = $this->monthlyEarnings->total_draw_earnings ?? 0;
                                $isNegative = $monthlyDrawEarnings < 0;
                            @endphp
                            <flux:text class="text-lg font-semibold {{ $isNegative ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }}">
                                ₱{{ number_format($monthlyDrawEarnings, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="{{ $isNegative ? 'red' : 'blue' }}" size="sm">
                                {{ $isNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Monthly Total Earning -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="currency-dollar" class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                                Monthly Total Earning
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            @php
                                $monthlyTotal = ($this->monthlyEarnings->total_earnings ?? 0) + ($this->monthlyEarnings->total_draw_earnings ?? 0);
                                $isTotalNegative = $monthlyTotal < 0;
                            @endphp
                            <flux:text class="text-lg font-semibold {{ $isTotalNegative ? 'text-red-600 dark:text-red-400' : 'text-purple-600 dark:text-purple-400' }}">
                                ₱{{ number_format($monthlyTotal, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="{{ $isTotalNegative ? 'red' : 'purple' }}" size="sm">
                                {{ $isTotalNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Monthly Ghostbet Earnings -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-t-2 border-slate-200 dark:border-slate-600">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="user" class="w-4 h-4 text-orange-600 dark:text-orange-400" />
                                Monthly Ghostbet Earnings
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            @php
                                $monthlyGhostbet = $this->monthlyGhostbetEarnings->total_win_amount ?? 0;
                                $isGhostbetNegative = $monthlyGhostbet < 0;
                            @endphp
                            <flux:text class="text-lg font-semibold {{ $isGhostbetNegative ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400' }}">
                                ₱{{ number_format($monthlyGhostbet, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="{{ $isGhostbetNegative ? 'red' : 'orange' }}" size="sm">
                                {{ $isGhostbetNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
            </flux:table>
        </div>
    </div>

    <!-- Daily Overview -->
    <div class="mb-6 sm:mb-8">
        <flux:heading size="lg" class="mb-4 text-slate-700 dark:text-slate-200 flex items-center gap-2 pl-1 sm:pl-3">
            <flux:icon name="clock" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
            Daily Overview
        </flux:heading>

        <!-- Mobile Card Layout (Hidden on Desktop) -->
        <div class="block lg:hidden space-y-4">
            <!-- Daily Earning Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                            <flux:icon name="banknotes" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Daily Earning</h3>
                            <flux:badge color="green" size="sm">Positive</flux:badge>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Regular earnings</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold text-emerald-600 dark:text-emerald-400">
                            ₱{{ number_format($this->dailyEarnings->total_earnings ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Draw Earning Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                @php
                    $dailyDrawEarnings = $this->dailyEarnings->total_draw_earnings ?? 0;
                    $isDailyDrawNegative = $dailyDrawEarnings < 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg">
                            <flux:icon name="chart-pie" class="w-5 h-5 text-cyan-600 dark:text-cyan-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Daily Draw Earning</h3>
                            <flux:badge color="{{ $isDailyDrawNegative ? 'red' : 'cyan' }}" size="sm">
                                {{ $isDailyDrawNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Draw outcomes</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold {{ $isDailyDrawNegative ? 'text-red-600 dark:text-red-400' : 'text-cyan-600 dark:text-cyan-400' }}">
                            ₱{{ number_format($dailyDrawEarnings, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Total Earning Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                @php
                    $dailyTotal = ($this->dailyEarnings->total_earnings ?? 0) + ($this->dailyEarnings->total_draw_earnings ?? 0);
                    $isDailyTotalNegative = $dailyTotal < 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-violet-100 dark:bg-violet-900/30 rounded-lg">
                            <flux:icon name="calculator" class="w-5 h-5 text-violet-600 dark:text-violet-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Daily Total Earning</h3>
                            <flux:badge color="{{ $isDailyTotalNegative ? 'red' : 'violet' }}" size="sm">
                                {{ $isDailyTotalNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Combined total</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold {{ $isDailyTotalNegative ? 'text-red-600 dark:text-red-400' : 'text-violet-600 dark:text-violet-400' }}">
                            ₱{{ number_format($dailyTotal, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Ghostbet Earnings Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300 border-t-4 border-t-amber-500 dark:border-t-amber-400">
                @php
                    $dailyGhostbet = $this->dailyGhostbetEarnings->total_win_amount ?? 0;
                    $isDailyGhostbetNegative = $dailyGhostbet < 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                            <flux:icon name="user" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Daily Ghostbet Earnings</h3>
                            <flux:badge color="{{ $isDailyGhostbetNegative ? 'red' : 'amber' }}" size="sm">
                                {{ $isDailyGhostbetNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ghostbet wins</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold {{ $isDailyGhostbetNegative ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                            ₱{{ number_format($dailyGhostbet, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Bet Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300 border-t-4 border-t-rose-500 dark:border-t-rose-400">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-rose-100 dark:bg-rose-900/30 rounded-lg">
                            <flux:icon name="ticket" class="w-5 h-5 text-rose-600 dark:text-rose-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Total Bet</h3>
                            <flux:badge color="rose" size="sm">Activity</flux:badge>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ number_format($this->dailyBetStats->total_bet_count ?? 0) }} bets
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold text-rose-600 dark:text-rose-400">
                            ₱{{ number_format($this->dailyBetStats->total_bet_amount ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Withdrawal Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 p-4 transition-colors duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <flux:icon name="arrow-down-tray" class="w-5 h-5 text-red-600 dark:text-red-400" />
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-700 dark:text-slate-200">Total Withdrawal</h3>
                            <flux:badge color="red" size="sm">Outflow</flux:badge>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ number_format($this->dailyWithdrawals->total_win_amount ?? 0) }} withdrawals
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold text-red-600 dark:text-red-400">
                            ₱{{ number_format($this->dailyWithdrawals->total_withdrawal_amount ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Table Layout (Hidden on Mobile) -->
        <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700 transition-colors duration-300">
            <flux:table class="w-full">
                <flux:table.columns>
                    <flux:table.column class="bg-slate-50 dark:bg-slate-700 font-semibold text-slate-700 dark:text-slate-200 !pl-5">Metric</flux:table.column>
                    <flux:table.column class="bg-slate-50 dark:bg-slate-700 font-semibold text-slate-700 dark:text-slate-200 text-right">Amount</flux:table.column>
                    <flux:table.column class="bg-slate-50 dark:bg-slate-700 font-semibold text-slate-700 dark:text-slate-200 text-center">Status</flux:table.column>
                    <flux:table.column class="bg-slate-50 dark:bg-slate-700 font-semibold text-slate-700 dark:text-slate-200 text-center">Additional Info</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    <!-- Daily Earning -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="banknotes" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                                Daily Earning
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <flux:text class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">
                                ₱{{ number_format($this->dailyEarnings->total_earnings ?? 0, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="green" size="sm">Positive</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:text size="sm" class="text-slate-500 dark:text-slate-400">Regular earnings</flux:text>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Daily Draw Earning -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="chart-pie" class="w-4 h-4 text-cyan-600 dark:text-cyan-400" />
                                Daily Draw Earning
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            @php
                                $dailyDrawEarnings = $this->dailyEarnings->total_draw_earnings ?? 0;
                                $isDailyDrawNegative = $dailyDrawEarnings < 0;
                            @endphp
                            <flux:text class="text-lg font-semibold {{ $isDailyDrawNegative ? 'text-red-600 dark:text-red-400' : 'text-cyan-600 dark:text-cyan-400' }}">
                                ₱{{ number_format($dailyDrawEarnings, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="{{ $isDailyDrawNegative ? 'red' : 'cyan' }}" size="sm">
                                {{ $isDailyDrawNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:text size="sm" class="text-slate-500 dark:text-slate-400">Draw outcomes</flux:text>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Daily Total Earning -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="calculator" class="w-4 h-4 text-violet-600 dark:text-violet-400" />
                                Daily Total Earning
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            @php
                                $dailyTotal = ($this->dailyEarnings->total_earnings ?? 0) + ($this->dailyEarnings->total_draw_earnings ?? 0);
                                $isDailyTotalNegative = $dailyTotal < 0;
                            @endphp
                            <flux:text class="text-lg font-semibold {{ $isDailyTotalNegative ? 'text-red-600 dark:text-red-400' : 'text-violet-600 dark:text-violet-400' }}">
                                ₱{{ number_format($dailyTotal, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="{{ $isDailyTotalNegative ? 'red' : 'violet' }}" size="sm">
                                {{ $isDailyTotalNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:text size="sm" class="text-slate-500 dark:text-slate-400">Combined total</flux:text>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Daily Ghostbet Earnings -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-t-2 border-slate-200 dark:border-slate-600">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="user" class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                                Daily Ghostbet Earnings
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            @php
                                $dailyGhostbet = $this->dailyGhostbetEarnings->total_win_amount ?? 0;
                                $isDailyGhostbetNegative = $dailyGhostbet < 0;
                            @endphp
                            <flux:text class="text-lg font-semibold {{ $isDailyGhostbetNegative ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                                ₱{{ number_format($dailyGhostbet, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="{{ $isDailyGhostbetNegative ? 'red' : 'amber' }}" size="sm">
                                {{ $isDailyGhostbetNegative ? 'Negative' : 'Positive' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:text size="sm" class="text-slate-500 dark:text-slate-400">Ghostbet wins</flux:text>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Total Bet -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-t-2 border-slate-200 dark:border-slate-600">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="ticket" class="w-4 h-4 text-rose-600 dark:text-rose-400" />
                                Total Bet
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <flux:text class="text-lg font-semibold text-rose-600 dark:text-rose-400">
                                ₱{{ number_format($this->dailyBetStats->total_bet_amount ?? 0, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="rose" size="sm">Activity</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:text size="sm" class="text-slate-500 dark:text-slate-400">
                                {{ number_format($this->dailyBetStats->total_bet_count ?? 0) }} bets
                            </flux:text>
                        </flux:table.cell>
                    </flux:table.row>

                    <!-- Total Withdrawal -->
                    <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <flux:table.cell class="font-medium text-slate-700 dark:text-slate-200">
                            <div class="flex items-center gap-2 pl-3">
                                <flux:icon name="arrow-down-tray" class="w-4 h-4 text-red-600 dark:text-red-400" />
                                Total Withdrawal
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <flux:text class="text-lg font-semibold text-red-600 dark:text-red-400">
                                ₱{{ number_format($this->dailyWithdrawals->total_withdrawal_amount ?? 0, 2) }}
                            </flux:text>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:badge color="red" size="sm">Outflow</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-center">
                            <flux:text size="sm" class="text-slate-500 dark:text-slate-400">
                                {{ number_format($this->dailyWithdrawals->total_withdrawal_count ?? 0) }} withdrawals
                            </flux:text>
                        </flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
            </flux:table>
        </div>
    </div>

    <!-- Summary Footer -->
    <div class="mt-6 sm:mt-8 p-4 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
        <flux:text size="sm" class="text-slate-500 dark:text-slate-400 text-center">
            Last updated: {{ now()->format('M d, Y \a\t h:i A') }} •
            Data refreshes automatically when date changes
        </flux:text>
    </div>
</div>
