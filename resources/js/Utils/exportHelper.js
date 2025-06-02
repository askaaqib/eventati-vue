import { utils, writeFile } from 'xlsx';
import { saveAs } from 'file-saver';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

pdfMake.vfs = pdfFonts.pdfMake.vfs;

export const exportToExcel = (data, filename = 'export') => {
    const ws = utils.json_to_sheet(data);
    const wb = utils.book_new();
    utils.book_append_sheet(wb, ws, 'Sheet1');
    writeFile(wb, `${filename}.xlsx`);
};

export const exportToCSV = (data, filename = 'export') => {
    const ws = utils.json_to_sheet(data);
    const csv = utils.sheet_to_csv(ws);
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    saveAs(blob, `${filename}.csv`);
};

export const exportToPDF = (data, filename = 'export', columns) => {
    const tableBody = [
        columns.map(col => ({ text: col.title, style: 'tableHeader' }))
    ];

    data.forEach(item => {
        const row = columns.map(col => {
            return { text: item[col.title] || '', style: 'tableCell' };
        });
        tableBody.push(row);
    });

    const docDefinition = {
        content: [
            { text: filename.toUpperCase(), style: 'header' },
            {
                table: {
                    headerRows: 1,
                    widths: columns.map(() => '*'),
                    body: tableBody
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
            },
            tableCell: {
                fontSize: 11
            }
        }
    };

    pdfMake.createPdf(docDefinition).download(`${filename}.pdf`);
};

export const printTable = (data, columns) => {
    const printWindow = window.open('', '_blank');
    const tableHeaders = columns.map(col => `<th>${col.title}</th>`).join('');
    const tableRows = data.map(item => {
        const cells = columns.map(col => `<td>${item[col.title] || ''}</td>`).join('');
        return `<tr>${cells}</tr>`;
    }).join('');

    printWindow.document.write(`
    <html>
      <head>
        <title>Print</title>
        <style>
          body { font-family: Arial, sans-serif; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; }
          tr:nth-child(even) { background-color: #f9f9f9; }
        </style>
      </head>
      <body>
        <h1>${document.title}</h1>
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
