SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO origenes VALUES (1, 'WEB'), (2, 'MOVIL');

INSERT INTO empresas VALUES (1, 'Global Water', '', 1, CURRENT_TIMESTAMP, 1);

INSERT INTO usuarios VALUES (1, 'superadmin', 'uK_MurTu_J8lfwmoOYcuSdUelnbt-Lhl', '$2y$13$pteNp/lmyNMtMN.B9jZy6el6plclbtcBrLsVcWxFS/CrUi4gkXfTe', NULL, 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, 1, NULL, NULL, 'donal_56@hotmail.com', 1, 'Carlos Donaldo', 'Ramón', 'Gómez', NULL, NULL, NULL, NULL, NULL, 1);

/** RBAC **/
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('Actividades', 'Actividades', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('ClasesVehiculares', 'Clases vehiculares', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('Clientes', 'Clientes', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('Marcas', 'Marcas', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('Pozos', 'Pozos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('TiposArchivos', 'Tipos de archivos', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('TiposCombustibles', 'Tipos de combustibles', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('TiposUnidadesVehiculares', 'Tipos de unidades vehiculares', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('UnidadesMedida', 'Unidades de medida', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
INSERT INTO grupos_permisos(code, name, created_at, updated_at) VALUES ('UnidadesVehiculares', 'Unidades vehiculares', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Refrescar rutas
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarActividad', 2, 'Agregar actividad', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Actividades');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarClaseVehicular', 2, 'Agregar clase vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'ClasesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarCliente', 2, 'Agregar cliente', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Clientes');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarMarca', 2, 'Agregar marca', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Marcas');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarPozo', 2, 'Agregar pozo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Pozos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarProceso', 2, 'Agregar proceso', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'procesos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarTipoArchivo', 2, 'Agregar tipo de archivo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposArchivos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarTipoCombustible', 2, 'Agregar tipo de combustible', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposCombustibles');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarTipoUnidadVehicular', 2, 'Agregar tipo de unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposUnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarUnidadMedida', 2, 'Agregar unidad de medida', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesMedida');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarUnidadVehicular', 2, 'Agregar unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('CLI', 1, 'Cliente', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), NULL);
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultaCliente', 2, 'Consultar cliente', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Clientes');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarActividad', 2, 'Consultar actividad', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Actividades');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarClaseVehicular', 2, 'Consultar clase vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'ClasesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarMarca', 2, 'Consultar marca', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Marcas');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarPozo', 2, 'Consultar pozo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Pozos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarProceso', 2, 'Consultar proceso', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'procesos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarTipoArchivo', 2, 'Consultar tipo de archivo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposArchivos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarTipoCombustible', 2, 'Consultar tipo de combustible', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposCombustibles');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarTipoUnidadVehicular', 2, 'Consultar tipo de unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposUnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarUnidadMedida', 2, 'Consultar unidad de medida', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesMedida');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarUnidadVehicular', 2, 'Consultar unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarActividad', 2, 'Eliminar actividad', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Actividades');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarClaseVehicular', 2, 'Eliminar clase vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'ClasesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarCliente', 2, 'Eliminar cliente', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Clientes');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarMarca', 2, 'Eliminar marca', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Marcas');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarPozo', 2, 'Eliminar pozo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Pozos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarProceso', 2, 'Eliminar proceso', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'procesos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarTipoArchivo', 2, 'Eliminar tipo de archivo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposArchivos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarTipoCombustible', 2, 'Eliminar tipo de combustible', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposCombustibles');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarTipoUnidadVehicular', 2, 'Eliminar tipo de unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposUnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarUnidadMedida', 2, 'Eliminar unidad de medida', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesMedida');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarUnidadVehicular', 2, 'Eliminar unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarActividad', 2, 'Modificar actividad', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Actividades');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarClaseVehicular', 2, 'Modificar clase vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'ClasesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarCliente', 2, 'Modificar cliente', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Clientes');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarMarca', 2, 'Modificar marca', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Marcas');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarPozo', 2, 'Modificar pozo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'Pozos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarProceso', 2, 'Modificar proceso', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'procesos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarTipoArchivo', 2, 'Modificar tipo de archivo', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposArchivos');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarTipoCombustible', 2, 'Modificar tipo de combustible', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposCombustibles');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarTipoUnidadVehicular', 2, 'Modificar tipo de unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'TiposUnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarUnidadMedida', 2, 'Modificar unidad de medida', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesMedida');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarUnidadVehicular', 2, 'Modificar unidad vehicular', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'UnidadesVehiculares');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('OP', 1, 'Operador', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), NULL);
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('QHS', 1, 'QHS', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), NULL);
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('agregarOrdenServicio', 2, 'Agregar orden de servicio', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'OrdenesServicio');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('consultarOrdenServicio', 2, 'Consultar orden de servicio', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'OrdenesServicio');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('eliminarOrdenServicio', 2, 'Eliminar orden de servicio', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'OrdenesServicio');
INSERT INTO permisos(name, type, description, rule_name, data, created_at, updated_at, group_code) VALUES ('modificarOrdenServicio', 2, 'Modificar orden de servicio', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'OrdenesServicio');

