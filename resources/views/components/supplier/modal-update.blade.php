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
<script>
    function prepareEditModal(id, name) {
        const form = document.getElementById('editSupplierForm');
        const input = document.getElementById('editSupplierName');
        
        input.value = name;
        
        let url = "{{ route('supplier.update', ':id') }}";
        form.action = url.replace(':id', id);
    }
</script>