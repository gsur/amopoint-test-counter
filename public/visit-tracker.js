(function () {
    var API_URL = 'http://109.194.30.247:8502/api/visits';
    var GEO_URL = 'https://ipapi.co/json/';

    function getDeviceName() {
        var platform = navigator.platform || 'unknown-platform';
        var userAgent = navigator.userAgent || 'unknown-agent';
        return platform + ' | ' + userAgent.slice(0, 180);
    }

    function postVisit(payload) {
        try {
            var request = new XMLHttpRequest();
            request.open('POST', API_URL, true);
            request.setRequestHeader('Content-Type', 'application/json');
            request.send(JSON.stringify(payload));
        } catch (e) {
            return null;
        }
    }

    function sendVisit() {
        try {
            var geoRequest = new XMLHttpRequest();
            geoRequest.open('GET', GEO_URL, true);
            geoRequest.onreadystatechange = function () {
                if (geoRequest.readyState !== 4 || geoRequest.status < 200 || geoRequest.status >= 300) {
                    return;
                }

                var geo;

                try {
                    geo = JSON.parse(geoRequest.responseText);
                } catch (e) {
                    return;
                }

                postVisit({
                    ip: geo && geo.ip ? String(geo.ip) : '0.0.0.0',
                    city: geo && geo.city ? String(geo.city) : 'Unknown',
                    device: getDeviceName()
                });
            };
            geoRequest.send(null);
        } catch (e) {
            return null;
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sendVisit, { once: true });
    } else {
        sendVisit();
    }
})();
