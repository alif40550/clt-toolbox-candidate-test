<x-layouts.app>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumbs -->
        <nav class="mb-4 text-sm font-medium">
            <ol class="flex items-center space-x-2 text-gray-500">
                <li><a href="#" class="hover:text-gray-700 transition-colors">Suppliers</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">{{ $supplier->name }}</li>
            </ol>
        </nav>

        <!-- Top Profile Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:p-8 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-4 flex-wrap">
                    <h2 class="text-3xl font-bold text-gray-900 font-elegant tracking-tight">{{ $supplier->name }}</h2>
                </div>
                <p class="mt-2 text-sm font-mono text-gray-500 tracking-wider">ID: SUP-{{ $supplier->id }}</p>
            </div>
            <div class="shrink-0">
                <button type="button" onclick="openModal('editSupplierModal');prepareEditModal({{ $supplier->id }}, '{{ addslashes($supplier->name) }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                    <i class="ph ph-pencil-simple text-lg text-gray-500"></i>
                    Edit Supplier
                </button>
            </div>
        </div>

        <!-- Associated Layups Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <h3 class="text-xl font-bold text-gray-900 font-elegant tracking-tight">Associated Layups</h3>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('supplier.layup.export', $supplier) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 shadow-sm text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ph ph-download-simple text-lg text-gray-500"></i>
                    Export JSON
                </a>
                
                <form action="{{ route('supplier.layup.import', $supplier) }}" method="POST" enctype="multipart/form-data" class="hidden" id="form-import-layup">
                    @csrf
                    <input type="file" name="import_file" id="import_file_layup" accept=".json" onchange="handleLayupImport(event)">
                </form>
                <button type="button" onclick="document.getElementById('import_file_layup').click()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 shadow-sm text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ph ph-upload-simple text-lg text-gray-500"></i>
                    Import JSON
                </button>
                <button type="button" onclick="openModal('addLayupModal')" class="inline-flex items-center gap-2 px-4 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#3e7c5b] hover:bg-[#2f6348] transition-colors">
                    <i class="ph ph-plus font-bold"></i>
                    Add Layup
                </button>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="ph ph-check-circle text-xl text-green-500"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-3">
            <i class="ph ph-x-circle text-xl text-red-500"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Associated Layups Table -->
        @if($supplier->layups->isEmpty())
            <div class="w-full">
                <h1 class="italic w-fit mx-auto text-gray-500">No data</h1>
            </div>
        @else
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Layup ID</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Thickness</th>
                                <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ply Count</th>
                                <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ( $supplier->layups as $layup )
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">L-{{ $layup->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 layup-name-element">{{ $layup->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $layup->total_thickness }}</td> 
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-100 text-gray-800 text-xs font-bold border border-gray-200 shadow-sm">{{ $layup->layers_count }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('supplier.layup.edit', $layup) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>
                                        <button onclick="openModal('confirmDeleteModal');prepareDeleteModal({{ $layup->id }})" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </main>
    <x-supplier.modal-update></x-supplier.modal-update>
    <x-layup.modal-add :supplier="$supplier"></x-layup.modal-add>
    <x-layup.modal-delete></x-layup.modal-delete>
    <x-layup.modal-conflict></x-layup.modal-conflict>
</x-layouts.app>