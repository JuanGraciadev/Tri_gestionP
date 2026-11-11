/**
 * TRIGESTION - Utilidades JavaScript globales
 */
window.APP = window.APP || {};
window.APP.csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
window.APP.routes = window.APP.routes || {};

/**
 * Formato de moneda en pesos colombianos ($)
 */
window.APP.formatMoney = function (amount) {
    const val = typeof amount === 'number' ? amount : parseFloat(String(amount).replace(/[^0-9.-]+/g, '')) || 0;
    return '$' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

/**
 * Petición fetch con cabeceras CSRF y JSON automáticas
 */
window.APP.fetchJson = async function (url, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': window.APP.csrf,
        ...(options.headers || {})
    };
    if (options.body && typeof options.body === 'object' && !(options.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(options.body);
    }
    const response = await fetch(url, { ...options, headers });
    return response.json();
};

/**
 * Diálogo modal de confirmación con SweetAlert2
 */
window.APP.confirmAction = function (title, text, confirmText = 'Sí, continuar', icon = 'warning') {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#64748b',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'rounded-[2rem] font-sans' }
    });
};

/**
 * Toast flotante
 */
window.APP.toast = function (title, icon = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    return Toast.fire({ icon, title });
};

