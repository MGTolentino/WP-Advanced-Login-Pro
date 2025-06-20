/**
 * Manejo centralizado de mapas para el formulario de vendedor
 */
(function($) {
    'use strict';
    
    // Variables globales
    var map, marker, geocoder, selectedLocation;
    var isMapInitialized = false;
    
    // Inicialización cuando el documento está listo
    $(document).ready(function() {
        // Verificar si estamos en la página correcta
        if ($('#wp-alp-location-map').length === 0) return;
        
        // Inicializar mapa solo cuando se muestre el paso de ubicación
        $(document).on('click', '.wp-alp-location-option[data-option="specific"]', function() {
            initializeMap();
        });
        
        // Manejador para el enlace "More information"
        $('#location-more-info').on('click', function(e) {
            e.preventDefault();
            $('#location-info-modal').fadeIn(300);
        });
        
        // Cerrar el modal cuando se hace clic en el botón de cierre
        $('.wp-alp-info-modal-close').on('click', function() {
            $(this).closest('.wp-alp-info-modal').fadeOut(300);
        });
        
        // Cerrar el modal cuando se hace clic fuera del contenido
        $(document).on('click', '.wp-alp-info-modal', function(e) {
            if ($(e.target).hasClass('wp-alp-info-modal')) {
                $(this).fadeOut(300);
            }
        });
        
        // Manejar el toggle de ubicación exacta
        $('#exact-location-toggle').on('change', function() {
            var isExactLocation = $(this).is(':checked');
            
            if (isExactLocation) {
                // Cambiar a ubicación exacta
                $('#approximate-tooltip').fadeOut(200);
                if (selectedLocation && selectedLocation.geometry) {
                    updateLocationDisplay(selectedLocation.geometry.location);
                } else if (map) {
                    updateLocationDisplay(map.getCenter());
                }
            } else {
                // Cambiar a ubicación aproximada
                $('#approximate-tooltip').fadeIn(200);
                if (selectedLocation && selectedLocation.geometry) {
                    updateLocationDisplay(selectedLocation.geometry.location);
                } else if (map) {
                    updateLocationDisplay(map.getCenter());
                }
            }
        });
        
        // Botón para confirmar dirección y mostrar el formulario detallado
        $('#confirm-address-btn').on('click', function() {
            // Ocultar la vista del mapa
            $('.wp-alp-location-specific-container').hide();
            
            // Mostrar el formulario de dirección detallada
            $('#address-form-container').show();
            
            // Desplazarse al inicio del contenedor
            $('html, body').animate({
                scrollTop: $('#address-form-container').offset().top - 100
            }, 300);
        });
    });
    
    // Inicialización del mapa (solo se ejecutará una vez)
    function initializeMap() {
        if (isMapInitialized) return;
        
        // Verificar que Google Maps esté disponible
        if (typeof google === 'undefined' || !google.maps) {
            console.error('Google Maps API no está disponible');
            setTimeout(initializeMap, 1000); // Reintentar en 1 segundo
            return;
        }
        
        // Coordenadas predeterminadas (se pueden ajustar)
        var defaultLocation = { lat: 20.6534, lng: -103.3276 };  // Guadalajara, México
        
        try {
            // Crear el mapa
            map = new google.maps.Map(document.getElementById('wp-alp-location-map'), {
                center: defaultLocation,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false
            });
            
            // Crear el marcador
            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true
            });
            
            // Crear el geocoder para búsquedas
            geocoder = new google.maps.Geocoder();
            
            // Inicializar autocompletado
            initAddressAutocomplete();
            
            // Actualizar posición del marcador cuando se arrastra
            marker.addListener('dragend', function() {
                var position = marker.getPosition();
                updateLocationDisplay(position);
                
                // Mostrar el botón de confirmar
                $('.wp-alp-confirm-address-btn').fadeIn(300);
                
                // Geocodificar inverso para obtener la dirección
                geocoder.geocode({ 'location': position }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        selectedLocation = results[0];
                        $('#wp-alp-address-input').val(results[0].formatted_address);
                    }
                });
            });
            
            // Marcar como inicializado
            isMapInitialized = true;
            
            // Mostrar elementos visuales
            $('.wp-alp-house-marker, .wp-alp-approximate-tooltip').show();
            
            console.log('Mapa inicializado correctamente');
        } catch (e) {
            console.error('Error al inicializar el mapa:', e);
        }
    }
    
    // Inicializa el autocompletado para la dirección
    function initAddressAutocomplete() {
        if (typeof google === 'undefined' || !google.maps || !google.maps.places) {
            console.error('Google Maps Places API no está disponible');
            return;
        }
        
        var addressInput = document.getElementById('wp-alp-address-input');
        if (!addressInput) return;
        
        try {
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
                
                // Guardar la ubicación seleccionada
                selectedLocation = place;
                
                // Actualizar el mapa con la nueva ubicación
                if (map) {
                    map.setCenter(place.geometry.location);
                    marker.setPosition(place.geometry.location);
                    
                    // Mostrar el botón de confirmar
                    $('.wp-alp-confirm-address-btn').fadeIn(300);
                }
                
                console.log("Ubicación seleccionada:", place.formatted_address);
            });
            
            console.log('Autocompletado inicializado correctamente');
        } catch (e) {
            console.error('Error al inicializar autocompletado:', e);
        }
    }
    
    // Función para actualizar la visualización de la ubicación
    function updateLocationDisplay(position) {
        if (!map || !marker) return;
        
        map.setCenter(position);
        marker.setPosition(position);
    }
    
    // Exponer funciones al ámbito global si es necesario
    window.vendorMap = {
        initializeMap: initializeMap,
        updateLocationDisplay: updateLocationDisplay
    };
    
})(jQuery);