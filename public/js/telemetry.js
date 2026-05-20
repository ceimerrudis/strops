window.addEventListener("load", () => {
    resetTelemetryCookie();
});

document.addEventListener('click', (event) => {
  const button = event.target.closest('button, input[type="submit"]');
  if (button) {
    noteButtonClick(button);
  }
}, true);

const _fetch = window.fetch;
window.fetch = function(...args) {
  return _fetch.apply(this, args).then(res => {
    resetTelemetryCookie();
    return res;
  });
};

const _send = XMLHttpRequest.prototype.send;
XMLHttpRequest.prototype.send = function(...args) {
  this.addEventListener('load', () => resetTelemetryCookie());
  return _send.apply(this, args);
};

function resetTelemetryCookie(){
    const nav = performance.getEntriesByType("navigation")[0];

    const page_metrics = {
        //total_load_ms: nav.loadEventEnd,
        dom_ready_ms: nav.domContentLoadedEventEnd,
        page_name: window.location.pathname,
        //ttfb_ms: nav.responseStart,
        
        button_log: [],
        
        screen_width: screen.width,
        screen_height: screen.height,
    };
    
    document.cookie = "telemetry_cookie=" + encodeURIComponent(JSON.stringify(page_metrics)) + "; path=/; SameSite=Lax";
}

function noteButtonClick(button) {
    const raw = document.cookie
        .split('; ')
        .find(row => row.startsWith('telemetry_cookie='))
        ?.split('=').slice(1).join('='); 
    
    if (!raw) return;
    
    const metrics = JSON.parse(decodeURIComponent(raw));
    
    metrics.button_log = metrics.button_log || [];
    metrics.button_log.push({
        button_name: button.name || 'unknown',
        time: Date.now(),
    });

    document.cookie = "telemetry_cookie=" + encodeURIComponent(JSON.stringify(metrics)) + "; path=/; SameSite=Lax";
};
