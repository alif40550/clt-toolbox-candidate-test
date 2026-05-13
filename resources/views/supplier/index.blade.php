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
                                            <div class="text-sm font-bold text-gray-900">{{ $supplier->name }}</div>
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

    <!-- 1. ADD SUPPLIER MODAL -->
    <x-form.modal-add></x-form.modal-add>

    <!-- 2. EDIT SUPPLIER MODAL -->
    <div id="editSupplierModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-40 transition-opacity" aria-hidden="true" onclick="closeModal('editSupplierModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="modal-content inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10 text-blue-600">
                            <i class="ph ph-pencil-simple text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900  " id="modal-title">
                                Edit Supplier
                            </h3>
                            <div class="mt-1">
                                <p class="text-sm text-gray-500">
                                    Update the details for this timber supplier.
                                </p>
                            </div>
                        </div>
                        <button type="button" onclick="closeModal('editSupplierModal')" class="text-gray-400 hover:text-gray-500 focus:outline-none rounded-md">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-4 py-5 sm:p-6">
                    <form action="" method="post" onsubmit="closeModal('editSupplierModal')" id="editSupplierForm">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label for="editSupplierName" class="block text-sm font-medium text-gray-700">Supplier Name <span class="text-red-500">*</span></label>
                                <div class="mt-1.5 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-identification-card text-gray-400"></i>
                                    </div>
                                    <input type="text" id="editSupplierName" name="supplierName" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2.5 border" required>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Save Changes
                            </button>
                            <button type="button" onclick="closeModal('editSupplierModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- 3. CONFIRM DELETE MODAL -->
    <div id="confirmDeleteModal" class="modal-overlay fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-40 transition-opacity" aria-hidden="true" onclick="closeModal('confirmDeleteModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="modal-content inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full border border-gray-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10 text-red-600">
                            <i class="ph ph-trash text-xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Supplier
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this supplier? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="" method="post" id="deleteSupplierForm">
                    @csrf
                    @method('DELETE')
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Yes, Delete
                        </button>
                        <button type="button" onclick="closeModal('confirmDeleteModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('open');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('open');
        }
        function prepareEditModal(id, name) {
            const form = document.getElementById('editSupplierForm');
            const input = document.getElementById('editSupplierName');
            
            input.value = name;
            
            let url = "{{ route('supplier.update', ':id') }}";
            form.action = url.replace(':id', id);
        }
        function prepareDeleteModal(id){
            const form = document.getElementById('deleteSupplierForm');

            let url = "{{ route('supplier.destroy', ':id') }}";
            form.action = url.replace(':id', id);
        }
    </script>
</x-layouts.app>