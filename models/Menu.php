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
                    'etiqueta' => 'Catálogos',
                    'icono' => 'bi bi-box-seam',
                    'submenu' => self::getSubItems([
                        'actividades' => 'Actividades',
                        'clases-vehiculares' => 'Clases Vehiculares',
                        'clientes' => 'Clientes',
                        'formatos' => 'Formatos',
                        'marcas' => 'Marcas',
                        'pozos' => 'Pozos',
                        'unidades-medida' => 'Unidades de medida',
                        'tipos-archivos' => 'Tipos de archivos',
                        'tipos-combustibles' => 'Tipos de combustibles',
                        'tipos-formatos' => 'Tipos de formatos',
                        'tipos-unidades-vehiculares' => 'Tipos de unidades vehiculares',
                        'unidades-vehiculares' => 'Unidades vehiculares',
                    ])
                ], [
                    'etiqueta' => 'Seguridad',
                    'icono' => 'bi bi-shield-lock',
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