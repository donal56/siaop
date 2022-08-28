<?php
    namespace app\models;

    use webvimark\modules\UserManagement\models\User;

    class Menu {

        /**
         * Recupera un arreglo que representa al menu accesible por el usuario actual
         * @return array
         */
        public static function getItems() {

            $menu = [
                [
                    'etiqueta' => 'Ordenes de servicio',
                    'icono' => 'fa-solid fa-list-check',
                    'submenu' => self::getSubItems([
                        'ordenes-servicio' => 'Ordenes de servicio'
                    ])
                ], [
                    'etiqueta' => 'Vehículos',
                    'icono' => 'fa-solid fa-car-rear',
                    'submenu' => self::getSubItems([
                        'clases-vehiculares' => 'Clases vehiculares',
                        'marcas' => 'Marcas',
                        'tipos-combustibles' => 'Tipos de combustibles',
                        'tipos-unidades-vehiculares' => 'Tipos de unidades vehiculares',
                        'unidades-vehiculares' => 'Unidades vehiculares'
                    ])
                ], [
                    'etiqueta' => 'Catálogos',
                    'icono' => 'fa-solid fa-box',
                    'submenu' => self::getSubItems([
                        'actividades' => 'Actividades',
                        'clientes' => 'Clientes',
                        'pozos' => 'Pozos',
                        'procesos' => 'Procesos',
                        'tipos-archivos' => 'Tipos de archivos',
                        'unidades-medida' => 'Unidades de medida'
                    ])
                ], [
                    'etiqueta' => 'Seguridad',
                    'icono' => 'fa-solid fa-shield-halved',
                    'submenu' => self::getSubItems([
                        'user-management/user' => 'Usuarios',
                        'user-management/role' => 'Roles',
                        'user-management/auth-item-group' => 'Grupos de permisos',
                        'user-management/permission' => 'Permisos',
                        'user-management/user-visit-log' => 'Registro de visitas',
                        'site/parametros' => 'Parámetros'
                    ])
                ]
            ];

            return array_filter($menu, fn($menuItem, $ix) => count($menuItem['submenu']) > 0, ARRAY_FILTER_USE_BOTH);
        }

        private static function getSubItems(array $items) {
            $menu = [];

            foreach ($items as $url => $name) {
                if(User::canRoute($url)) {
                    $menu[] = [
                        'etiqueta' => $name,
                        'url' => "/$url"
                    ];
                }
            }

            return $menu;
        }
    }