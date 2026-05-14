// --- DATA STATE ---
        // Initial Layer Configuration (Mendekati skema DB Anda)
        // Kita menggunakan array untuk menyimpan State UI sementara sebelum disave ke DB
        let layers = @json($layup->layers);

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
                            <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Remove Layer">
                                <i class="ph ph-trash text-lg"></i>
                            </button>
                        </div>
                    </td>
                `;

                // Bind Edit Button Event
                tr.querySelector('.btn-edit').addEventListener('click', () => openEditModal(index));

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

        // --- SAVE TO BACKEND SIMULATION ---

        document.getElementById('btn-save-changes').addEventListener('click', function() {
            
            // 1. Map state saat ini untuk menghasilkan payload yang sesuai dengan Schema Database
            const payloadData = layers.map((layer, index) => {
                return {
                    id: layer.id,               // ID dari DB (bisa null jika layer baru)
                    layer_order: index + 1,     // Order baru berdasarkan urutan UI saat ini
                    thickness: layer.thickness,
                    width: layer.width,
                    angle: layer.angle,
                    grade: layer.grade          // Note: Grade dikirim, pastikan Backend siap menerimanya
                };
            });

            // 2. Tampilkan payload di console (Simulasi fetch/axios ke Laravel)
            console.log("=== PAYLOAD READY TO SEND TO LARAVEL ===");
            console.log(JSON.stringify({ layers: payloadData }, null, 2));
            
            alert("Perubahan disiapkan! Silakan cek Developer Console (F12) untuk melihat JSON Payload berisi urutan (layer_order) yang baru.");

            // Disini nantinya Anda taruh: 
            // axios.post('/api/layups/1/layers', { layers: payloadData }).then(...)
        });


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