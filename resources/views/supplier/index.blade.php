<x-layouts.app>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-[28px] font-bold text-gray-900   tracking-tight">Suppliers</h2>
                <p class="mt-1.5 text-sm text-gray-500">Manage timber suppliers and material sourcing.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button type="button" onclick="openModal('addSupplierModal')" class="inline-flex items-center gap-2 px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#3e7c5b] hover:bg-[#2f6348] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                    <i class="ph ph-plus font-bold"></i>
                    Add Supplier
                </button>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="ph ph-magnifying-glass text-gray-400 text-lg"></i>
                </div>
                <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 bg-white shadow-sm" placeholder="Search suppliers by name...">
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider w-1/3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                Total Layups
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                Created At
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($suppliers as $supplier )
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="rounded-full" src="https://ui-avatars.com/api/?name={{ $supplier->name }}" alt="{{ $supplier->name }}">
                                    <div class="ml-4">
                                            <a href="{{ route('supplier.show', $supplier) }}">
                                                <div class="text-sm font-bold text-gray-900 hover:underline hover:text-blue-500">{{ $supplier->name }}</div>
                                            </a>
                                            <div class="text-[11px] text-gray-500 mt-0.5">ID: {{ 'SUP-'. $supplier->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $supplier->layups_count }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @formatDate($supplier->created_at) 
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openModal('editSupplierModal');prepareEditModal({{ $supplier->id }}, '{{ addslashes($supplier->name) }}')" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </button>
                                        <button onclick="openModal('confirmDeleteModal');prepareDeleteModal({{ $supplier->id }})" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </main>

    <x-supplier.modal-add></x-supplier.modal-add>
    <x-supplier.modal-update></x-supplier.modal-update>
    <x-supplier.modal-delete></x-supplier.modal-delete>
</x-layouts.app>