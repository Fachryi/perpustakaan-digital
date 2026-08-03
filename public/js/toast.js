/* global $, coreui */

const TOGGLE_ATTRIBUTE = 'data-toast-toggle';
const TEXT_ATTRIBUTE = 'data-toast-text';
const COLOR_ATTRIBUTE = 'data-toast-color';
const BG_CLASS_PREFIX = 'text-bg-';
(() => {
  const toast = document.getElementById('bs-toast');
  $(`[${TOGGLE_ATTRIBUTE}]`).each((_, toastTrigger) => {
    if (toast) {
      const toastCoreUI = coreui.Toast.getOrCreateInstance(toast);
      const $toast = $(toast);
      const $toastTrigger = $(toastTrigger);
      const method = $toastTrigger.attr(TOGGLE_ATTRIBUTE) || 'show';
      const text = $toastTrigger.attr(TEXT_ATTRIBUTE) || 'Empty toast :(';
      const color = $toastTrigger.attr(COLOR_ATTRIBUTE);
      $(toastTrigger).on('click', () => {
        var _toastCoreUI$method;
        $toast.find('p').text(text);
        $toast.removeClass((_, className) => (className.match(new RegExp(`(^|\\s)${BG_CLASS_PREFIX}\\S+`, 'g')) || []).join(' '));
        if (color) {
          $toast.addClass(BG_CLASS_PREFIX + color);
        }
        toastCoreUI == null || (_toastCoreUI$method = toastCoreUI[method]) == null || _toastCoreUI$method.call(toastCoreUI);
      });
    }
  });
})();
//# sourceMappingURL=toast.js.map