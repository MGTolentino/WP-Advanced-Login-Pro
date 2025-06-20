/**
 * Herramienta de debugging para la integración de Google Maps
 */
(function() {
    'use strict';
    
    console.log('=== Maps Debug Tool Initialized ===');
    
    // Recopilar información del entorno
    function collectDebugInfo() {
        var info = {
            timestamp: new Date().toISOString(),
            pageUrl: window.location.href,
            googleMapsStatus: typeof google !== 'undefined' ? 'Loaded' : 'Not loaded',
            googleMapsVersion: typeof google !== 'undefined' && google.maps ? google.maps.version : 'Unknown',
            placesAPI: typeof google !== 'undefined' && google.maps && google.maps.places ? 'Available' : 'Not available',
            wpAlpMapsConfig: typeof wpAlpMaps !== 'undefined' ? wpAlpMaps : 'Not defined',
            vendorMapObject: typeof window.vendorMap !== 'undefined' ? 'Available' : 'Not available',
            initMapFunction: typeof window.initMap === 'function' ? 'Defined' : 'Not defined',
            mapContainer: document.getElementById('wp-alp-location-map') ? 'Found' : 'Not found',
            scripts: []
        };
        
        // Recopilar scripts cargados
        var scripts = document.getElementsByTagName('script');
        for (var i = 0; i < scripts.length; i++) {
            var src = scripts[i].src;
            if (src && (src.includes('maps.googleapis.com') || src.includes('vendor-location.js'))) {
                info.scripts.push({
                    index: i,
                    src: src
                });
            }
        }
        
        return info;
    }
    
    // Mostrar información en la consola
    function logDebugInfo() {
        var info = collectDebugInfo();
        console.log('=== Google Maps Integration Debug Info ===');
        console.table(info);
        
        if (info.scripts.length > 0) {
            console.log('=== Google Maps Related Scripts ===');
            console.table(info.scripts);
        } else {
            console.warn('No Google Maps related scripts found in the page!');
        }
        
        // Verificaciones adicionales
        if (typeof google === 'undefined') {
            console.error('Google object is not defined. This indicates the Google Maps API hasn\'t loaded correctly.');
        } else if (!google.maps) {
            console.error('Google Maps module is not available. The API might have loaded with errors.');
        } else if (!google.maps.places) {
            console.error('Google Maps Places library is not available. Check if the API URL includes libraries=places.');
        }
        
        if (!document.getElementById('wp-alp-location-map')) {
            console.error('Map container (#wp-alp-location-map) not found in the DOM.');
        }
        
        return info;
    }
    
    // Ejecutar después de un breve retraso
    setTimeout(function() {
        window.mapsDebugInfo = logDebugInfo();
        
        // Crear botón de depuración en la página
        var debugButton = document.createElement('button');
        debugButton.textContent = 'Maps Debug';
        debugButton.style.position = 'fixed';
        debugButton.style.bottom = '10px';
        debugButton.style.right = '10px';
        debugButton.style.zIndex = '9999';
        debugButton.style.backgroundColor = '#f44336';
        debugButton.style.color = 'white';
        debugButton.style.border = 'none';
        debugButton.style.padding = '10px 15px';
        debugButton.style.borderRadius = '4px';
        debugButton.style.cursor = 'pointer';
        
        debugButton.addEventListener('click', function() {
            window.mapsDebugInfo = logDebugInfo();
            alert('Debug info has been logged to the console. Press F12 to view.');
        });
        
        document.body.appendChild(debugButton);
    }, 3000);
    
    // Exponer funciones globalmente para pruebas manuales
    window.mapsDebug = {
        getInfo: collectDebugInfo,
        logInfo: logDebugInfo,
        reloadAPI: function() {
            var script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=' + 
                         (typeof wpAlpMaps !== 'undefined' ? wpAlpMaps.apiKey : 'YOUR_API_KEY') + 
                         '&libraries=places&callback=initMap';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
            console.log('Manual API reload attempted');
        }
    };
})();