SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Estructura de la tabla <actividades>
-- ----------------------------
DROP TABLE IF EXISTS actividades;
CREATE TABLE actividades (
    id_actividad INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_unidad_medida INT(11) NOT NULL,
    id_empresa INT(11) NOT NULL,
    actividad VARCHAR(512) NOT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_actividad_id_unidad_medida(id_unidad_medida) USING BTREE,
    INDEX ix_actividad_id_empresa(id_empresa) USING BTREE,
    INDEX ix_actividad_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_actividad_id_unidad_medida FOREIGN KEY(id_unidad_medida) REFERENCES unidades_medida(id_unidad_medida) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_actividad_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_actividad_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <clases_vehiculares>
-- ----------------------------
DROP TABLE IF EXISTS clases_vehiculares;
CREATE TABLE clases_vehiculares (
    id_clase_vehicular INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    clase_vehicular VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255),
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_clase_vehicular_id_empresa(id_empresa) USING BTREE,
    INDEX ix_clase_vehicular_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_clase_vehicular_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_clase_vehicular_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <clientes>
-- ----------------------------
DROP TABLE IF EXISTS clientes;
CREATE TABLE clientes (
    id_cliente INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    razon_social VARCHAR(255) NOT NULL,
    rfc VARCHAR(13),
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_cliente_id_empresa(id_empresa) USING BTREE,
    INDEX ix_cliente_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_cliente_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_cliente_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <cuadrillas>
-- ----------------------------
DROP TABLE IF EXISTS cuadrillas;
CREATE TABLE cuadrillas (
    id_cuadrilla INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_orden_servicio INT(11) NOT NULL,
    usuario INT(11) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_cuadrilla_id_orden_servicio(id_orden_servicio) USING BTREE,
    INDEX ix_cuadrilla_usuario(usuario) USING BTREE,
    INDEX ix_cuadrilla_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_cuadrilla_id_orden_servicio FOREIGN KEY(id_orden_servicio) REFERENCES ordenes_servicio(id_orden_servicio) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_cuadrilla_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_cuadrilla_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <formatos>
-- ----------------------------
DROP TABLE IF EXISTS formatos;
CREATE TABLE formatos (
    id_formato INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    id_tipo_formato INT(11),
    titulo VARCHAR(255) NOT NULL,
    fecha_creacion TIMESTAMP(6) NOT NULL,
    codigo VARCHAR(50),
    revision VARCHAR(50),
    subtitulo VARCHAR(255),
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_formato_id_empresa(id_empresa) USING BTREE,
    INDEX ix_formato_id_tipo_formato(id_tipo_formato) USING BTREE,
    INDEX ix_formato_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_formato_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_formato_id_tipo_formato FOREIGN KEY(id_tipo_formato) REFERENCES tipos_formatos(id_tipo_formato) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_formato_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <formatos_rellenados>
-- ----------------------------
DROP TABLE IF EXISTS formatos_rellenados;
CREATE TABLE formatos_rellenados (
    id_formato_rellenado INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_formato INT(11) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    parametro_1 VARCHAR(255),
    parametro_2 VARCHAR(255),
    parametro_3 VARCHAR(255),
    elaborado_por VARCHAR(255),
    revisado_por VARCHAR(255),
    autorizador_por VARCHAR(255),
    observaciones_generales VARCHAR(512),
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_formato_rellenado_id_formato(id_formato) USING BTREE,
    INDEX ix_formato_rellenado_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_formato_rellenado_id_formato FOREIGN KEY(id_formato) REFERENCES formatos(id_formato) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_formato_rellenado_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <formatos_secciones>
-- ----------------------------
DROP TABLE IF EXISTS formatos_secciones;
CREATE TABLE formatos_secciones (
    id_formato_seccion INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_formato INT(11) NOT NULL,
    orden INT(11) NOT NULL,
    formato_seccion VARCHAR(255) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_formato_seccion_id_formato(id_formato) USING BTREE,
    INDEX ix_formato_seccion_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_formato_seccion_id_formato FOREIGN KEY(id_formato) REFERENCES formatos(id_formato) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_formato_seccion_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <marcas>
-- ----------------------------
DROP TABLE IF EXISTS marcas;
CREATE TABLE marcas (
    id_marca INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    marca VARCHAR(255) NOT NULL,
    descripcion VARCHAR(512) NULL DEFAULT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_marca_id_empresa(id_empresa) USING BTREE,
    INDEX ix_marca_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_marca_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_marca_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <ordenes_servicio>
-- ----------------------------
DROP TABLE IF EXISTS ordenes_servicio;
CREATE TABLE ordenes_servicio (
    id_orden_servicio INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    id_tipo_orden_servicio INT(11) NOT NULL,
    id_cliente INT(11) NOT NULL,
    id_estatus INT(11) NOT NULL,
    id_unidad_vehicular INT(11),
    id_pozo INT(11),
    usuario_jefe_cuadrilla INT(11),
    usuario_cliente_solicitante INT(11),
    hora_salida TIME,
    distancia_kms INT(11),
    combustible_aproximado_lts NUMERIC(10, 2),
    ruta_descripcion VARCHAR(255) NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada TIME NOT NULL,
    origen_x VARCHAR(64) NOT NULL,
    origen_y VARCHAR(64) NOT NULL,
    destino_x VARCHAR(64) NOT NULL,
    destino_y VARCHAR(64) NOT NULL,
    fecha_hora_llegada_real TIMESTAMP(6),
    fecha_hora_salida_real TIMESTAMP(6),
    fecha_hora_inicio_trabajo TIMESTAMP(6),
    fecha_hora_final_trabajo TIMESTAMP(6),
    fecha_captura TIMESTAMP(6) NOT NULL,
    usuario_captura INT(11) NOT NULL,
    origen_version INT(11) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_orden_servicio_id_empresa(id_empresa) USING BTREE,
    INDEX ix_orden_servicio_id_tipo_orden_servicio(id_tipo_orden_servicio) USING BTREE,
    INDEX ix_orden_servicio_id_cliente(id_cliente) USING BTREE,
    INDEX ix_orden_servicio_id_unidad_vehicular(id_unidad_vehicular) USING BTREE,
    INDEX ix_orden_servicio_id_estatus(id_estatus) USING BTREE,
    INDEX ix_orden_servicio_id_pozo(id_pozo) USING BTREE,
    INDEX ix_orden_servicio_usuario_captura(usuario_captura) USING BTREE,
    INDEX ix_orden_servicio_usuario_jefe_cuadrilla(usuario_jefe_cuadrilla) USING BTREE,
    INDEX ix_orden_servicio_usuario_cliente_solicitante(usuario_cliente_solicitante) USING BTREE,
    INDEX ix_orden_servicio_origen_version(origen_version) USING BTREE,
    INDEX ix_orden_servicio_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_orden_servicio_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_id_tipo_orden_servicio FOREIGN KEY(id_tipo_orden_servicio) REFERENCES tipos_ordenes_servicio(id_tipo_orden_servicio) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_id_cliente FOREIGN KEY(id_cliente) REFERENCES clientes(id_cliente) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_id_unidad_vehicular FOREIGN KEY(id_unidad_vehicular) REFERENCES unidades_vehiculares(id_unidad_vehicular) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_id_estatus FOREIGN KEY(id_estatus) REFERENCES estatus(id_estatus) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_id_pozo FOREIGN KEY(id_pozo) REFERENCES pozos(id_pozo) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_usuario_captura FOREIGN KEY(usuario_captura) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_usuario_jefe_cuadrilla FOREIGN KEY(usuario_jefe_cuadrilla) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_usuario_cliente_solicitante FOREIGN KEY(usuario_cliente_solicitante) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_origen_version FOREIGN KEY(origen_version) REFERENCES origenes(id_origen) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <ordenes_servicio_actividades>
-- ----------------------------
DROP TABLE IF EXISTS ordenes_servicio_actividades;
CREATE TABLE ordenes_servicio_actividades (
    id_orden_servicio_actividad INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_orden_servicio INT(11) NOT NULL,
    id_actividad INT(11) NOT NULL,
    cantidad NUMERIC(10, 2) NOT NULL DEFAULT 0,
    realizado TINYINT(1) NOT NULL DEFAULT 0,
    observacion VARCHAR(512),
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_orden_servicio_actividad_id_orden_servicio(id_orden_servicio) USING BTREE,
    INDEX ix_orden_servicio_actividad_id_actividad(id_actividad) USING BTREE,
    UNIQUE INDEX ix_orden_servicio_actividad(id_orden_servicio, id_actividad) USING BTREE,
    CONSTRAINT fk_orden_servicio_actividad_id_orden_servicio FOREIGN KEY(id_orden_servicio) REFERENCES ordenes_servicio(id_orden_servicio) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_actividad_id_actividad FOREIGN KEY(id_actividad) REFERENCES actividades(id_actividad) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_actividad_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <ordenes_servicio_archivos>
-- ----------------------------
DROP TABLE IF EXISTS ordenes_servicio_archivos;
CREATE TABLE ordenes_servicio_archivos (
    id_orden_servicio_archivo INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_orden_servicio INT(11) NOT NULL,
    id_archivo INT(11) NOT NULL,
    id_tipo_archivo INT(11) NOT NULL,
    validado TINYINT(1) NOT NULL DEFAULT 0,
    usuario_validacion INT(11),
    fecha_validacion TIMESTAMP(6),
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_orden_servicio_archivo_id_orden_servicio(id_orden_servicio) USING BTREE,
    INDEX ix_orden_servicio_archivo_id_archivo(id_archivo) USING BTREE,
    INDEX ix_orden_servicio_archivo_id_tipo_archivo(id_tipo_archivo) USING BTREE,
    INDEX ix_orden_servicio_archivo_usuario_validacion(usuario_validacion) USING BTREE,
    INDEX ix_orden_servicio_archivo_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_orden_servicio_archivo_id_orden_servicio FOREIGN KEY(id_orden_servicio) REFERENCES ordenes_servicio(id_orden_servicio) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_archivo_id_archivo FOREIGN KEY(id_archivo) REFERENCES archivos(id_archivo) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_archivo_id_tipo_archivo FOREIGN KEY(id_tipo_archivo) REFERENCES tipos_archivos(id_tipo_archivo) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_archivo_usuario_validacion FOREIGN KEY(usuario_validacion) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_orden_servicio_archivo_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <pozos>
-- ----------------------------
DROP TABLE IF EXISTS pozos;
CREATE TABLE pozos (
    id_pozo INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    pozo VARCHAR(255) NOT NULL,
    ubicacion_descripcion VARCHAR(255),
    ubicacion_x VARCHAR(64),
    ubicacion_y VARCHAR(64),
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_pozo_id_empresa(id_empresa) USING BTREE,
    INDEX ix_pozo_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_pozo_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_pozo_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <procesos>
-- ----------------------------
DROP TABLE IF EXISTS procesos;
CREATE TABLE procesos (
    id_proceso INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    proceso VARCHAR(255) NOT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_proceso_id_empresa(id_empresa) USING BTREE,
    INDEX ix_proceso_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_proceso_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_proceso_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <rubricas>
-- ----------------------------
DROP TABLE IF EXISTS rubricas;
CREATE TABLE rubricas (
    id_rubrica INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_formato_seccion INT(11) NOT NULL,
    orden INT(11) NOT NULL,
    rubrica VARCHAR(255) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_rubrica_id_formato_seccion(id_formato_seccion) USING BTREE,
    INDEX ix_rubrica_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_rubrica_id_formato_seccion FOREIGN KEY(id_formato_seccion) REFERENCES formatos_secciones(id_formato_seccion) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_rubrica_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <rubricas_criterios>
-- ----------------------------
DROP TABLE IF EXISTS rubricas_criterios;
CREATE TABLE rubricas_criterios (
    id_rubrica_criterio INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_rubrica INT(11) NOT NULL,
    orden INT(11) NOT NULL,
    rubrica_criterio VARCHAR(512) NOT NULL,
    numero VARCHAR(64),
    nota VARCHAR(512) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_rubrica_criterio_id_rubrica(id_rubrica) USING BTREE,
    INDEX ix_rubrica_criterio_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_rubrica_criterio_id_rubrica FOREIGN KEY(id_rubrica) REFERENCES rubricas(id_rubrica) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_rubrica_criterio_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <rubricas_grupos_indicadores>
-- ----------------------------
DROP TABLE IF EXISTS rubricas_grupos_indicadores;
CREATE TABLE rubricas_grupos_indicadores (
    id_rubrica_grupo_indicador INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_rubrica INT(11) NOT NULL,
    rubrica_grupo_indicador VARCHAR(512) NOT NULL,
    orden INT(11) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_rubrica_grupo_indicador_id_rubrica(id_rubrica) USING BTREE,
    INDEX ix_rubrica_grupo_indicador_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_rubrica_grupo_indicador_id_rubrica FOREIGN KEY(id_rubrica) REFERENCES rubricas(id_rubrica) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_rubrica_grupo_indicador_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <rubricas_indicadores>
-- ----------------------------
DROP TABLE IF EXISTS rubricas_indicadores;
CREATE TABLE rubricas_indicadores (
    id_rubrica_indicador INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_rubrica_grupo_indicador INT(11) NOT NULL,
    rubrica_indicador VARCHAR(255) NOT NULL,
    orden INT(11) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_rubrica_indicador_id_rubrica_grupo_indicador(id_rubrica_grupo_indicador) USING BTREE,
    INDEX ix_rubrica_indicador_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_rubrica_indicador_id_rubrica_grupo_indicador FOREIGN KEY(id_rubrica_grupo_indicador) REFERENCES rubricas_grupos_indicadores(id_rubrica_grupo_indicador) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_rubrica_indicador_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <rubricas_respuestas>
-- ----------------------------
DROP TABLE IF EXISTS rubricas_respuestas;
CREATE TABLE rubricas_respuestas (
    id_rubrica_respuesta INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_rubrica_criterio INT(11) NOT NULL,
    id_rubrica_indicador INT(11) NOT NULL,
    id_formato_rellenado INT(11) NOT NULL,
    observacion VARCHAR(512),
    parametro_1 VARCHAR(255),
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_rubrica_respuesta_id_rubrica_criterio(id_rubrica_criterio) USING BTREE,
    INDEX ix_rubrica_respuesta_id_rubrica_indicador(id_rubrica_indicador) USING BTREE,
    INDEX ix_rubrica_respuesta_id_formato_rellenado(id_formato_rellenado) USING BTREE,
    INDEX ix_rubrica_respuesta_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_rubrica_respuesta_id_rubrica_criterio FOREIGN KEY(id_rubrica_criterio) REFERENCES rubricas_criterios(id_rubrica_criterio) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_rubrica_respuesta_id_rubrica_indicador FOREIGN KEY(id_rubrica_indicador) REFERENCES rubricas_indicadores(id_rubrica_indicador) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_rubrica_respuesta_id_formato_rellenado FOREIGN KEY(id_formato_rellenado) REFERENCES formatos_rellenados(id_formato_rellenado) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_rubrica_respuesta_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <tipos_archivos>
-- ----------------------------
DROP TABLE IF EXISTS tipos_archivos;
CREATE TABLE tipos_archivos (
    id_tipo_archivo INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_proceso INT(11) NOT NULL,
    id_empresa INT(11) NOT NULL,
    tipo_archivo VARCHAR(255) NOT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_tipo_archivo_id_proceso(id_proceso) USING BTREE,
    INDEX ix_tipo_archivo_id_empresa(id_empresa) USING BTREE,
    INDEX ix_tipo_archivo_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_tipo_archivo_id_proceso FOREIGN KEY(id_proceso) REFERENCES procesos(id_proceso) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_tipo_archivo_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_tipo_archivo_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <tipos_combustibles>
-- ----------------------------
DROP TABLE IF EXISTS tipos_combustibles;
CREATE TABLE tipos_combustibles (
    id_tipo_combustible INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    tipo_combustible VARCHAR(255) NOT NULL,
    descripcion VARCHAR(512) NULL DEFAULT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_tipo_combustible_id_empresa(id_empresa) USING BTREE,
    INDEX ix_tipo_combustible_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_tipo_combustible_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_tipo_combustible_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <tipos_formatos>
-- ----------------------------
DROP TABLE IF EXISTS tipos_formatos;
CREATE TABLE tipos_formatos (
    id_tipo_formato INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    tipo_formato VARCHAR(255) NOT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_tipo_formato_id_empresa(id_empresa) USING BTREE,
    INDEX ix_tipo_formato_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_tipo_formato_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_tipo_formato_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <tipos_ordenes_servicio>
-- ----------------------------
DROP TABLE IF EXISTS tipos_ordenes_servicio;
CREATE TABLE tipos_ordenes_servicio (
    id_tipo_orden_servicio INT(11) PRIMARY KEY AUTO_INCREMENT,
    tipo_orden_servicio VARCHAR(255) NOT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_tipo_orden_servicio_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_tipo_orden_servicio_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <tipos_unidades_vehiculares>
-- ----------------------------
DROP TABLE IF EXISTS tipos_unidades_vehiculares;
CREATE TABLE tipos_unidades_vehiculares (
    id_tipo_unidad_vehicular INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    tipo_unidad_vehicular VARCHAR(255) NOT NULL,
    descripcion VARCHAR(512) NULL DEFAULT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_tipo_unidad_vehicular_id_empresa(id_empresa) USING BTREE,
    INDEX ix_tipo_unidad_vehicular_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_tipo_unidad_vehicular_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_tipo_unidad_vehicular_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <unidades_medida>
-- ----------------------------
DROP TABLE IF EXISTS unidades_medida;
CREATE TABLE unidades_medida (
    id_unidad_medida INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    unidad_medida VARCHAR(255) NOT NULL,
    nombre_corto VARCHAR(10),
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_unidad_medida_id_empresa(id_empresa) USING BTREE,
    INDEX ix_unidad_medida_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_unidad_medida_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_unidad_medida_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <unidades_vehiculares>
-- ----------------------------
DROP TABLE IF EXISTS unidades_vehiculares;
CREATE TABLE unidades_vehiculares (
    id_unidad_vehicular INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT(11) NOT NULL,
    id_marca INT(11) NOT NULL,
    id_tipo_unidad_vehicular INT(11) NOT NULL,
    id_clase_vehicular INT(11),
    id_tipo_combustible INT(11),
    modelo VARCHAR(100) NOT NULL,
    placa VARCHAR(10) NOT NULL,
    motor VARCHAR(100),
    tarjeta_circulacion VARCHAR(32),
    numero_identificacion_vehicular VARCHAR(32),
    poliza VARCHAR(32),
    vigencia_poliza DATE,
    permiso_ruta_sct VARCHAR(32) COMMENT 'Secretaria de caminos y transporte',
    numero_economica VARCHAR(32),
    permiso_trp VARCHAR(32) COMMENT 'Permiso de para transporte de residuos peligrosos',
    vigencia_trp DATE,
    permiso_trme VARCHAR(32)  COMMENT 'Permiso para transporte de residuos y manejo especial',
    vigencia_trme DATE,
    rendimiento_combustible DOUBLE(10, 2) COMMENT 'Kilometros por litro de combustible',
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_unidad_vehicular_id_empresa(id_empresa) USING BTREE,
    INDEX ix_unidad_vehicular_id_marca(id_marca) USING BTREE,
    INDEX ix_unidad_vehicular_id_tipo_unidad_vehicular(id_tipo_unidad_vehicular) USING BTREE,
    INDEX ix_unidad_vehicular_id_clase_vehicular(id_clase_vehicular) USING BTREE,
    INDEX ix_unidad_vehicular_id_tipo_combustible(id_tipo_combustible) USING BTREE,
    INDEX ix_unidad_vehicular_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_unidad_vehicular_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_unidad_vehicular_marca FOREIGN KEY(id_marca) REFERENCES marcas(id_marca) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_unidad_vehicular_tipo_unidad_vehicular FOREIGN KEY(id_tipo_unidad_vehicular) REFERENCES tipos_unidades_vehiculares(id_tipo_unidad_vehicular) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_unidad_vehicular_id_clase_vehicular FOREIGN KEY(id_clase_vehicular) REFERENCES clases_vehiculares(id_clase_vehicular) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_unidad_vehicular_id_tipo_combustible FOREIGN KEY(id_tipo_combustible) REFERENCES tipos_combustibles(id_tipo_combustible) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_unidad_vehicular_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
