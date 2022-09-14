$.extend($.__proto__, {
    showLoader: true,
    contentBeforeReload : '',
    customGridEventsCache : {},

    /**
     * Valida que el grid exista
     * Usado en todo método del script
     */
    validateGrid: function(gridId) {
        if(typeof gridId !== 'string' || document.getElementById(gridId) === null) {
            throw 'Grid no válido';
        }
    },

    /**
     * Recarga el grid
     * El segundo parametro es la acción a relizar despues de una recarga exitosa
     */
    reloadGrid: function(gridId, _successCallback) {
        $.pjax.defaults.timeout = 0;

        $('#' + gridId).on('pjax:error', function (event) {
            console.warn('Fallo al recargar');
            event.preventDefault();
        });

        this.validateGrid(gridId);

        $(document).on('pjax:success', function(event, data, status, xhr, options) {

            $.customGridEventsCache[gridId].forEach(ev => {
                $.attachEvent(gridId, ev.event, ev.attribute, ev._callback);
            });

            if(typeof _successCallback == "function") {
                _successCallback();
            }

            $(document).off('pjax:success');
        });
        
        if(this.showLoader) {
            this.startLoader(gridId);
        }

        $.pjax.reload('#' + gridId, {history: false, timeout : 0});
    },

    /**
     * Retorna el objeto que representa a una fila con base en su ID
     */
    getRowAttributes: function(gridId, rowId) {
        this.validateGrid(gridId);

        const el = document.querySelector(`#${gridId} tr[data-key="${rowId}"] td[data-json]`);
        return el === null ? null : JSON.parse(el.getAttribute("data-json"));
    },

    /**
     * Recupera los IDs de toas las filas
     */
    getIds: function(gridId) {
        this.validateGrid(gridId);

        let arrayOfIds = [];
        const elements = document.querySelectorAll(`#${gridId} tr[data-key]`);
        elements.forEach(el => arrayOfIds.push(el.getAttribute("data-key")));
        return arrayOfIds;
    },

    /**
     * retorna un objeto que representa los datos de todas las filas
     */
    getRowsData(gridId) {
        this.validateGrid(gridId);
        return this.getIds(gridId).map(rowId => this.getRowAttributes(gridId, rowId));
    },

    /**
     * Elimina una fila 
     */
    deleteRow(gridId, rowId) {
        this.validateGrid(gridId);
        const el = document.querySelector(`#${gridId} tr[data-key="${rowId}"]`);
        
        if(el != null) {
            el.remove();
        }
    },

    /**
     * Inicia el loader manualmente
     */
    startLoader(gridId) {
        this.validateGrid(gridId);

        const gridTableBody = document.querySelector(`#${gridId} table tbody`);
        this.contentBeforeReload = gridTableBody.innerHTML;

        gridTableBody.innerHTML = 
            `<tr>
                <td colspan= '100%' align= 'center'>
                    <i class= 'fa fa-spin fa-circle-notch'></i>
                    <br>
                    <small>Cargando&hellip;</small>
                </td>
            </tr>`;
    },

    /**
     * Termina el loader manualmente
     */
    endLoader(gridId) {
        this.validateGrid(gridId);
        const gridTableBody = document.querySelector(`#${gridId} table tbody`);
        gridTableBody.innerHTML = this.contentBeforeReload;
    },

    /**
     * Agrega un evento. 
     * @param String event - Tipo de evento. Actualmente solo soporta 'click'
     * @param String|number attribute - Atributo o número de columna sobre la que aplica el evento. Predetermina a todas las columnas.
     * @param Function _callback - Función a ejecutar. Los parametros que recibira son el id del registro, valor de la celda y atributo
     */
    attachEvent(gridId, event, attribute = '*', _callback) {
        this.validateGrid(gridId);

        if(!attribute) {
            attribute = '*';
        }

        let selector = "tr td";

        if(typeof attribute == 'number') {
            selector += ":nth-child(" + (attribute + 1) + ")";
        }
        else if(attribute != '*') {
            selector += "[data-attr=" + attribute.replaceAll(".", "\\.") + "]";
        }
        selector += ":not([data-json]):not([data-attribute])";

        const cells = document.getElementById("servicios").querySelectorAll(selector);

        for(i = 0; i < cells.length; i++) {

            switch(event) {
                case 'click':
                    cells[i].onclick = action;
                    break;
                case 'change':
                    cells[i].onchange = action;
                    break;
                default: 
                    throw 'Evento no soportado';
            }
        }

        //Guardar internamente en caso de reload
        const eventData = {
            event : event,
            attribute, attribute,
            _callback, _callback
        };

        if(typeof $.customGridEventsCache[gridId] == 'undefined') {
            $.customGridEventsCache[gridId] = [eventData];
        }
        else {
            $.customGridEventsCache[gridId].push(eventData);
        }

        function action(ev) {
            let key = ev.target.parentElement.getAttribute('data-key');
            let attr = ev.target.getAttribute('data-attr');
            let value = ev.target.value;

            if(typeof attribute == "string" && attribute != '*' && attr === null) return;

            if(ev.target.type == 'checkbox') {
                key = value;
                value = ev.target.checked;
            }

            const callbackResponse = _callback(key, value, attr);

            if(callbackResponse === false) {
                if(ev.target.type == 'checkbox') {
                    ev.target.checked = !ev.target.checked;
                }
            }
        }
    },

    /**
     * Retorna los IDs de las filas seleccionadas
     */
     getSelectedIds(gridId) {
        this.validateGrid(gridId);
        return $(`#${gridId} div`).yiiGridView('getSelectedRows');
    },

    /**
     * retorna un objeto que representa los datos de todas las filas seleccionadas
     */
    getSelectedRowsData(gridId) {
        this.validateGrid(gridId);
        return this.getSelectedIds(gridId).map(rowId => this.getRowAttributes(gridId, rowId));
    }
});