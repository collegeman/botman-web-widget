export const emitMessage = (method, params = {}) => {
    window.parent.postMessage({
        method: 'botman-web-widget.' + method,
        params
    })
}