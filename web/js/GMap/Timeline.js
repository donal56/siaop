class Timeline {

    events;
    elementId;
    title;
    subtitle;
    
    /**
     * @function
     * Crear una línea de tiempo con base es un objeto ruta, parte de un objeto DiretionsResultObject
     * @param {String} elementId - ID del componente
     * @param {String} title - Título del componente
     * @param {!String} subtitle - Subtítulo del componente
     */
    constructor(elementId, title, subtitle = '') {
        if(!elementId) throw new Exception('ID del elemento requerido');

        this.elementId = elementId;
        this.title = title;
        this.subtitle = subtitle;
        this.events = {};
    }

    /**
     * @function
     * Genera el componente de línea de tiempo
     */
    generar() {
        const linebreak = document.createElement('BR');

        const planRuta = document.getElementById(this.elementId);
        planRuta.innerHTML = '';

        const titulo = document.createElement('H4');
        titulo.textContent = this.title;
        titulo.style = 'font-size: 2em; padding-top: 20px; text-align: center';
        planRuta.appendChild(titulo);
        
        const subtitulo = document.createElement('H5');
        subtitulo.innerHTML = this.subtitle;
        subtitulo.style = 'font-size: 1.2em; text-align: center';
        planRuta.appendChild(subtitulo);

        planRuta.appendChild(linebreak);
        
        const lineaTiempo = document.createElement('UL');
        lineaTiempo.classList.add('timeline');
        
        const llaves = Object.keys(this.events);

        llaves.forEach(eventId => {
            const eventData = this.events[eventId];
            const evento = document.createElement('LI');
            evento.classList.add('event');
            evento.setAttribute('data-date', eventData.fecha);
    
            const tituloEvento = document.createElement('DIV');
            tituloEvento.classList.add('event-title');
            tituloEvento.innerHTML += eventData.titulo;
            evento.appendChild(tituloEvento);
            
            const textoEvento = document.createElement('P');
            textoEvento.classList.add('event-body');
            textoEvento.innerHTML += eventData.cuerpo;
            evento.appendChild(textoEvento);
    
            lineaTiempo.appendChild(evento);
        }, this);

        planRuta.appendChild(lineaTiempo);
    }

    /**
     * @function
     * Agrega un evento a linea de tiempo.
     * Las fechas no se reordenan, así que el evento se agregara al final de la línea.
     * @param {Object} eventData - Objeto con las siguientes propiedades: titulo, cuerpo, fecha
     */
    agregarEvento(eventData) {
        if(!eventData) return;

        const llaves = Object.keys(this.events);
        let eventId = 0;

        if(llaves.length > 0) {
            eventId = Number(llaves[llaves.length - 1]) + 1;
        }

        this.events[eventId] = eventData;

        return eventId;
    }
}