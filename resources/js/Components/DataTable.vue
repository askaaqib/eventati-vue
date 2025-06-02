<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { utils, writeFile } from 'xlsx';
import { saveAs } from 'file-saver';
import pdfMake from 'pdfmake/build/pdfmake';
import * as pdfFonts from 'pdfmake/build/vfs_fonts';

// Initialize the virtual file system
try {
    pdfMake.vfs = pdfFonts.pdfMake ? pdfFonts.pdfMake.vfs : pdfFonts.default.pdfMake.vfs;
} catch (error) {
    console.error('Error initializing PDFMake fonts:', error);
    // Fallback to empty vfs if fonts fail to load
    pdfMake.vfs = {};
}

// Set the default fonts
pdfMake.fonts = {
    Roboto: {
        normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Regular.ttf',
        bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Medium.ttf',
        italics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Italic.ttf',
        bolditalics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-MediumItalic.ttf'
    }
};

const loading = ref(false);

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    exportOptions: {
        type: Object,
        default: () => ({
            pdf: true,
            excel: true,
            csv: true,
            print: true
        })
    },
    exportFileName: {
        type: String,
        default: 'export'
    }
});

const emit = defineEmits(['delete']);

const currentRoute = computed(() => usePage().url);

// Local state
const localFilters = ref({
    search: props.filters?.search || '',
    sort_by: props.filters?.sort_by || '',
    sort_dir: props.filters?.sort_dir || 'asc',
    per_page: props.filters?.per_page || 10,
});

const debouncedUpdate = debounce((key, value) => {
    updateFilters(key, value);
}, 300);

// Update URL when filters change
const updateFilters = async (key, value) => {
    loading.value = true;
    localFilters.value[key] = value;

    try {
        const response = await axios.get(currentRoute.value, {
            params: {
                ...localFilters.value,
                page: 1,
                ajax: true,
            },
        });

        props.data.data = response.data.data;
        props.data.links = response.data.links;
        props.data.meta = response.data.meta;
        props.data.from = response.data.from;
        props.data.to = response.data.to;
        props.data.total = response.data.total;
    } catch (error) {
        console.error('Filter update error:', error);
    } finally {
        loading.value = false;
    }
};

// Sort handler
const sort = (column) => {
    if (localFilters.value.sort_by === column) {
        updateFilters('sort_dir', localFilters.value.sort_dir === 'asc' ? 'desc' : 'asc');
    } else {
        updateFilters('sort_by', column);
        updateFilters('sort_dir', 'asc');
    }
};

// Export functions
const exportToExcel = () => {
    const exportData = props.data.data.map(item => {
        const exportedItem = {};
        props.columns.forEach(col => {
            if (col.exportable !== false) {
                exportedItem[col.label] = item[col.key];
            }
        });
        return exportedItem;
    });

    const ws = utils.json_to_sheet(exportData);
    const wb = utils.book_new();
    utils.book_append_sheet(wb, ws, 'Sheet1');
    writeFile(wb, `${props.exportFileName}.xlsx`);
};

const exportToCSV = () => {
    const exportData = props.data.data.map(item => {
        const exportedItem = {};
        props.columns.forEach(col => {
            if (col.exportable !== false) {
                exportedItem[col.label] = item[col.key];
            }
        });
        return exportedItem;
    });

    const ws = utils.json_to_sheet(exportData);
    const csv = utils.sheet_to_csv(ws);
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    saveAs(blob, `${props.exportFileName}.csv`);
};

const exportToPDF = () => {
    const exportData = props.data.data.map(item => {
        const row = [];
        props.columns.forEach(col => {
            if (col.exportable !== false) {
                row.push(item[col.key] || '');
            }
        });
        return row;
    });

    const headers = props.columns
        .filter(col => col.exportable !== false)
        .map(col => ({ text: col.label, style: 'tableHeader' }));

    const docDefinition = {
        content: [
            { text: props.exportFileName.toUpperCase(), style: 'header' },
            {
                table: {
                    headerRows: 1,
                    widths: props.columns
                        .filter(col => col.exportable !== false)
                        .map(() => '*'),
                    body: [
                        headers,
                        ...exportData
                    ]
                }
            }
        ],
        styles: {
            header: {
                fontSize: 18,
                bold: true,
                margin: [0, 0, 0, 10]
            },
            tableHeader: {
                bold: true,
                fontSize: 13,
                color: 'black'
            }
        }
    };

    pdfMake.createPdf(docDefinition).download(`${props.exportFileName}.pdf`);
};