INSERT INTO permisos_hijos(parent, child) VALUES ('agregarActividad', '/actividades/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarActividad', '/actividades/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarActividad', '/actividades/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarActividad', '/actividades/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarActividad', '/actividades/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarClaseVehicular', '/clases-vehiculares/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarClaseVehicular', '/clases-vehiculares/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarClaseVehicular', '/clases-vehiculares/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarClaseVehicular', '/clases-vehiculares/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarClaseVehicular', '/clases-vehiculares/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarCliente', '/clientes/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarCliente', '/clientes/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultaCliente', '/clientes/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarCliente', '/clientes/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultaCliente', '/clientes/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarMarca', '/marcas/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarMarca', '/marcas/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarMarca', '/marcas/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarMarca', '/marcas/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarMarca', '/marcas/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarPozo', '/pozos/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarPozo', '/pozos/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarPozo', '/pozos/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarPozo', '/pozos/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarPozo', '/pozos/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarTipoArchivo', '/tipos-archivos/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarTipoArchivo', '/tipos-archivos/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarTipoArchivo', '/tipos-archivos/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarTipoArchivo', '/tipos-archivos/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarTipoArchivo', '/tipos-archivos/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarTipoCombustible', '/tipos-combustibles/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarTipoCombustible', '/tipos-combustibles/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarTipoCombustible', '/tipos-combustibles/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarTipoCombustible', '/tipos-combustibles/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarTipoCombustible', '/tipos-combustibles/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarTipoUnidadVehicular', '/tipos-unidades-vehiculares/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarTipoUnidadVehicular', '/tipos-unidades-vehiculares/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarTipoUnidadVehicular', '/tipos-unidades-vehiculares/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarTipoUnidadVehicular', '/tipos-unidades-vehiculares/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarTipoUnidadVehicular', '/tipos-unidades-vehiculares/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarUnidadMedida', '/unidades-medida/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarUnidadMedida', '/unidades-medida/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarUnidadMedida', '/unidades-medida/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarUnidadMedida', '/unidades-medida/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarUnidadMedida', '/unidades-medida/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarUnidadVehicular', '/unidades-vehiculares/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarUnidadVehicular', '/unidades-vehiculares/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarUnidadVehicular', '/unidades-vehiculares/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarUnidadVehicular', '/unidades-vehiculares/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarUnidadVehicular', '/unidades-vehiculares/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarProceso', '/procesos/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarProceso', '/procesos/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarProceso', '/procesos/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarProceso', '/procesos/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarProceso', '/procesos/view');
INSERT INTO permisos_hijos(parent, child) VALUES ('agregarOrdenServicio', '/ordenes-servicio/create');
INSERT INTO permisos_hijos(parent, child) VALUES ('eliminarOrdenServicio', '/ordenes-servicio/delete');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarOrdenServicio', '/ordenes-servicio/index');
INSERT INTO permisos_hijos(parent, child) VALUES ('modificarOrdenServicio', '/ordenes-servicio/update');
INSERT INTO permisos_hijos(parent, child) VALUES ('consultarOrdenServicio', '/ordenes-servicio/view');

SET FOREIGN_KEY_CHECKS = 1;
