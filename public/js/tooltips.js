/* global coreui */

(() => {
  const tooltipTriggerList = document.querySelectorAll('[data-coreui-toggle="tooltip"]');
  [...tooltipTriggerList].map(tooltipTriggerEl => new coreui.Tooltip(tooltipTriggerEl));
})();
//# sourceMappingURL=tooltips.js.map