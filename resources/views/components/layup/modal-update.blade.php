<div id="editLayupModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-40 transition-opacity" aria-hidden="true" onclick="closeModal('editLayupModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="modal-content inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10 text-blue-600">
                        <i class="ph ph-pencil-simple text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 font-elegant" id="modal-title">
                            Edit Layup Configuration
                        </h3>
                    </div>
                    <button type="button" onclick="closeModal('editLayupModal')" class="text-gray-400 hover:text-gray-500 focus:outline-none rounded-md">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="bg-white px-4 py-5 sm:p-6">
                <form action="" method="post" id="editLayupForm">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Layup Name <span class="text-red-500">*</span></label>
                            <input id="editLayupName" name="layupName" type="text" class="mt-1.5 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border shadow-sm" value="" required>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeModal('editLayupModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    function prepareEditModal(id, name) {
        const form = document.getElementById('editLayupForm');
        const input = document.getElementById('editLayupName');
        
        input.value = name;
        
        let url = "{{ route('supplier.layup.update', ':id') }}";
        form.action = url.replace(':id', id);
    }
    </script>
</div>