let map;
let lastinfowindow;
let overlay;

class GMap {
    innerMap;
    directionsService;
    overlay;
    elementId;
    _routeSelectorCallback;
    directionsRenderers;
    markers;
    markersInitialConf;
    lastPlacesSearch;
    autocompleteService;
    placesService;
    autocompleteInput;

    constructor(elementId = 'gooMap', conf = {}) {
        let mapOptions = {
            zoom: conf.zoom || 18,
            mapTypeId: conf.mapType || google.maps.MapTypeId.HYBRID
        }

        this.directionsService = new google.maps.DirectionsService();
        this.innerMap = new google.maps.Map(document.getElementById(elementId), mapOptions);
        this.overlay = null;
        this.elementId = elementId;
        this.directionsRenderers = [];
        this.markers = [];
        this.markersInitialConf = [];
        this.lastPlacesSearch = '';
        this.autocompleteService = null;
        this.placesService = null;
        this.autocompleteInput = null;
       
        // Eventos iniciales
        let that = this;
        google.maps.event.addListener(this.innerMap, 'zoom_changed', function() {

            const initialZoom = mapOptions.zoom;
            const minSize = 40;
            const maxSize = 150;
            const actualZoom = that.innerMap.getZoom();
            
            that.markers.forEach((marker, ix) => {
                const initialSize = that.markersInitialConf[ix].icon.scaledSize.width;
                let calculatedSize = initialSize / initialZoom * actualZoom;
                
                if(calculatedSize > maxSize) calculatedSize = maxSize;
                if(calculatedSize < minSize) calculatedSize = minSize;    
                
                marker.setIcon({
                    url: marker.getIcon().url,
                    scaledSize: new google.maps.Size(calculatedSize, calculatedSize),
                    labelOrigin: new google.maps.Point(calculatedSize / 2, calculatedSize + 7),
                }); 
            });       
        });
    }

    setCenter(lat, lang, zoom) {
        this.innerMap.setCenter(new google.maps.LatLng(lat, lang));
        this.innerMap.setZoom(zoom);
    }

    getZoom() {
        return this.innerMap.getZoom();
    }

    addMarker(lat, lang, title, body = '', show = true, color = "red", iconText = null, iconName = null) {
        return this.createMarker(Number(lat), Number(lang), {
            title : title,
            body : body,
            show : show,
            color : color,
            iconText : iconText,
            iconName : iconName,
            global : true
        });
    }

    /**
     * 
     * @param {Number} lat latitud
     * @param {Number} lang longitud
     * @param {Obejct} markerOptions Opciones del marcador
     * - title : Titulo del marcador
     * - body : Cuerpo del marcador
     * - show : Mostrar el marcador
     * - color : Color del marcador
     * - iconText : Texto del icono
     * - iconName : Nombre del icono
     * - height : Altura del icono
     * - width : Ancho del icono
     * - mode : Tipo de marcador(sliding, normal)
     * - global : Incluir o no dentro de los marcadores globales
     * @returns {google.maps.Marker}
     */
    createMarker(lat, lang, markerOptions = {}) {

        const markerConf = {
            position: new google.maps.LatLng(Number(lat), Number(lang)),
            map: this.innerMap,
            title: markerOptions.title,
            label: {
                text: markerOptions.iconText || ' ',
                color: '#EEEEEE',
                fontSize: '13px',
                fontWeight: '500',
                className: 'bordered wrap-sm',
            },
            icon: {
                //Lista parcial de los iconos viejos: https://stackoverflow.com/questions/31197596/google-map-api-marker-icon-url
                url: "/js/gmap/icons/" + (markerOptions.iconName == null ? (markerOptions.color || 'red') + '-dot' : markerOptions.iconName) + ".png",
                scaledSize: new google.maps.Size(markerOptions.height || 32, markerOptions.width || 32),
                labelOrigin: new google.maps.Point((markerOptions.height || 32) / 2, (markerOptions.width || 32) + 7),
            }
        };
        let marker;

        if(markerOptions.mode == 'sliding') {
            marker = new SlidingMarker(markerConf);
        }
        else {
            marker = new google.maps.Marker(markerConf);
        }

        let infowindow = new google.maps.InfoWindow({
            content: `<div class="mapBody"><h6>${markerOptions.title}</h6>${markerOptions.body}</div>`,
            maxWidth: 500
        });

        //Eventlistener for InfoWindow
        let innerMap = this.innerMap;
        google.maps.event.addListener(marker, 'click', function () {
            if (lastinfowindow) { lastinfowindow.close(); }

            infowindow.open(innerMap, marker);
            lastinfowindow = infowindow;
        });

        if (markerOptions.show) {
            google.maps.event.trigger(marker, 'click');
        }

        if(markerOptions.global !== false) {
            this.markers.push(marker);
            this.markersInitialConf.push(markerConf);
        }

        return marker;
    }

