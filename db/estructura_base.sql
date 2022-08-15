SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Estructura de la tabla <archivos>
-- ----------------------------
DROP TABLE IF EXISTS archivos;
CREATE TABLE archivos (
    id_archivo INT(11) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    extension VARCHAR(4) NOT NULL,
    mime VARCHAR(255) NOT NULL,
    tamanio INT(32) NOT NULL,
    md5 VARCHAR(32) NOT NULL,
    ip VARCHAR(64) NOT NULL,
    ubicacion_x VARCHAR(64) NOT NULL,
    ubicacion_y VARCHAR(64) NOT NULL,
    observacion VARCHAR(512),
    fecha_carga TIMESTAMP(6) NOT NULL,
    usuario_carga INT(11) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_archivo_usuario_carga(usuario_carga) USING BTREE,
    UNIQUE INDEX ix_archivo_md5(md5) USING BTREE,
    CONSTRAINT fk_archivo_usuario_carga FOREIGN KEY(usuario_carga) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <asignaciones_permisos>
-- ----------------------------
DROP TABLE IF EXISTS asignaciones_permisos;
CREATE TABLE asignaciones_permisos (
    item_name VARCHAR(64) NOT NULL,
    user_id INT(11) NOT NULL,
    created_at INT(11) NULL DEFAULT NULL,
    PRIMARY KEY (item_name, user_id) USING BTREE,
    INDEX ix_asignacion_permiso_user_id(user_id) USING BTREE,
    CONSTRAINT fk_asignacion_permiso_item_name FOREIGN KEY(item_name) REFERENCES permisos(name) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_asignacion_permiso_user_id FOREIGN KEY(user_id) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <bitacoras>
-- ----------------------------
DROP TABLE IF EXISTS bitacoras;
CREATE TABLE bitacoras (
    id_bitacora INT(11) PRIMARY KEY AUTO_INCREMENT,
    tabla VARCHAR(255) NOT NULL,
    llave_primaria VARCHAR(255),
    registro_viejo JSON,
    registro_nuevo JSON,
    accion ENUM('INSERT', 'UPDATE', 'DELETE', 'OTHER') NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    dml_created_by VARCHAR(255) NOT NULL,
    usuario_version INT(11),
    INDEX ix_bitacora_tabla(tabla) USING BTREE,
    INDEX ix_bitacora_llave_primaria(llave_primaria) USING BTREE
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <dispositivos>
-- ----------------------------
DROP TABLE IF EXISTS dispositivos;
CREATE TABLE dispositivos (
    id_dispositivo INT(11) PRIMARY KEY AUTO_INCREMENT,
    usuario INT(11) NOT NULL,
    token VARCHAR(100) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    INDEX ix_dispositivo_usuario(usuario) USING BTREE,
    UNIQUE INDEX ix_dispositivo_token(token) USING BTREE,
    CONSTRAINT fk_dispositivo_usuario FOREIGN KEY(usuario) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <ejecuciones_cron>
-- ----------------------------
DROP TABLE IF EXISTS ejecuciones_cron;
CREATE TABLE ejecuciones_cron (
    id_ejecucion_cron INT(11) PRIMARY KEY AUTO_INCREMENT,
    cron VARCHAR(255) NOT NULL,
    fecha TIMESTAMP(0) NOT NULL,
    estado TINYINT(1) NOT NULL,
    salida VARCHAR(512) NULL DEFAULT NULL
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <empresas>
-- ----------------------------
DROP TABLE IF EXISTS empresas;
CREATE TABLE empresas (
    id_empresa INT(255) PRIMARY KEY AUTO_INCREMENT,
    empresa VARCHAR(120) NOT NULL,
    descripcion VARCHAR(255) NULL DEFAULT NULL,
    activo TINYINT(1) UNSIGNED NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    INDEX ix_empresa_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_empresa_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <estatus>
-- ----------------------------
DROP TABLE IF EXISTS estatus;
CREATE TABLE estatus (
    id_estatus INT(11) PRIMARY KEY,
    estatus VARCHAR(255) NOT NULL,
    id_tipo_estatus INT(11) NOT NULL,
    INDEX ix_estatus_id_tipo_estatus(id_tipo_estatus) USING BTREE,
    CONSTRAINT fk_estatus_id_tipo_estatus FOREIGN KEY(id_tipo_estatus) REFERENCES tipos_estatus(id_tipo_estatus) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <grupos_permisos>
-- ----------------------------
DROP TABLE IF EXISTS grupos_permisos;
CREATE TABLE grupos_permisos (
    code VARCHAR(64) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at INT(11) NULL DEFAULT NULL,
    updated_at INT(11) NULL DEFAULT NULL
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <origenes>
-- ----------------------------
DROP TABLE IF EXISTS origenes;
CREATE TABLE origenes (
    id_origen INT(11) PRIMARY KEY,
    origen VARCHAR(100) NOT NULL
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <parametros>
-- ----------------------------
DROP TABLE IF EXISTS parametros;
CREATE TABLE parametros (
    id_parametro INT(11) PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    tipo VARCHAR(10) NOT NULL,
    valor_predeterminado VARCHAR(512) NULL DEFAULT NULL,
    valor_predeterminado_multiempresa VARCHAR(512),
    unico TINYINT(1) NOT NULL,
    privado TINYINT(1) NOT NULL,
    requerido TINYINT(1) NOT NULL,
    orden INT(0) NOT NULL,
    activo TINYINT(1) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    UNIQUE INDEX ix_parametro_codigo(codigo) USING BTREE,
    UNIQUE INDEX ux_parametro_orden(orden) USING BTREE,
    INDEX ix_parametro_usuario_version(usuario_version) USING BTREE,
    CONSTRAINT fk_parametro_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <parametros_valores>
-- ----------------------------
DROP TABLE IF EXISTS parametros_valores;
CREATE TABLE parametros_valores (
    id_parametro_valor INT(11) PRIMARY KEY AUTO_INCREMENT,
    activo TINYINT(1) NOT NULL,
    valor VARCHAR(512) NULL DEFAULT NULL,
    id_parametro INT(11) NOT NULL,
    id_empresa INT(11) NOT NULL,
    fecha_version TIMESTAMP(6) NOT NULL,
    usuario_version INT(11) NOT NULL,
    UNIQUE INDEX ux_parametro_valor_id_parametro_id_empresa(id_parametro, id_empresa) USING BTREE,
    INDEX ix_parametro_valor_usuario_version(usuario_version) USING BTREE,
    INDEX ix_parametro_valor_id_empresa(id_empresa) USING BTREE,
    CONSTRAINT fk_parametro_valor_usuario_version FOREIGN KEY(usuario_version) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_parametro_valor_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_parametro_valor_id_parametro FOREIGN KEY(id_parametro) REFERENCES parametros(id_parametro) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <permisos>
-- ----------------------------
DROP TABLE IF EXISTS permisos;
CREATE TABLE permisos (
    name VARCHAR(64) PRIMARY KEY,
    type INT(11) NOT NULL,
    description text NULL,
    rule_name VARCHAR(64) NULL DEFAULT NULL,
    data text NULL,
    created_at INT(11) NULL DEFAULT NULL,
    updated_at INT(11) NULL DEFAULT NULL,
    group_code VARCHAR(64) NULL DEFAULT NULL,
    INDEX ix_permiso_rule_name(rule_name) USING BTREE,
    INDEX ix_permiso_type(type) USING BTREE,
    INDEX ix_permiso_group_code(group_code) USING BTREE,
    CONSTRAINT fk_permiso_rule_name FOREIGN KEY(rule_name) REFERENCES reglas(name) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_permiso_group_code FOREIGN KEY(group_code) REFERENCES grupos_permisos(code) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <permisos_hijos>
-- ----------------------------
DROP TABLE IF EXISTS permisos_hijos;
CREATE TABLE permisos_hijos (
    parent VARCHAR(64) NOT NULL,
    child VARCHAR(64) NOT NULL,
    PRIMARY KEY (parent, child) USING BTREE,
    INDEX ix_permiso_hijo_child(child) USING BTREE,
    CONSTRAINT fk_permiso_hijo_parent FOREIGN KEY(parent) REFERENCES permisos(name) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_permiso_hijo_child FOREIGN KEY(child) REFERENCES permisos(name) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <reglas>
-- ----------------------------
DROP TABLE IF EXISTS reglas;
CREATE TABLE reglas (
    name VARCHAR(64) PRIMARY KEY,
    data text NULL,
    created_at INT(11) NULL DEFAULT NULL,
    updated_at INT(11) NULL DEFAULT NULL
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <tipos_estatus>
-- ----------------------------
DROP TABLE IF EXISTS tipos_estatus;
CREATE TABLE tipos_estatus (
    id_tipo_estatus INT(11) PRIMARY KEY,
    tipo_estatus VARCHAR(255) NOT NULL
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <usuarios>
-- ----------------------------
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NULL DEFAULT NULL,
    auth_key VARCHAR(32) NULL DEFAULT NULL,
    password_hash VARCHAR(255) NULL DEFAULT NULL,
    confirmation_token VARCHAR(255) NULL DEFAULT NULL,
    status INT(11) NOT NULL DEFAULT 1,
    superadmin SMALLINT(6) NULL DEFAULT 0,
    created_at INT(11) NOT NULL,
    updated_at INT(11) NOT NULL,
    created_by INT(11) NOT NULL,
    updated_by INT(11) NULL DEFAULT NULL,
    registration_ip VARCHAR(15) NULL DEFAULT NULL,
    bind_to_ip VARCHAR(255) NULL DEFAULT NULL,
    email VARCHAR(128) NULL DEFAULT NULL,
    email_confirmed smallINT(1) NOT NULL DEFAULT 0,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100),
    telefono VARCHAR(20),
    curp VARCHAR(18),
    puesto VARCHAR(255),
    area VARCHAR(255),
    numero_interno VARCHAR(64),
    id_empresa INT(11) NOT NULL,
    INDEX ix_usuario_created_by(created_by) USING BTREE,
    INDEX ix_usuario_updated_by(updated_by) USING BTREE,
    INDEX ix_usuario_id_empresa(id_empresa) USING BTREE,
    CONSTRAINT fk_usuario_created_by FOREIGN KEY(created_by) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_usuario_updated_by FOREIGN KEY(updated_by) REFERENCES usuarios(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_usuario_id_empresa FOREIGN KEY(id_empresa) REFERENCES empresas(id_empresa) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <visitas>
-- ----------------------------
DROP TABLE IF EXISTS visitas;
CREATE TABLE visitas (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    token VARCHAR(255) NOT NULL,
    ip VARCHAR(15) NOT NULL,
    language char(2) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    user_id INT(11) NULL DEFAULT NULL,
    visit_time INT(11) NOT NULL,
    browser VARCHAR(30) NULL DEFAULT NULL,
    os VARCHAR(20) NULL DEFAULT NULL,
    INDEX ix_visita_user_id(user_id) USING BTREE,
    CONSTRAINT fk_visita_user_id FOREIGN KEY(user_id) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

-- ----------------------------
-- Estructura de la tabla <versiones_app>
-- ----------------------------
DROP TABLE IF EXISTS versiones_app;
CREATE TABLE versiones_app (
    id_version_app INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    version_mayor INT(10),
    version_menor INT(10),
    url VARCHAR(100),
    fecha date
) ENGINE = InnoDB ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
