<x-layouts.app>
    <main class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <nav class="text-sm font-medium">
                <ol class="flex items-center space-x-1 text-gray-500">
                    <li><a href="#" class="hover:text-gray-700">Home</a></li>
                    <li><i class="ph ph-caret-right text-xs mx-1"></i></li>
                    <li><a href="#" class="hover:text-gray-700">Suppliers</a></li>
                    <li><i class="ph ph-caret-right text-xs mx-1"></i></li>
                    <li><a href="#" class="hover:text-gray-700">Layups</a></li>
                    <li><i class="ph ph-caret-right text-xs mx-1"></i></li>
                    <li class="text-gray-900">{{ $layup->layup_code ?? 'Kode Layup' }}</li>
                </ol>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="exportLayers()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ph ph-download-simple text-lg text-gray-500"></i>
                    Export JSON
                </button>
                <input type="file" id="import_file_layers" accept=".json" class="hidden" onchange="importLayers(event)">
                <button type="button" onclick="document.getElementById('import_file_layers').click()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ph ph-upload-simple text-lg text-gray-500"></i>
                    Import JSON
                </button>

                <button type="button" id="btn-save-changes" class="inline-flex items-center gap-2 px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#3e7c5b] hover:bg-[#2f6348] transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    <i class="ph ph-floppy-disk text-lg"></i>
                    Save Changes
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
            <div class="flex flex-col lg:flex-row justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap mb-2">
                        <h2 class="text-[26px] font-bold text-gray-900 font-elegant tracking-tight">Layup Specification: {{ $layup->name ?? 'Nama Layup' }}</h2>
                    </div>
                </div>

                <div class="flex flex-wrap lg:flex-nowrap gap-6 lg:gap-10">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Created By</span>
                        <span class="text-sm font-bold text-gray-900">{{ $layup->creator_name ?? 'System' }}</span>
                    </div>
                    <div class="hidden lg:block w-px bg-gray-200"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Last Modified</span>
                        <span class="text-sm font-bold text-gray-900">{{ $layup->updated_at ? $layup->updated_at->format('M d, Y') : '-' }}</span>
                    </div>
                    <div class="hidden lg:block w-px bg-gray-200"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Thickness</span>
                        <span class="text-xl font-bold text-brand-600 font-serif" id="header-total-thickness">{{ $layup->layers->sum('thickness') ?? 0 }}mm</span>
                    </div>
                    <div class="hidden lg:block w-px bg-gray-200"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Layers</span>
                        <span class="text-xl font-bold text-brand-600 font-serif" id="header-total-layers">{{ $layup->layers->count() ?? 0 }} Layers</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7 flex flex-col">
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-[22px] font-bold text-gray-900 font-elegant">Layer Composition</h3>
                    <button type="button" id="btn-add-layer" class="text-brand-600 hover:text-brand-800 text-sm font-medium inline-flex items-center gap-1 transition-colors">
                        <i class="ph ph-plus font-bold"></i> Add Layer
                    </button>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex-1">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col" class="px-5 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider w-12">Order</th>
                                    <th scope="col" class="px-5 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Thickness</th>
                                    <th scope="col" class="px-5 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Width</th>
                                    <th scope="col" class="px-5 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Angle</th>
                                    <th scope="col" class="px-5 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th scope="col" class="px-5 py-3 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="layer-table-body" class="bg-white divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="bg-white/50 px-5 py-3 border-t border-gray-200 flex justify-between items-center">
                        <p class="text-xs text-gray-500 font-medium" id="footer-layer-count"></p>
                        <p class="text-xs text-gray-500 font-medium tracking-wide">Calculated Sum: <span id="footer-total-sum" class="font-mono text-gray-800 font-bold"></span></p>
                    </div>
                </div>

                <!-- Note Section -->
                <div class="mt-6 bg-[#faf9f5] border border-[#e5dfc9] rounded-xl p-4 flex gap-4 items-start shadow-sm">
                    <i class="ph ph-info text-2xl text-[#b09459] mt-0.5 shrink-0"></i>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1">Engineering Note</h4>
                        <p class="text-[13px] text-gray-600 leading-relaxed">
                            Ensure bonding pressure is adjusted for varying layer grades (C24/C16 mix). Verify alignment of 90° transverse layers.
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col">
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-[22px] font-bold text-gray-900 font-elegant">Structure Visualizer</h3>
                    
                    <div class="flex items-center gap-4 text-[11px] font-medium text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 bg-wood-light border border-black/10 rounded-sm"></div>
                            <span>Longitudinal (0°)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 bg-wood-dark border border-black/10 rounded-sm"></div>
                            <span>Transverse (90°)</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 flex-1 flex flex-col">
                    
                    <div class="relative flex-1 flex justify-center py-6">
                        
                        <div class="absolute left-0 top-6 bottom-6 w-16 border-r border-dashed border-gray-300 flex flex-col justify-between items-end pr-3">
                            <div class="text-[10px] font-bold text-gray-400 uppercase leading-tight text-right pt-2">
                                Top<br>(Outside)
                            </div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase leading-tight text-right pb-2">
                                Bottom<br>(Inside)
                            </div>
                        </div>

                        <div id="visualizer-canvas" class="flex flex-col gap-1 w-[260px] ml-12 transition-all duration-300 ease-in-out">
                        </div>

                    </div>

                    <div class="text-center mt-6 pt-6 border-t border-gray-100">
                        <p class="text-sm font-elegant text-gray-800 font-bold mb-1">Cross-Laminated Structural Assembly</p>
                        <p class="text-[11px] text-gray-400 italic">Note: 3D orientation is for schematic purposes. All layers bonded with industrial-grade adhesives.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <div id="modal-edit-layer" class="fixed inset-0 z-50 modal-overlay flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md modal-content overflow-hidden border border-gray-100">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 font-serif">Edit Layer <span id="modal-layer-label" class="text-brand-600"></span></h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Thickness (mm)</label>
                        <input type="number" id="edit-thickness" class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Width (mm)</label>
                        <input type="number" id="edit-width" class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Angle</label>
                        <select id="edit-angle" class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 bg-white">
                            <option value="0">0° (Longitudinal)</option>
                            <option value="90">90° (Transverse)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Grade</label>
                        <select id="edit-grade" class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 bg-white">
                            <option value="C24">C24</option>
                            <option value="C16">C16</option>
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">*Pastikan sesuai ketersediaan</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="saveEditLayer()" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 transition-colors shadow-sm">
                    Update Layer
                </button>
            </div>
        </div>
    </div>

    <div id="toast-notification" class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-2xl border text-sm font-medium transition-all duration-300 translate-y-4 opacity-0 pointer-events-none" role="alert">
        <i id="toast-icon" class="ph text-xl"></i>
        <span id="toast-message"></span>
    </div>

    <script>
        // --- DATA STATE ---
        
        // MENGAMBIL DATA DARI LARAVEL BLADE SECARA DINAMIS
        // Variabel layers akan langsung diisi dengan output JSON dari Backend
        let layers = @json($layersData ?? []);

        let draggedIndex = null;
        let currentlyEditingIndex = null; // Menyimpan index baris yang sedang diedit

        // --- RENDER FUNCTIONS ---
        
        function renderAll() {
            renderTable();
            renderVisualizer();
            updateStats();
        }

        // Render Table Rows
        function renderTable() {
            const tbody = document.getElementById('layer-table-body');
            tbody.innerHTML = '';

            layers.forEach((layer, index) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50/80 transition-colors bg-white';
                tr.setAttribute('draggable', 'true');
                tr.dataset.index = index;
                
                // Add Drag & Drop Event Listeners
                tr.addEventListener('dragstart', handleDragStart);
                tr.addEventListener('dragover', handleDragOver);
                tr.addEventListener('dragleave', handleDragLeave);
                tr.addEventListener('drop', handleDrop);
                tr.addEventListener('dragend', handleDragEnd);

                // Styling Angle Pill
                let anglePill = '';
                if (parseInt(layer.angle) === 0) {
                    anglePill = `
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200 shadow-sm">
                            <i class="ph ph-arrow-up font-bold"></i> 0°
                        </span>
                    `;
                } else {
                    anglePill = `
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-orange-50 text-orange-700 border border-orange-200 shadow-sm">
                            <i class="ph ph-arrows-clockwise font-bold"></i> 90°
                        </span>
                    `;
                }

                // Styling Grade Dot
                const dotColor = layer.grade === 'C24' ? 'bg-brand-500' : 'bg-[#a37952]';

                tr.innerHTML = `
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="drag-handle p-1.5 rounded hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors inline-block cursor-grab" title="Drag to reorder">
                            <i class="ph ph-dots-six-vertical text-lg"></i>
                        </div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${layer.thickness}mm</td>
                    <td class="px-5 py-4 whitespace-nowrap text-sm font-mono text-gray-500">${layer.width}mm</td>
                    <td class="px-5 py-4 whitespace-nowrap">${anglePill}</td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full ${dotColor}"></span>
                            <span class="text-sm text-gray-700">${layer.grade}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button class="btn-edit p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit Layer">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </button>
                            <button class="btn-remove p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Remove Layer">
                                <i class="ph ph-trash text-lg"></i>
                            </button>
                        </div>
                    </td>
                `;

                // Bind Edit & Remove Button Event
                tr.querySelector('.btn-edit').addEventListener('click', () => openEditModal(index));
                tr.querySelector('.btn-remove').addEventListener('click', () => removeLayer(index));

                tbody.appendChild(tr);
            });
        }

        // Render 2D Structure Blocks
        function renderVisualizer() {
            const canvas = document.getElementById('visualizer-canvas');
            canvas.innerHTML = '';

            layers.forEach((layer, index) => {
                const block = document.createElement('div');
                
                // Determine style based on angle
                const is0Deg = parseInt(layer.angle) === 0;
                const bgColorClass = is0Deg ? 'bg-wood-light' : 'bg-wood-dark';
                const icon = is0Deg ? '<i class="ph ph-arrow-up text-lg"></i>' : '<i class="ph ph-arrows-clockwise text-lg"></i>';
                
                // Calculate height proportionally. 1mm = 2px looks good for 140mm total (280px high).
                const heightPx = layer.thickness * 1.8;

                block.className = `${bgColorClass} w-full rounded shadow-sm border border-black/10 flex items-center justify-between px-4 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-x-1 cursor-pointer group`;
                block.style.height = `${heightPx}px`;

                // Visual numbering
                const visualLabel = `L${index + 1}`;

                block.innerHTML = `
                    <div class="flex-1 text-center font-mono text-xs font-bold text-[#5c4632] tracking-wide ml-4">
                        ${visualLabel} (${layer.thickness}mm)
                    </div>
                    <div class="text-[#8c6b4d] group-hover:text-[#5c4632] transition-colors">
                        ${icon}
                    </div>
                `;
                canvas.appendChild(block);
            });
        }

        // Update Text Stats
        function updateStats() {
            const totalThickness = layers.reduce((sum, layer) => sum + parseFloat(layer.thickness), 0);
            const totalLayers = layers.length;

            document.getElementById('header-total-thickness').textContent = `${totalThickness}mm`;
            document.getElementById('header-total-layers').textContent = `${totalLayers} Layers`;
            document.getElementById('footer-layer-count').textContent = `Showing ${totalLayers} layers`;
            document.getElementById('footer-total-sum').textContent = `${totalThickness.toFixed(2)} mm`;
        }

        // --- EDIT MODAL LOGIC (Client-Side) ---

        function openEditModal(index) {
            currentlyEditingIndex = index;
            const layer = layers[index];
            
            // Populate form
            document.getElementById('modal-layer-label').textContent = `L${index + 1}`;
            document.getElementById('edit-thickness').value = layer.thickness;
            document.getElementById('edit-width').value = layer.width;
            document.getElementById('edit-angle').value = layer.angle;
            document.getElementById('edit-grade').value = layer.grade;
            
            // Show modal
            document.getElementById('modal-edit-layer').classList.add('open');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-layer').classList.remove('open');
            currentlyEditingIndex = null;
        }

        function saveEditLayer() {
            if (currentlyEditingIndex !== null) {
                // Update JS State
                layers[currentlyEditingIndex].thickness = parseFloat(document.getElementById('edit-thickness').value);
                layers[currentlyEditingIndex].width = parseFloat(document.getElementById('edit-width').value);
                layers[currentlyEditingIndex].angle = parseInt(document.getElementById('edit-angle').value);
                layers[currentlyEditingIndex].grade = document.getElementById('edit-grade').value;
                
                closeEditModal();
                renderAll(); // Re-render UI to reflect changes
            }
        }

        function addLayer() {
            layers.push({
                id: null,
                thickness: 20, // default value
                width: 150,    // default value
                angle: 0,      // default value
                grade: 'C24'   // default value
            });
            renderAll();
        }

        function removeLayer(index) {
            if (confirm("Apakah Anda yakin ingin menghapus layer ini?")) {
                layers.splice(index, 1);
                renderAll();
            }
        }
        
        document.getElementById('btn-add-layer').addEventListener('click', addLayer);

        // --- IMPORT & EXPORT JSON ---

        function exportLayers() {
            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(layers, null, 2));
            const downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", "layers_export_" + new Date().getTime() + ".json");
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        }

        function importLayers(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const parsedData = JSON.parse(e.target.result);
                    if (Array.isArray(parsedData)) {
                        // Reset ID ke null agar layer hasil import dianggap sebagai layer baru
                        // dan tidak menyebabkan konflik validasi di backend
                        layers = parsedData.map(layer => {
                            return { ...layer, id: null };
                        });
                        renderAll();
                        showToast('success', 'Data layer berhasil dimuat ke editor. Jangan lupa klik Save Changes untuk menyimpan permanen.');
                    } else {
                        showToast('error', 'Format JSON tidak valid. Harus berupa array layer.');
                    }
                } catch (err) {
                    showToast('error', 'Gagal mem-parsing file JSON.');
                }
            };
            reader.readAsText(file);
            
            // Reset input file value so the same file can be uploaded again if needed
            event.target.value = '';
        }

        // --- SAVE TO BACKEND ---

        /**
         * Tampilkan toast notification.
         * @param {'success'|'error'} type
         * @param {string} message
         */
        function showToast(type, message) {
            const toast   = document.getElementById('toast-notification');
            const icon    = document.getElementById('toast-icon');
            const msgEl   = document.getElementById('toast-message');

            // Reset classes
            toast.className = toast.className
                .replace(/bg-\S+|border-\S+|text-\S+/g, '').trim();

            if (type === 'success') {
                toast.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
                icon.className  = 'ph ph-check-circle text-xl text-green-500';
            } else {
                toast.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
                icon.className  = 'ph ph-x-circle text-xl text-red-500';
            }

            msgEl.textContent = message;

            // Animasi masuk
            toast.classList.remove('translate-y-4', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-y-0', 'opacity-100');

            // Auto-hide setelah 3.5 detik
            clearTimeout(toast._hideTimer);
            toast._hideTimer = setTimeout(() => {
                toast.classList.add('translate-y-4', 'opacity-0', 'pointer-events-none');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, 3500);
        }

        /**
         * Kirim perubahan layers ke server via fetch().
         * Payload dibangun dari state `layers` yang sudah dimanipulasi JS.
         */
        async function saveChanges() {
            const btn         = document.getElementById('btn-save-changes');
            const originalHTML = btn.innerHTML;

            // 1. Set loading state pada tombol
            btn.disabled  = true;
            btn.innerHTML = `<i class="ph ph-spinner animate-spin text-lg"></i> Saving...`;

            // 2. Bangun payload dari state JS saat ini
            const payload = {
                layers: layers.map((layer, index) => ({
                    id:          layer.id ?? null,
                    layer_order: index + 1,
                    thickness:   layer.thickness,
                    width:       layer.width,
                    angle:       layer.angle,
                    grade:       layer.grade,
                }))
            };

            try {
                // 3. Kirim ke backend via fetch()
                const response = await fetch(
                    `{{ route('layup.layers.save', $layup->id) }}`,
                    {
                        method:  'POST',
                        headers: {
                            'Content-Type':     'application/json',
                            'Accept':           'application/json',
                            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(payload),
                    }
                );

                const data = await response.json();

                if (response.ok && data.success) {
                    // 4a. Sukses
                    showToast('success', data.message ?? 'Perubahan berhasil disimpan!');
                } else {
                    // 4b. Validasi error atau error lain dari server
                    const firstError = data.errors
                        ? Object.values(data.errors).flat()[0]
                        : (data.message ?? 'Terjadi kesalahan.');
                    showToast('error', firstError);
                }
            } catch (err) {
                // 4c. Network error
                console.error('Save failed:', err);
                showToast('error', 'Koneksi gagal. Periksa jaringan Anda.');
            } finally {
                // 5. Kembalikan tombol ke state semula
                btn.disabled  = false;
                btn.innerHTML = originalHTML;
            }
        }

        document.getElementById('btn-save-changes').addEventListener('click', saveChanges);


        // --- DRAG AND DROP HANDLERS ---
        
        function handleDragStart(e) {
            draggedIndex = parseInt(this.dataset.index);
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', draggedIndex);
            
            setTimeout(() => {
                this.classList.add('dragging');
            }, 0);
        }

        function handleDragOver(e) {
            e.preventDefault(); 
            e.dataTransfer.dropEffect = 'move';
            
            const tbody = document.getElementById('layer-table-body');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.dragging)'));
            
            rows.forEach(row => row.classList.remove('drag-over'));
            
            if(this !== e.target && !this.classList.contains('dragging')) {
                 this.classList.add('drag-over');
            }
            return false;
        }

        function handleDragLeave(e) {
            this.classList.remove('drag-over');
        }

        function handleDrop(e) {
            e.stopPropagation();
            e.preventDefault();
            
            this.classList.remove('drag-over');
            
            const targetIndex = parseInt(this.dataset.index);
            
            if (draggedIndex !== null && draggedIndex !== targetIndex) {
                // Reorder array in Javascript
                const draggedItem = layers.splice(draggedIndex, 1)[0];
                layers.splice(targetIndex, 0, draggedItem);
                
                // Re-render everything
                renderAll();
            }
            return false;
        }

        function handleDragEnd(e) {
            this.classList.remove('dragging');
            const tbody = document.getElementById('layer-table-body');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.forEach(row => row.classList.remove('drag-over'));
            draggedIndex = null;
        }

        // --- INITIALIZE ---
        window.onload = function() {
            renderAll();
        };

    </script>
</x-layouts.app>