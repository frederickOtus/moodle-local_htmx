/**
 * Prepend the local_htmx request root to HTMX requests
 * that don't already have it.
 */
htmx.on('htmx:configRequest', e => {
    if (!e.detail.path.includes('/local/htmx/serve.php')) {
        e.detail.path.replace(/^\//, '');
        e.detail.path = `/local/htmx/serve.php/${e.detail.path}`;
    }
});

/**
 * Store any HTMX errors that get triggered before we're ready to
 * handle them.
 * 
 * @param {Event} e
 */
const bufferHtmxErrors = e => {
    if (!window.htmxErrorBuffer) {
        window.htmxErrorBuffer = [];
    }
    window.htmxErrorBuffer.push(e);
};

document.body.addEventListener('htmx:responseError', bufferHtmxErrors);

/**
 * When the error modal is ready, go ahead and trigger any buffered
 * response errors we captured.
 */
document.body.addEventListener('local_htmx:errorModalReady', () => {
    document.body.removeEventListener('htmx:responseError', bufferHtmxErrors);
    if (window.htmxErrorBuffer) {
        window.htmxErrorBuffer.forEach(e => document.body.dispatchEvent(e));
    }
    delete window.htmxErrorBuffer;
});