const printTable = () => {
    const printWindow = window.open('', '_blank');
    const tableHeaders = props.columns
        .filter(col => col.exportable !== false)
        .map(col => `<th>${col.label}</th>`)
        .join('');

    const tableRows = props.data.data.map(item => {
        const cells = props.columns
            .filter(col => col.exportable !== false)
            .map(col => `<td>${item[col.key] || ''}</td>`)
            .join('');
        return `<tr>${cells}</tr>`;
    }).join('');

    printWindow.document.write(`
        <html>
            <head>
                <title>Print</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    h1 { margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h1>${props.exportFileName}</h1>
                <table>
                    <thead><tr>${tableHeaders}</tr></thead>
                    <tbody>${tableRows}</tbody>
                </table>
                <script>
                    window.onload = function() { window.print(); window.close(); }
                <\/script>
            </body>
        </html>
    `);
    printWindow.document.close();
};

// Add this method
const handlePaginate = async (url) => {
    loading.value = true;
    try {
        const response = await axios.get(url, {
            params: {
                ...localFilters.value,
                ajax: true,
            }
        });

        props.data.data = response.data.data;
        props.data.links = response.data.links;
        props.data.meta = response.data.meta;
        props.data.from = response.data.from;
        props.data.to = response.data.to;
        props.data.total = response.data.total;
    } catch (error) {
        console.error('Pagination error:', error);
    } finally {
        loading.value = false;
    }
};


const handleAjaxSearch = debounce(async () => {
    loading.value = true;

    try {
        const response = await axios.get(currentRoute.value, {
            params: {
                ...localFilters.value,
                page: 1,
                ajax: true,
            }
        });

        props.data.data = response.data.data;
        props.data.links = response.data.links;
        props.data.meta = response.data.meta;
        props.data.from = response.data.from;
        props.data.to = response.data.to;
        props.data.total = response.data.total;
    } catch (error) {
        console.error('Search error:', error);
    } finally {
        loading.value = false;
    }
}, 300);

</script>

<template>
    <div class="space-y-4">
        <!-- Search and Filters -->
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <div class="w-full sm:w-64">
                <input
                    type="text"
                    v-model="localFilters.search"
                    @input="handleAjaxSearch"
                    placeholder="Search..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
            </div>
            <div class="flex items-center space-x-2">
                <select
                    v-model="localFilters.per_page"
                    @change="updateFilters('per_page', localFilters.per_page)"
                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>

                <!-- Export Buttons -->
                <div class="flex space-x-2 ml-2">
                    <button
                        v-if="exportOptions.pdf"
                        @click="exportToPDF"
                        class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition flex items-center"
                        title="Export to PDF"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        PDF
                    </button>
                    <button
                        v-if="exportOptions.excel"
                        @click="exportToExcel"
                        class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition flex items-center"
                        title="Export to Excel"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </button>
                    <button
                        v-if="exportOptions.csv"
                        @click="exportToCSV"
                        class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition flex items-center"
                        title="Export to CSV"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        CSV
                    </button>
                    <button
                        v-if="exportOptions.print"
                        @click="printTable"
                        class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition flex items-center"
                        title="Print"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Rest of your DataTable template remains the same -->
        <!-- Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sr. No
                    </th>
                    <th
                        v-for="column in columns"
                        :key="column.key"
                        @click="column.sortable !== false && sort(column.key)"
                        :class="[
                            'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
                            column.sortable !== false ? 'cursor-pointer hover:bg-gray-100' : ''
                        ]"
                    >
                        <div class="flex items-center">
                            {{ column.label }}
                            <span
                                v-if="localFilters.sort_by === column.key"
                                class="ml-1"
                            >
                                {{ localFilters.sort_dir === 'asc' ? '↑' : '↓' }}
                            </span>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in data.data" :key="item.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ data.from + index }}
                    </td>
                    <td
                        v-for="column in columns"
                        :key="column.key"
                        class="px-6 py-4 whitespace-nowrap"
                    >
                        <slot :name="`cell(${column.key})`" :item="item">
                            {{ item[column.key] }}
                        </slot>
                    </td>
                </tr>
                <tr v-if="data.data.length === 0">
                    <td :colspan="columns.length" class="px-6 py-4 text-center text-gray-500">
                        No records found
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-2">
            <div class="text-sm text-gray-700">
                Showing {{ data.from }} to {{ data.to }} of {{ data.total }} entries
            </div>
            <div class="flex space-x-2">
                <button
                    v-for="link in data.links"
                    :key="link.label"
                    @click="link.url && handlePaginate(link.url)"
                    :disabled="!link.url || link.active"
                    :class="[
                        'px-3 py-1 rounded-md',
                        link.active ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100',
                        !link.url ? 'text-gray-400 cursor-not-allowed' : ''
                      ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </div>
</template>
