/**
 * Prepend the local_htmx request root to HTMX requests
 * that don't already have it.
 */
document.body.addEventListener('htmx:configRequest', e => {
    if (!e.detail.path.includes('/local/htmx/serve.php')) {
        e.detail.path.replace(/^\//, '');
        e.detail.path = `/local/htmx/serve.php/${e.detail.path}`;
    }
});