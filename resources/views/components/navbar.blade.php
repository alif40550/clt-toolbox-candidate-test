<header class="bg-white border-b border-gray-200 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Left: Logo & App Name -->
            <div class="flex items-center gap-4">
                <div class="bg-[#3e7c5b] text-white rounded flex items-center justify-center h-10 w-10 shadow-sm">
                    <!-- Custom SVG to match the actual CLT Layup logo -->
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3L8.5 9H15.5L12 3Z" />
                        <path d="M7.5 10L4 16H11L7.5 10Z" />
                        <path d="M16.5 10L13 16H20L16.5 10Z" />
                    </svg>
                </div>
                <div class="flex flex-col justify-center">
                    <h1 class="font-bold text-gray-900 leading-none text-[15px] font-serif tracking-wide">CLT Layup</h1>
                    <p class="text-[10px] text-gray-500 font-medium tracking-widest uppercase mt-0.5">Manager</p>
                </div>
            </div>

            <!-- Middle: Navigation Links -->
            <nav class="hidden md:flex space-x-8 h-full ml-12 flex-1">
                <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                    Overview
                </a>
                <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-brand-600 text-sm font-medium text-brand-600">
                    Suppliers
                </a>
                <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                    Layups
                </a>
                <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                    Layers
                </a>
                <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                    Settings
                </a>
            </nav>

            <!-- Right: Actions & User Profile -->
            <div class="flex items-center gap-5">
                <div class="h-8 w-px bg-gray-200"></div>

                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-gray-200 transition-colors">
                        <i class="ph ph-user text-lg"></i>
                    </div>
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>