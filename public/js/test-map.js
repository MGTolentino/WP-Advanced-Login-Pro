// Script de prueba para el mapa
document.addEventListener('DOMContentLoaded', function() {
    console.log('TEST-MAP: Script de prueba cargado');
    
    // Esperar un momento para asegurarnos que el DOM está completamente cargado
    setTimeout(function() {
        console.log('TEST-MAP: Verificando existencia del elemento del mapa');
        
        // Buscar el elemento del mapa de varias formas
        var mapElement = document.getElementById('wp-alp-location-map');
        console.log('TEST-MAP: Resultado de getElementById:', mapElement);
        
        var mapElementBySelector = document.querySelector('#wp-alp-location-map');
        console.log('TEST-MAP: Resultado de querySelector:', mapElementBySelector);
        
        var mapContainers = document.querySelectorAll('.wp-alp-map-wrapper');
        console.log('TEST-MAP: Número de elementos con clase wp-alp-map-wrapper:', mapContainers.length);
        
        // Mostrar los IDs de todos los elementos en la página para debugging
        var allElements = document.querySelectorAll('[id]');
        console.log('TEST-MAP: Todos los elementos con ID en la página:');
        allElements.forEach(function(el) {
            console.log('- ' + el.id);
        });
        
        if (!mapElement) {
            console.error('TEST-MAP: Elemento del mapa no encontrado');
            
            // Intentar crear un mensaje visible en el cuerpo del documento
            var errorMessage = document.createElement('div');
            errorMessage.style.position = 'fixed';
            errorMessage.style.top = '10px';
            errorMessage.style.right = '10px';
            errorMessage.style.backgroundColor = '#ff4444';
            errorMessage.style.color = 'white';
            errorMessage.style.padding = '10px';
            errorMessage.style.borderRadius = '4px';
            errorMessage.style.zIndex = '9999';
            errorMessage.textContent = 'Error: Elemento del mapa no encontrado';
            document.body.appendChild(errorMessage);
            return;
        }
        
        console.log('TEST-MAP: Elemento del mapa encontrado, aplicando estilos');
        
        // Asegurar que el mapa tiene dimensiones
        mapElement.style.height = '400px';
        mapElement.style.width = '100%';
        mapElement.style.backgroundColor = '#e5e3df';
        
        // Mensaje para confirmar visibilidad
        var messageDiv = document.createElement('div');
        messageDiv.style.position = 'absolute';
        messageDiv.style.top = '50%';
        messageDiv.style.left = '50%';
        messageDiv.style.transform = 'translate(-50%, -50%)';
        messageDiv.style.backgroundColor = 'white';
        messageDiv.style.padding = '15px';
        messageDiv.style.borderRadius = '8px';
        messageDiv.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
        messageDiv.style.zIndex = '9999';
        messageDiv.innerHTML = '<p style="margin: 0; font-weight: bold;">Test de Mapa</p>' +
                              '<p style="margin: 5px 0 0;">Si puedes ver este mensaje, el contenedor funciona correctamente.</p>';
        
        mapElement.appendChild(messageDiv);
        
        // Intentar cargar Google Maps directamente
        console.log('TEST-MAP: Intentando cargar Google Maps API');
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=' + 
                    (window.GOOGLE_MAPS_API_KEY || 'AIzaSyA6tLIy4UXGxEJoNehZYjXHVt8GnZnbjP4') + 
                    '&callback=initTestMap';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
        
        // Variable global para contar intentos
        window.mapInitAttempts = 0;
        
        // Función que se llamará cuando se cargue la API
        window.initTestMap = function() {
            console.log('TEST-MAP: Google Maps API cargada, inicializando mapa de prueba');
            
            try {
                // Crear un mapa simple
                var map = new google.maps.Map(mapElement, {
                    center: { lat: 20.6534, lng: -103.3276 },
                    zoom: 12
                });
                
                console.log('TEST-MAP: Mapa creado correctamente');
                
                // Mensaje de éxito
                var successDiv = document.createElement('div');
                successDiv.style.position = 'absolute';
                successDiv.style.bottom = '10px';
                successDiv.style.left = '10px';
                successDiv.style.backgroundColor = '#4CAF50';
                successDiv.style.color = 'white';
                successDiv.style.padding = '10px';
                successDiv.style.borderRadius = '4px';
                successDiv.style.zIndex = '9999';
                successDiv.textContent = 'Google Maps cargado correctamente';
                
                // Agregar mensaje al documento, no al mapa (que ahora tiene el mapa de Google)
                document.body.appendChild(successDiv);
            } catch (error) {
                console.error('TEST-MAP: Error al inicializar el mapa', error);
                
                // Si hay un error, intentar nuevamente con otra API key
                if (window.mapInitAttempts < 1) {
                    window.mapInitAttempts++;
                    console.log('TEST-MAP: Reintentando con API key alternativa');
                    
                    var altScript = document.createElement('script');
                    altScript.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyA6tLIy4UXGxEJoNehZYjXHVt8GnZnbjP4&callback=initTestMap';
                    altScript.async = true;
                    altScript.defer = true;
                    document.head.appendChild(altScript);
                }
            }
        };
    }, 500); // Delay de 500ms para asegurar que el DOM está listo
});