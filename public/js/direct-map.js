/**
 * Script directo para inicializar Google Maps
 * Sin dependencias de WordPress o plugins
 */
(function() {
    'use strict';
    
    // Función para inicializar el mapa
    function initializeMap() {
        console.log('Inicializando mapa directamente');
        
        var mapElement = document.getElementById('wp-alp-location-map');
        if (!mapElement) {
            console.error('Elemento del mapa no encontrado');
            return;
        }
        
        // Coordenadas predeterminadas (Guadalajara, México)
        var defaultLocation = { lat: 20.6534, lng: -103.3276 };
        
        try {
            // Crear el mapa
            var map = new google.maps.Map(mapElement, {
                center: defaultLocation,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false
            });
            
            // Crear el marcador
            var marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true
            });
            
            // Inicializar el geocoder
            var geocoder = new google.maps.Geocoder();
            
            // Inicializar autocompletado
            var addressInput = document.getElementById('wp-alp-address-input');
            if (addressInput) {
                var autocomplete = new google.maps.places.Autocomplete(addressInput, {
                    types: ['address']
                });
                
                // Cuando se selecciona una dirección
                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        console.log("No se encontraron detalles para: " + place.name);
                        return;
                    }
                    
                    // Actualizar el mapa con la nueva ubicación
                    map.setCenter(place.geometry.location);
                    marker.setPosition(place.geometry.location);
                    
                    // Mostrar el botón de confirmar
                    var confirmBtn = document.getElementById('confirm-address-btn');
                    if (confirmBtn) {
                        confirmBtn.style.display = 'block';
                    }
                    
                    console.log("Ubicación seleccionada:", place.formatted_address);
                });
            }
            
            // Actualizar posición del marcador cuando se arrastra
            marker.addListener('dragend', function() {
                var position = marker.getPosition();
                
                // Mostrar el botón de confirmar
                var confirmBtn = document.getElementById('confirm-address-btn');
                if (confirmBtn) {
                    confirmBtn.style.display = 'block';
                }
                
                // Geocodificar inverso para obtener la dirección
                geocoder.geocode({ 'location': position }, function(results, status) {
                    if (status === 'OK' && results[0] && addressInput) {
                        addressInput.value = results[0].formatted_address;
                    }
                });
            });
            
            // Botón para confirmar dirección y mostrar el formulario detallado
            var confirmBtn = document.getElementById('confirm-address-btn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    // Ocultar la vista del mapa
                    var mapContainer = document.querySelector('.wp-alp-location-specific-container');
                    if (mapContainer) {
                        mapContainer.style.display = 'none';
                    }
                    
                    // Mostrar el formulario de dirección detallada
                    var addressForm = document.getElementById('address-form-container');
                    if (addressForm) {
                        addressForm.style.display = 'block';
                        
                        // Desplazarse al inicio del contenedor
                        addressForm.scrollIntoView({behavior: 'smooth', block: 'start'});
                    }
                });
            }
            
            // Mostrar elementos visuales
            var houseMarker = document.querySelector('.wp-alp-house-marker');
            var tooltip = document.querySelector('.wp-alp-approximate-tooltip');
            
            if (houseMarker) houseMarker.style.display = 'block';
            if (tooltip) tooltip.style.display = 'block';
            
            console.log('Mapa inicializado correctamente');
        } catch (e) {
            console.error('Error al inicializar el mapa:', e);
        }
    }
    
    // Función para cargar Google Maps API
    function loadGoogleMapsAPI() {
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDpsMEKzmfP8OtmuKzYpsuJAM3eHRrf4TU&libraries=places';
        script.async = true;
        script.defer = true;
        script.onload = function() {
            console.log('Google Maps API cargada directamente');
            // Inicializar el mapa cuando se cargue la API
            initializeMap();
        };
        document.head.appendChild(script);
    }
    
    // Inicializar cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Documento listo, verificando si estamos en la página de ubicación');
        
        // Verificar si estamos en la página correcta
        if (document.getElementById('wp-alp-location-map')) {
            console.log('Contenedor del mapa encontrado, configurando manejadores de eventos');
            
            // Verificar si la opción específica ya está seleccionada
            var specificOption = document.querySelector('.wp-alp-location-option[data-option="specific"]');
            if (specificOption && specificOption.classList.contains('selected')) {
                console.log('Ubicación específica ya seleccionada, cargando el mapa');
                loadGoogleMapsAPI();
            }
            
            // Configurar el manejador de click para la opción de ubicación específica
            document.addEventListener('click', function(e) {
                if (e.target && e.target.closest('.wp-alp-location-option[data-option="specific"]')) {
                    console.log('Opción de ubicación específica clickeada, cargando el mapa');
                    loadGoogleMapsAPI();
                }
            });
            
            // Configurar el enlace "Más información"
            var infoLink = document.getElementById('location-more-info');
            if (infoLink) {
                infoLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    var modal = document.getElementById('location-info-modal');
                    if (modal) modal.style.display = 'block';
                });
            }
            
            // Configurar el cierre del modal
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('wp-alp-info-modal-close')) {
                    var modal = e.target.closest('.wp-alp-info-modal');
                    if (modal) modal.style.display = 'none';
                }
                
                // Cerrar el modal si se hace clic fuera del contenido
                if (e.target && e.target.classList.contains('wp-alp-info-modal')) {
                    e.target.style.display = 'none';
                }
            });
        }
    });
})();