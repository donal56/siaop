//
//
// General
//     utils
//
//
//

/**
 * @function
 * Capitaliza la cadena provista
 * @param {String} cad - Cadena de texto
 * @returns {String} - Cadena capitalizada
 */
 function capitalize(cad) {
    if(cad == null) return null;
    return cad.charAt(0).toUpperCase() + cad.substring(1).toLowerCase();
}

/**
 * @function
 * Asigna el valor de un objeto de manera recursiva tomando su ruta como una cadena concatenada por puntos, p/e 'parent.child'.4
 * Si la ruta provista no exista, creara la llave.
 * @param {object} obj  - El objeto a modificar.
 * @param {String} path  - Cadena que representa la ruta del valor a cambiar. Al igual que la notación por punto de los objetos. Acepta indices, por ejemplo 'obj.obj.0.obj'.
 * @param {mixed} value - El nevo valor.
 */
function setbyPath(obj, path, value) {
    var i;
    path = path.split('.');
    for (i = 0; i < path.length - 1; i++)
        obj = obj[path[i]];

    obj[path[i]] = value;
}

/**
 * @function
 * Recuperar el valor de la llave de un objeto, de acuerdo a su ruta, p/e 'parent.child'
 * @param {object} obj  - El objeto a modificar.
 * @param {String} path  - Cadena que representa la ruta del valor a cambiar. Al igual que la notación por punto de los objetos.
 */
 function getbyPath(obj, path) {
    const parts = path.split('.');

    if(parts.length == 1) {
        return obj === null ? null : obj[parts[0]];
    }
    else {
        const part = parts.shift();
        return getbyPath(obj[part], parts.join("."));
    }
}

/**
 * @function
 * Al igual que la función de postgreSQL, evalua N argumentos y retorna el primero de ellos que no sea nulo.
 * Si todos son nulos retorna NULL
 * @returns {mixed} el primer argumento no nulo
 */
function coalesce() {
    if (arguments.length == 0) return null;

    if (arguments[0] !== null) return arguments[0];

    let args = Array.from(arguments);
    args = args.slice(1, args.length);

    return coalesce.apply(this, args);
}

/**
 * @function    
 * Crea una cadena aleatoria alfanumerica aleatoria de 18 caracters
 * @returns ID aleatorio
 */
 function randomId() {
    let ID = "";
    let characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    for ( var i = 0; i < 18; i++ ) {
        ID += characters.charAt(Math.floor(Math.random() * 36));
    }
    return ID;
}

/**
 * @function
 * Duerme el proceso por un tiempo determinado
 * @param {Integer} ms -  Tiempo en milisegundos
 */
async function sleep(ms = 1000) {
    await new Promise((resolve, reject) => setTimeout(() => resolve(), ms));
}

/**
 * @function
 * Crea una función con debounce(Agrupa llamadas consecutivas y las ejecuta con cierto retraso al inicio o final del grupo detectado)
 * @param {function} func - Función a ejecutar
 * @param {integer} wait - Tiempo de espera en milisegundos
 * @param {boolean} leading - Ejecutar la función al inicio o al final del grupo
 * @returns {function} función con debounce
 */
