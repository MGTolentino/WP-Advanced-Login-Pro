/**
 * Script de prueba para verificar la carga correcta
 */
console.log('===== MAPS TEST SCRIPT LOADED =====');
console.log('Timestamp: ' + new Date().toISOString());
console.log('Page URL: ' + window.location.href);

// Crear un elemento visual para confirmar la carga
document.addEventListener('DOMContentLoaded', function() {
    // Crear un div de prueba para mostrar visualmente
    var testDiv = document.createElement('div');
    testDiv.style.position = 'fixed';
    testDiv.style.top = '10px';
    testDiv.style.right = '10px';
    testDiv.style.backgroundColor = '#ff0000';
    testDiv.style.color = '#ffffff';
    testDiv.style.padding = '10px';
    testDiv.style.zIndex = '9999';
    testDiv.style.borderRadius = '5px';
    testDiv.style.fontFamily = 'Arial, sans-serif';
    testDiv.textContent = 'Maps Test Script Loaded';
    
    // Añadir al body
    document.body.appendChild(testDiv);

    // Intentar verificar si Google Maps está disponible
    if (typeof google !== 'undefined' && google.maps) {
        testDiv.textContent += ' - Google Maps OK';
        testDiv.style.backgroundColor = '#00aa00';
    } else {
        testDiv.textContent += ' - Google Maps NO';
    }
    
    // Mostrar información sobre otros scripts
    console.log('Scripts en la página:');
    var scripts = document.getElementsByTagName('script');
    for (var i = 0; i < scripts.length; i++) {
        if (scripts[i].src && 
            (scripts[i].src.includes('googleapis.com') || 
             scripts[i].src.includes('maps-') || 
             scripts[i].src.includes('vendor-'))) {
            console.log(i + ': ' + scripts[i].src);
        }
    }
});