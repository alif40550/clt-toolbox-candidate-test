<div id="modal-conflict-layup" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-red-50/50">
            <h3 class="text-lg font-bold text-red-700">Conflict Detected</h3>
            <button type="button" onclick="closeLayupConflictModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">The following imported layups have identical names to existing ones. They will be imported as new files with modified names (e.g., appended with '(1)').</p>
            <ul id="conflict-list-layup" class="list-disc list-inside text-sm text-gray-800 font-medium bg-gray-50 p-3 rounded-md mb-4 max-h-32 overflow-y-auto"></ul>
            <p class="text-sm text-gray-600 font-medium">Do you wish to proceed?</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <button type="button" onclick="closeLayupConflictModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
            <button type="button" onclick="confirmLayupImportConflict()" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">Confirm & Import</button>
        </div>
    </div>
</div>

<script>
    let pendingLayupImportData = null;
    let conflictLayupFile = null;

    function handleLayupImport(event) {
        const file = event.target.files[0];
        if (!file) return;

        const existingNames = Array.from(document.querySelectorAll('.layup-name-element'))
                                 .map(el => el.textContent.trim());

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const parsedData = JSON.parse(e.target.result);
                if (Array.isArray(parsedData)) {
                    let hasConflict = false;
                    const conflicts = [];

                    parsedData.forEach(item => {
                        if (item.name && existingNames.includes(item.name)) {
                            hasConflict = true;
                            conflicts.push(item.name);
                            let counter = 1;
                            let newName = item.name + ' (' + counter + ')';
                            while (existingNames.includes(newName)) {
                                counter++;
                                newName = item.name + ' (' + counter + ')';
                            }
                            item.name = newName;
                        }
                    });

                    if (hasConflict) {
                        pendingLayupImportData = parsedData;
                        conflictLayupFile = file;
                        document.getElementById('conflict-list-layup').innerHTML = conflicts.map(name => '<li>' + name + '</li>').join('');
                        document.getElementById('modal-conflict-layup').classList.remove('hidden');
                    } else {
                        submitLayupJsonDirectly(parsedData, file);
                    }
                } else {
                    document.getElementById('form-import-layup').submit();
                }
            } catch (err) {
                document.getElementById('form-import-layup').submit();
            }
        };
        reader.readAsText(file);
        event.target.value = '';
    }

    function closeLayupConflictModal() {
        document.getElementById('modal-conflict-layup').classList.add('hidden');
        pendingLayupImportData = null;
        conflictLayupFile = null;
    }

    function confirmLayupImportConflict() {
        if (pendingLayupImportData) {
            submitLayupJsonDirectly(pendingLayupImportData, conflictLayupFile);
            closeLayupConflictModal();
        }
    }

    function submitLayupJsonDirectly(data, originalFile) {
        const blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
        const modifiedFile = new File([blob], originalFile.name, { type: 'application/json' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(modifiedFile);
        
        const form = document.getElementById('form-import-layup');
        const fileInput = document.getElementById('import_file_layup');
        fileInput.files = dataTransfer.files;
        
        form.submit();
    }
</script>