function debounce(func, wait = 300, leading = false) {
    let timeout;
    return function() {
        let context = this, args = arguments;
        let later = function() {
            timeout = null;
            if (!leading) func.apply(context, args);
        };
        let callNow = leading && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}



//
//
// Date
//  utils
//
//
//

/**
 * @function
 * Retorna la fecha como texto en formato largo
 * @param {Date} date - Objeto fecha
 * @returns {String} - Fecha en texto
 */
function getLongWrittenDate(date) {
    if (!date) return null;

	const fechaActual = date.toLocaleDateString("es-MX", {
		weekday: "long",
		year: "numeric",
		month: "long",
		day: "numeric",
	});

    return capitalize(fechaActual);
}

/**
 * @function
 * Retorna la hora de una fecha en cierto formato
 * @param {Date String} date - Objeto fecha o cadena HH:mm:ss.u
 * @returns {String} Cadena de fecha en formato HH:mm
 */
function getTime(date) {
    if (!date) return "00:00";

    if(date instanceof Date) {
        date = date.toTimeString();
    }

    return date.substr(0, 5);
}

//
//
// Request
//   utils
//
//
//

/**
 * @function
 * @param {String} parameter - Nombre del parámetro
 * @returns {String|false} Valor del parámetro o falso
 */
 function getUrlParameter(parameter) {
    let url = window.location.href;
    
    let queryString = url.split("?")[1];
    let queries = {}; 
    
    if(queryString)
        queryString.split("&").forEach( p => { 
            let parts = p.split("=");
            queries[parts[0]] = decodeURIComponent(parts[1]);
        });

    return queries[parameter] || false;
}

/**
 * @function
 * Serializa el objeto de un formulario o el selector que lo representa.
 * @param {String|Element} form - Selector o elemento tipo formulario.
 * @returns Objeto de profundidad variable que represente al formulario.
 */
function serializeForm(form) {
    const regex = /(.*)\[(.*)\]/;

    if(!form) throw 'Formulario no definido';

    return $(form).serializeArray().reduce((data, pair) => {
        const groups = regex.exec(pair.name);

        if (groups) {
            if (!data[groups[1]]) {
                data[groups[1]] = {};
            }
            data[groups[1]][groups[2]] = pair.value;
        }
        else {
            data[pair.name] = pair.value;
        }

        return data;
    }, {});
}

/**
 * @function
 * Solicitud post
 * @param {String} url - Ruta POST de guardado.
 * @param {Object} data - Datos a guardar. Recomendado usar serializeForm().
 * @param {Object} conf - Configuración.
 *      @subparam {Boolean} silent - Si es true no muestra mensajes.
 *      @subparam {function} callback - Función a ejecutar al terminar la solicitud.
 * @retuns {Promise} promesa de la solicitud.
 */
function postData(url, data = {}, conf = {}) {

    let toast = null;
    let _callback = conf.callback;

    if(!conf.silent) {
        toast = new Toast('Enviando&hellip; <i class="fas fa-circle-notch fa-spin"></i>', 'default', -1);
    }

    return fetch(url, {
        method: 'POST',
        cache: 'no-cache',
        credentials: 'include',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data['_csrf'] || document.querySelector('meta[name=csrf-token]').content
        },
    })
    .then(async (response) => {

        let text = await response.text();

        if (response.ok) {
            try {
                const json = JSON.parse(text);

                if(!conf.silent) {
                    toast.replace('Cambios guardados exitosamente');
                }
                if(typeof _callback == 'function') {
                    _callback(json, true);
                }
            }
            catch(e) {
                if(typeof _callback == 'function') {
                    _callback(null, false);
                }

                throwError(e.message, text);
            }
        } 
        else {
            if(typeof _callback == 'function') {
                _callback(null, false);
            }
            throwError(response.status == 403 ? 'No cuenta con los permisos necesarios para realizar esta acción' : response.statusText, text);
        }
    }, () => {
        if(!conf.silent) {
            toast.replace('Hubo un fallo en la red. Revise su conexión', 'danger');
        }
        if(typeof _callback == 'function') {
            _callback(null, false);
        }
    });

    function throwError(error, detail) {
        if(!conf.silent) {
            toast.replace('Ocurrio un error al procesar su solicitud', 'danger');
        }
        if(typeof _callback == 'function') {
            _callback(null, false);
        }

        console.debug(detail);
        throw new Error(error);
    }
}

/**
 * @function
 * Guardado formulario via ajax Post. Muestra mensaje de guardado exitoso.
 * @param {String} formSelector - Selector del formulario.
 * @param {CallableFunction} _callback - Acción a ejecutar si los cambios son exitosos. 
 *     La función toma como parametro la respuesta de la solicitud y el exito o no.
 * @param {Object} conf - Configuración extra. Valores aceptados:
 *     @subparam {Object} extraData : Datos a agregar a la solicitud. Si un campo coincide con el del formulario, lo sobreescribira.
 */
function postForm(formSelector, _callback = () => { }, conf = {}) {
    const form = document.querySelector(formSelector);
    const data = { ...serializeForm(form), ...conf.extraData };
    postData(form.action, data, conf);
}