    addSearchBox(autocompleteInputId) {

        // Con ayuda de https://dzone.com/articles/implement-and-optimize-autocomplete-with-google-pl

        const map = this;
        const innerMap = map.innerMap;
        const requiredFields = ['name', 'geometry'];
        let currentMarker = null;
        let sessionToken;
        
        map.autocompleteService = new google.maps.places.AutocompleteService();
        map.placesService = new google.maps.places.PlacesService(this.innerMap);
        sessionToken = new google.maps.places.AutocompleteSessionToken();

        const autocompleteInput = document.getElementById(autocompleteInputId);
        autocompleteInput.setAttribute("autocomplete", "off");
        map.autocompleteInput = autocompleteInput;
        
        const deleteButton = document.createElement("SPAN");
        deleteButton.className = 'autocomplete-delete';
        deleteButton.innerHTML = 'X';
        deleteButton.addEventListener('click', () => {
            autocompleteInput.value = '';
            autocompleteResults.innerHTML = '';
            autocompleteResults.style.display = 'none';
        });

        innerMap.controls[google.maps.ControlPosition.TOP_RIGHT].push(deleteButton);
        innerMap.controls[google.maps.ControlPosition.TOP_RIGHT].push(autocompleteInput);

        const styles = document.createElement('style');
        styles.innerHTML = `
            #autocomplete-results {
                right: 0px;
                top: 0px;
                position: absolute;
                background-color: white;
                width: 50%;
                padding: 5px;
                border: 1px solid rgb(210, 210, 210);
                margin-right: 25px;
                margin-top: 50px;
                border-radius: 5px;
                display: none;
            }

            .autocomplete-item {
                padding: 5px;
                height: 30px;
                line-height: 20px;
                border-top: 1px solid #d9d9d9;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                text-align: left;
                font-size: 14px;
                cursor: pointer;
            }
            
            .autocomplete-item:hover {
                background-color: #f5f5f5;
            }

            #autocomplete-results .autocomplete-item:first-child {
                border-top: none;
            }
            
            .autocomplete-delete {
                position: absolute !important;
                right: 40px !important;
                top: 12px !important;
                background-color: lightgray;
                padding: 4px 7px;
                border-radius: 100%;
                cursor: pointer;
            }
            
            .autocomplete-delete:hover {
                background-color: #dcdcdc;
                font-weight: bold;
            }
        `;
        document.getElementsByTagName('head')[0].appendChild(styles);

        const autocompleteResults = document.createElement("UL");
        autocompleteResults.id = 'autocomplete-results';
        document.getElementById(map.elementId).appendChild(autocompleteResults);

        autocompleteInput.addEventListener('input', function() {
            autocompleteResults.innerHTML = '<div style= "text-align: center"><i class= "fa fa-2x fa-solid fa-circle-notch fa-spin" style= "margin: 5px 0px;"></i><br><span style= "font-size: 14px">Cargando&hellip;</span></div>';
            autocompleteResults.style.display = 'block';
        });

        autocompleteInput.addEventListener('input', debounce(function() {
            const busqueda = this.value
                                .toLowerCase()
                                .normalize("NFD")
                                .replace(/[\u0300-\u036f]/g, "")
                                .replace('"', '\\"')
                                .replace(/^\s+|\s+$/g, '')
                                .trim();
            
            if(map.lastPlacesSearch == busqueda || busqueda.length <= 3)  {
                autocompleteResults.innerHTML = '';
                autocompleteResults.style.display = 'none';
                return;
            }

            let autocompleteRequest = { 
                input : busqueda, 
                sessionToken : sessionToken,
                componentRestrictions : { country : 'mx' },
                types : ['geocode']
            };

            if(map.markers.length > 0) {
                autocompleteRequest.location = new google.maps.LatLng(map.markers[0].internalPosition.lat(),
                    map.markers[0].internalPosition.lng());
                    autocompleteRequest.radius = 50000;
                }
            else if(currentMarker != null) {
                autocompleteRequest.location = new google.maps.LatLng(currentMarker.internalPosition.lat(),
                currentMarker.internalPosition.lng());
                autocompleteRequest.radius = 50000;
            }

            map.lastPlacesSearch = busqueda;
            map.autocompleteService.getPlacePredictions(autocompleteRequest, mostrarSugerencias);
        }, 550));

        function mostrarSugerencias(predictions, status) {

            if(status != google.maps.places.PlacesServiceStatus.OK) {
                autocompleteResults.innerHTML = '';
                autocompleteResults.style.display = 'none';
                return;
            }

            let resultsHtml = [];
            predictions.forEach(function(prediction) {
                resultsHtml.push(`<li class= "autocomplete-item" data-type= "place" data-place-id= ${prediction.place_id}><span class="autocomplete-text">${prediction.description}</span></li>`);
            });
            autocompleteResults.innerHTML = resultsHtml.join("");
            autocompleteResults.style.display = 'block';

            let bounds = new google.maps.LatLngBounds();
            let autocompleteItems = autocompleteResults.querySelectorAll('.autocomplete-item');

            for (let autocompleteItem of autocompleteItems) {

                autocompleteItem.addEventListener('click', function() {
                    
                    const selectedText = this.querySelector('.autocomplete-text').textContent;
                    const placeId = this.getAttribute('data-place-id');

                    map.placesService.getDetails({ placeId: placeId, fields: requiredFields }, function(place, status) {

                        if (status == google.maps.places.PlacesServiceStatus.OK) {
                            if (!place.geometry) {
                                console.warn("Returned place contains no geometry");
                                return;                                
                            }
                        }

                        currentMarker = new google.maps.Marker({
                            map: innerMap,
                            icon: {
                               url: "/js/gmap/icons/pin.png",
                               size: new google.maps.Size(85, 85),
                               origin: new google.maps.Point(0, 0),
                               anchor: new google.maps.Point(17, 34),
                               scaledSize: new google.maps.Size(50, 50)
                            },
                            title: place.name,
                            label: {
                                text: place.name,
                                color: '#EEEEEE',
                                fontSize: '13px',
                                fontWeight: '500',
                                className: 'bordered wrap-sm',
                            },
                            position: place.geometry.location,
                            clickable: true
                        });

                        map.setCenter(place.geometry.location.lat(), place.geometry.location.lng(), map.getZoom());
                        
                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } 
                        else {
                            bounds.extend(place.geometry.location);
                        }
                    });

                    autocompleteInput.value = selectedText;
                    autocompleteResults.style.display = 'none';
                });
            }
        }
    }

    getAddress(latLng, _callback) {
        let geocoder = new google.maps.Geocoder();
        let result = null;
		let geocoderObject = {};

		if(typeof latLng == 'string') {
			geocoderObject = { address: latLng }
		}
		else {
			geocoderObject = { location: latLng };
		}

        geocoder.geocode(geocoderObject, (results, status) => {
            if (status === "OK") {
                if (results[0]) {
                    result = results[0].formatted_address;
                    _callback(results[0]);
                }
            }
        });
    }

    addOverlayMessage(text = '') {

        let trigger = false;

        if(this.overlay) {
            trigger = true;
            this.removeOverlayMessage();
        }

        google.maps.event.addListenerOnce(this.innerMap, 'idle', () => {
            this.overlay = new CustomOverlay(this.innerMap.getBounds(), text);
            this.overlay.setMap(this.innerMap);
        });
        this.disableDrag();

        if(trigger) {
            google.maps.event.trigger(this.innerMap, 'idle');
        }

        return this.overlay;
    }

    removeOverlayMessage() {
        if(this.overlay) {
            this.overlay.hide();
        }
        this.enableDrag();
    }

    enableDrag() {
        this.innerMap.setOptions({ gestureHandling: "cooperative", keyboardShortcuts: true });
    }

    disableDrag() {
        this.innerMap.setOptions({ gestureHandling: "none", keyboardShortcuts: false });
    }

    getCurrentLocation(_callback, silent = true) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
                    _callback(pos);
                },
                () => {
                    if(!silent) {
                        alert("Ocurrio un error al localizar su ubicación");
                    }
                }
            );
        }
        else if (!silent) {
            alert("El navegador no cuenta con servicio de geolocalización");
        }

        _callback(false);
    }
    
    /**
     * Consigue las direcciones para una ruta redonda a carro
     * @param {google.maps.LatLng} home - punto de partida y llegada
     * Referirse a getDirections() para la descripción de los demás parámetros
     * @param {object } options
     *      - silent : boolean - si es true no muestra un alert de error
     *      - optimizeWaypoints : boolean - reordenar puntos
     *      - renderConf : Object - Opciones de renderizado que tomara renderRoute()
     */
    getRoundCarTripDirections(home, waypoints, departureTime, _callback, _routeSelectorCallback, options = {}) {

        if(departureTime.getTime() < (new Date()).getTime()) {

            if(!options.silent) {
               alert("El cálculo de tiempos puede variar a un mayor grado ya que la hora de partida configurada [" + departureTime.toTimeString().substr(0, 8) + "] es antes de la actual.");
            }
    
            departureTime = new Date();
        }

        return this.getDirections(home, home, waypoints, "DRIVING", departureTime, _callback, _routeSelectorCallback, options);
    }

    /**
     * Consigue las direcciones de dada ruta y la renderiza en el mapa
     *
     * @param {google.maps.LatLng} start  - punto de partida
     * @param {google.maps.LatLng} end - punto de llegada
     * @param {google.maps.LatLng[]} waypoints - arreglo de paradas 
     * @param {string} travelMode - tipo de viaje para calculos de tiempo (DRIVING, BICYCLING, TRANSIT, WALKING), este parametro definira el tipo de rutas que se tomaran (carreteras, calles amplias, pasos peatonales, carriles de bicicleta)
     * @param {Date} departureTime - hora de salida
     * @param {CallableFunction} _callback - función a ejecutar al obtener un resultado, los parametros son el exito o no y el objeto JSON de la respuesta
     * @param {CallableFunction|Boolean} _routeSelectorCallback - Falso si se deshabilita la opción. Para habilitar la opción es necesario pasar como argumento una función callback que tome como argumento el indice seleccionado.
     * @param {object } options
     *      - silent : boolean - si es true no muestra un alert de error
     *      - optimizeWaypoints : boolean - reordenar puntos
     *      - renderConf : Object - Opciones de renderizado que tomara renderRoute()
     * @returns Promise
     */
    getDirections(start, end, waypoints, travelMode, departureTime, _callback, _routeSelectorCallback = false, options = {}) {

        if(!start) return;
        if(!end) return;
        if(!travelMode) return;

        this._routeSelectorCallback = _routeSelectorCallback;

        const gMap = this;

        let wayPointsRequest = waypoints.map(waypoint => ({
            location : waypoint,
            stopover : true,
        }));

        const request = {
            origin: start,
            destination: end,
            travelMode: travelMode,
            transitOptions: {
                departureTime: departureTime,
                modes: ["BUS", "RAIL", "SUBWAY", "TRAIN", "TRAM"],
                routingPreference: "FEWER_TRANSFERS"
            },
            drivingOptions: {
                departureTime: departureTime,
                trafficModel: "bestguess"
            },
            unitSystem: google.maps.UnitSystem.METRIC,
            waypoints: wayPointsRequest,
            optimizeWaypoints: options.optimizeWaypoints == undefined ? true : options.optimizeWaypoints,
            provideRouteAlternatives: options.provideRouteAlternatives == undefined ? true : options.provideRouteAlternatives,
            avoidFerries: false,
            avoidHighways: false,
            avoidTolls: false,
            region: "MX"
        };

        return this.directionsService.route(request, function (result, status) {

            let exito = false;

            switch(status) {
                case "OK":
                    exito = true;
                    gMap.renderRoute(result, 0, options.renderConf);

                    if(gMap._routeSelectorCallback) {
                        gMap.renderizarOpcionesRuta(result.routes.length, _routeSelectorCallback, 0);
                    }
                    break;
                case "NOT_FOUND":
                    alert("La ubicación de uno de los puntos de venta es inválida.");
                    break;
                case "ZERO_RESULTS":
                    alert("No hay resultados para esta ruta.");
                    break;
                case "MAX_WAYPOINTS_EXCEEDED":
                    alert("Máximo de puntos de venta por ruta superado.");
                    break;
                case "MAX_ROUTE_LENGTH_EXCEEDED":
                    alert("Máximo de tamaño de ruta superado.");
                    break;
                case "INVALID_REQUEST":
                    alert("Error de solicitud. Consulte al administrador.");
                    break;
                case "OVER_QUERY_LIMIT":
                    alert("Servicio de mapas fuera de servicio. Por favor espere un momento y reintente.");
                    break;
                case "REQUEST_DENIED":
                    alert("Error al contactar con el servicio. Consulte al administrador.");
                    break;
                case "UNKNOWN_ERROR":
                    alert("Parece que hubo un error. Por favor, reintente.");
            }

            _callback(exito, result);
        });
    }

    /**
     * @function
     * Mostrar rutas disponibles
     * @param {number} n - Número de rutas a mostrar
     * @param {CallableFunction} _callback - Acción a realizar despues de seleccionar una ruta
     * @param {number} selected - Índice base 0 de la ruta seleccionada
     */
    renderizarOpcionesRuta(n, _callback, selected = 0) {
        if(this._routeSelectorCallback === false) return;

        let routeSelector = document.getElementById('route-selector');

        if(routeSelector == null) {
            const div = document.createElement("DIV");
            div.id = 'route-selector';
            document.getElementById(this.elementId).parentElement.appendChild(div);
            routeSelector = div;
        }

        routeSelector.style = 'display: flex; justify-content: space-evenly; margin-top: 10px';
        routeSelector.innerHTML = '';

        for(i = 0; i < n; i++) {
            const label = document.createElement('LABEL');
            label.style = 'flex: 0 1 auto; text-align: center';
            label.classList.add('btn');
            label.classList.add('btn-default');
            label.classList.add('mx-4');
            label.textContent = (i + 1);
            label.setAttribute('for', 'ruta' + i);

            label.onclick = function() {
                for(i = 0; i < n; i++) {
                    document.querySelector('label[for=ruta' + i + ']').classList.remove('active');
                }
                this.classList.add('active');
                _callback(i);
            };
            
            if(i == selected) {
                label.classList.add('active');
            }
            
            routeSelector.appendChild(label);
        }
    }

    /**
     * Renderiza en el mapa un objeto DirectionsResult válido.
     * Este objeto es el fomato devuelto por la función google.maps.DirectionsService.route()
     * @param {Object} directionsResultObject - Datos a renderizar
     * @param {number} routeIndex - El número de ruta
     * @param {Object} options - Opciones de renderizado de @code{ google.maps.DirectionsRenderer }
     *      - @param {Boolean}  draggable - Si se puede arrastrar la ruta
     *      - @param {Boolean}  hideRouteList - Si se oculta la lista de rutas
     *      - @param {Boolean}  preserveViewport - Si se mantiene la vista del mapa
     *      - @param {Boolean}  suppressMarkers - Si se ocultan los marcadores
     *      - @param {Boolean}  suppressInfoWindows - Si se ocultan las ventanas de información
     *      - @param {Boolean}  suppressBicyclingLayer - Si se oculta la capa de bicicletas
     *      - @param {MarkerOptions} markerOptions - Opciones de marcador
     *      - @param {PolylineOPtions} polylineOptions - Opciones de línea
     *      - @param {HTMLElement} panel - Panel donde se renderiza la ruta
     * 
     *      Una opción extra es
     *      - @param {Boolean}  overwrite - Si se sobreescribe la ruta actual
     */
    renderRoute(directionsResultObject, routeIndex = 0, options = {}) {

        const overwrite = options.overwrite == undefined ? true : options.overwrite;
        let renderer = new google.maps.DirectionsRenderer(options);
        let rendererIx = null;

        if(overwrite) {
            rendererIx = 0;
        }
        else {
            for(let i = 0; i < this.directionsRenderers.length; i++) {
                if(this.directionsRenderers[i].getMap() == null) {
                    rendererIx = i;
                    break;
                }
            }
        }
        
        if(rendererIx == null) {
            this.directionsRenderers.push(renderer);
        }
        else {
            this.directionsRenderers[rendererIx] = renderer;
        }
        
        renderer.setRouteIndex(routeIndex);
        renderer.setMap(this.innerMap);
        renderer.setDirections(directionsResultObject);
        
        if(typeof this._routeSelectorCallback == 'function') {
            this.renderizarOpcionesRuta(directionsResultObject.routes.length, this._routeSelectorCallback, routeIndex);
        }
    }

    /**
     * Revierte del estado de renderización de rutas.
     */
    removeRoutes() {
        this.directionsRenderers.forEach(directionsRenderer => {
            directionsRenderer.setMap(null);
        });
    }

    /**
     * Crea un mapa con el proposito de localizar una ubicación a traves de un marcador
     * @param String name - ID del hidden-input donde se almacenara el valor de la coordenada en formato "latitud,longitud"
     *      El padre de dicho input servira como contenedor del mapa creado.
     * @param String latitud - Valor inicial para la latitud del marcador e input
     * @param String longitud - Valor inicial para la longitud del marcador e input
     * @param String config - Configuración del mapa
     * - mapStyle : Estilo de CSS usado en el mapa. Predetermina a un tamaño de 700x350
     * - _callback : Acción a realizar después de seleccionar un punto en el mapa
     * - ... : Paraámetros de configuración de google.maps.Map
     */
    static createPinpointMap(id, latitud, longitud, conf = {}) {

        const mapDiv = document.createElement('DIV');
        mapDiv.id = id + '_GMAP';
        mapDiv.style = conf.mapStyle || 'height: 400px; width: 700px';
    
        const searchbox = document.createElement('INPUT');
        searchbox.id = id + '_SEARCHBOX';
        searchbox.placeholder = 'Buscar dirección';
        searchbox.type = 'text';
        searchbox.style.width = '50%';
        searchbox.style.margin = '5px';
        searchbox.classList.add('controls', 'form-control');
    
        const container = document.querySelector("input#" + id + "[type=hidden]").parentElement;
        container.appendChild(searchbox);
        container.appendChild(mapDiv);
    
        let map = new GMap(id + '_GMAP', conf);
        let marker = null;
    
        map.addSearchBox(id + '_SEARCHBOX');
    
        if(latitud && longitud) {
            moverMarcador(new google.maps.LatLng(latitud, longitud));
            map.setCenter(latitud, longitud, map.getZoom());
        }
        else {
            map.getCurrentLocation(latLng => {
                if(latLng) {
                    moverMarcador(latLng);
                    map.setCenter(latLng.lat(), latLng.lng(), map.getZoom());
                }
                else {
                    map.setCenter(19.024, -98.626, 16);
                }
            });
        }
    
        google.maps.event.addListener(map.innerMap, 'click', event => moverMarcador(event.latLng));

        return map;
    
        function moverMarcador(latLng) {
            
            map.setCenter(latLng.lat(), latLng.lng(), map.getZoom());

            if(marker) {
                marker.setPosition(latLng);
            }
            else {
                marker = map.createMarker(latLng.lat(), latLng.lng(), {
                    title : 'Punto seleccionado',
                    body : '',
                    show : true,
                    global : false
                });
            }
    
            $('#' + id).val(latLng.lat() + ',' + latLng.lng());

            if(conf._callback) {
                conf._callback(marker);
            }
        }
    }

    removeAllMarkers() {
        this.markers.forEach(marker => marker.setMap(null));
        this.markers = [];
        this.markersInitialConf = [];
    }
}