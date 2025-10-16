/**
 * Route requests to local_htmx serve.php.
 */
htmx.on('htmx:configRequest', e => {
    e.detail.path = window.location.origin + '/local/htmx/serve.php/' + e.detail.path;
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