/**
 * @function
 * Guardado un formulario común (Sin ajax)
 * Si se especifica que se quiere crear otro del mismo que el formulario un parametro especial se envia
 * Es labor del controlador ubicar este parametro y manejarlo de la manera correcta
 * @param {String} formId - ID del formulario.
 * @param {Boolean} crearOtro - Retornar en exito el mismo formulario o  la vista inicial(index)
 * @return {Boolean} Exito
 */
async function saveSimpleForm(formId, crearOtro = false) {
    const form = document.getElementById(formId);
    $(form).yiiActiveForm('validate');
    await sleep(250);
    
    if(form.querySelectorAll('.has-error').length > 0) {
        new Toast('Algunos datos del formulario son inválidos.<br>Revise la información ingresada.', 'warning', 5000);
        return;
    }
    
    const url = new URL(form.action);
    url.searchParams.set('createAnother', crearOtro ? 1 : 0);
    form.action = url.toString();

    form.submit();
    return true;
}

/**
 * @function
 * @param {String} url - Enlace que consultar. Si el enlace inicia con diagonal sera relativo al host, si no sera relativo a la dirección actual.
 * @param {Object} data  - Objeto a enviar en la consulta a traves de parametros GET
 * @param {Callable} _callback - Acción a ejecutar. Recibe un objeto JSON como parámetro.
 */
function get(url, data, _callback = () => { }) {

    return fetch(url + "?" + $.param(data), {
        method: 'GET'
    }).then(response => {
        if (response.ok) {
            exito = true;
            return response.json();
        } 
        else {
            new Toast('Ocurrio un error al procesar su solicitud', 'danger', 4000);
            throw new Error(response.status == 403 ? 'No cuenta con los permisos necesarios para realizar esta acción' : response.statusText);
        }
    }, () => {
        new Toast('Hubo un fallo en la red. Revise su conexión', 'danger', 4000);
        throw new Error('Error en la solicitud');
    })
    .then(json => {
        if(typeof _callback == 'function') {
            _callback(json);
        }
    });
}

/**
 * Agrega  o reemplaza una cookie con una expiracion a N dias de hoy
 * @param {String} name - Nombre
 * @param {String} value - Valor
 * @param {Integer} days - Días en que expira
 */
function setCookie(name, value, days) {
    let date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

    removeCookie(name);
    document.cookie = name + "=" + value + ";expires=" + date.toUTCString() + "; path=/" + ";domain=." + window.location.host;
}

/**
 * Recupera el valor que almacena una cookie
 * @param {String} name - Nombre
 * @returns valor de la cookie
 */
function getCookie(name) {
    let parts = document.cookie.split(name + "=");
    if(parts.length == 2) 
        return parts.pop().split(";").shift();
}

/**
 * Elimina una cookie
 * @param {String} name - Nombre
 */
function removeCookie(name) {
    document.cookie = document.cookie
        .split(";")
        .filter(cookiePart => !cookiePart.includes(name + "="))
        .join(";");
}

/**
 * Inicia el proceso de comunicación mediante ServerSideEvents
 * @param {String} url - URL del tópico de eventos
 * @param {Callable} _callback - Función a ejecutar cuando se reciba un evento
 * @param {Boolean} debug - Depurar errores
 */
function initServerEvents(url, _callback, debug = false) {
    const eventSource = new EventSource(url);

    eventSource.onmessage = function(event) {
        let data = JSON.parse(event.data);
        
        if(debug) {
            console.log(data);
        }
        if(typeof _callback == 'function') {
            _callback(data);
        }
    };

    eventSource.onerror = function(errorData) {
        if(debug) {
            console.warn(errorData);
        }

        //eventSource.close();
        //new Toast('Ha ocurrido un error durante la comunicación en tiempo real', 'danger');
    };

    return eventSource;
}

