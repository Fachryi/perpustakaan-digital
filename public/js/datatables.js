/* global $, DataTable */

(() => {
  // Disable styling on numeric columns
  DataTable.type('num', 'className', '');
  $('[data-datatables]').DataTable({
    responsive: true,
    language: {
      url: '../../node_modules/datatables.net-plugins/i18n/id.json',
      lengthMenu: '_MENU_ entri per halaman',
      paginate: {
        first: '&laquo;',
        last: '&raquo;',
        next: '&rsaquo;',
        previous: '&lsaquo;'
      },
      searchPlaceholder: 'Cari data'
    },
    order: []
  });
})();
//# sourceMappingURL=datatables.js.map