/**
 * @function
 * Crea una comunicación con el websocket
 * @param {callable} callback
 * @returns el web socket
 */
 function createWebSocket(callback) {
    const ws = new WebSocket(document.getElementById('WebSocketServer').value);

    ws.onmessage = function(e) {
        let response = JSON.parse(e.data);
        console.warn(e);

        if(typeof callback == "function") {
            callback(response);
        }
    };

    return ws;
}

/**
 * @function
 * Descarga directamente un archivo
 * @param {String} url - URL del archivo
 * @param {String} name - Nombre del archivo
 */
function directDownload(url, fileName = "image.jpg") {

    if(!url) return;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.responseType = "blob";
    xhr.onload = function(){
        const urlCreator = window.URL || window.webkitURL;
        const imageUrl = urlCreator.createObjectURL(this.response);
        const tag = document.createElement('a');
        tag.href = imageUrl;
        tag.download = fileName;
        document.body.appendChild(tag);
        tag.click();
        document.body.removeChild(tag);
    }
    xhr.send();
}


//
//
// UI
//  utils
//
//
//

/**
 * @class
 * Crea un toast message
 */
 class Toast {
    html;
    type;
    timeout;

    timeoutId;
    element;

    defaults = {
        type : 'success',
        timeout : 3000,
    }

    /**
     * @constructor
     * @param {String} html - HTML válido usado como mensaje.
     * @param {!String} type - Tipo de mensaje. Las opciones son las mismas que en Bootstrap.
     * @param {!Number} timeout - Tiempo en ms que durara el mensaje, -1 si el mensaje es permanente;} html 
     */
    constructor(html, type, timeout) {
        this.init(html, type, timeout);
        this.render();
    }

    init(html, type, timeout) {
        this.html = html;
        this.type = type || this.defaults.type;
        this.timeout = Number(timeout) || this.defaults.timeout;
    }

    render() {
        const toast = this;

        this.element = this.element || document.createElement('DIV');
        this.element.innerHTML = this.html;
        this.element.className = 'toast show ' + this.type;
        this.element.onclick = function() {
            toast.clear();
        }

        document.querySelector('body').appendChild(this.element);
        
        if(toast.timeout != -1) {
            toast.timeoutId = setTimeout(function() {
                toast.clear();
            }, toast.timeout);
        }
    }

    replace(html, type, timeout) {
        this.clear();
        this.init(html, type, timeout);
        this.render();
    }
    
    clear() {
        clearTimeout(this.timeoutId);
        this.timeoutId = null;
        this.element.className = this.element.className.replace('show', '');
    }
}

/**
 * @function
 * Mueve la pantalla hacia el elemento indicado.
 * @param {String} elementId - ID del elemento
 */
function scrollToElement(elementId, baseElement = null, direction = 'top') {
    let base = window;
    let conf = {
        behavior: 'smooth'
    }

    conf[direction] = document.getElementById(elementId).getBoundingClientRect()[direction];

    if(baseElement) {
        base = document.getElementById(baseElement);
    }

    base.scrollTo(conf);
}

/**
 * @function
 * Rellena un contenedor plantilla
 * @param {String} templateId - ID del elemento plantilla
 * @param {Object} data - Objeto con los datos a rellenar
 * @param {Object} mapper - Objecto que mapea llaves con funciones para rellenar el contenido. La función recibe el valor de llave y el modelo de datos como parámetros. El objeto tambien puede tener una cadena de configuración en lugar de una función, por ejemplo: 'format:date' o 'format:time' formatea el valor como fecha o hora. Si el valor es algun otro valor, se usara como el valor fijo de la plantilla
 */
function fillTemplate(templateId, data, mapper = {}) {
    const template = document.getElementById(templateId);

    template.querySelectorAll('[data-field]').forEach(el => {
        const field = el.getAttribute("data-field");
        let value = getbyPath(data, field);

        if(typeof mapper[field] == 'function') {
            value = mapper[field](value, data);
        }
        else if(mapper[field]) {
            let conf = mapper[field];

            if(conf.startsWith("format")) {
                const format = conf.split(":")[1];

                switch(format) {
                    case "date":
                        break;
                    case "time":
                        value = value.substring(0, 5);
                }
            }
            else {
                value = conf;
            }
        }
        
        el.textContent = value;
    });
}
