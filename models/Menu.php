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
                    'etiqueta' => 'Seguridad',
                    'icono' => 'flaticon-025-setttings',
                    'submenu' => []
                ]
            ];

            if(User::canRoute('user-management/user/index')) {
                $menu[0]['submenu'][] = [
                    'etiqueta' => 'Usuarios',
                    'icono' => 'fas fa-users-cog',
                    'url' => '/user-management/user'
                ];
            }
            
            if(User::canRoute('user-management/user-visit-log/index')) {
                $menu[0]['submenu'][] = [
                    'etiqueta' => 'Registro de visitas',
                    'icono' => 'fas fa-signal',
                    'url' => '/user-management/user-visit-log'
                ];
            }
            
            if(User::canRoute('user-management/role')) {
                $menu[0]['submenu'][] = [
                    'etiqueta' => 'Roles',
                    'icono' => 'fas fa-user-tag',
                    'url' => '/user-management/role'
                ];
            }
            
            if(User::canRoute('user-management/auth-item-group')) {
                $menu[0]['submenu'][] = [
                    'etiqueta' => 'Grupos de permisos',
                    'icono' => 'fas fa-check-double',
                    'url' => '/user-management/auth-item-group'
                ];
            }
            
            if(User::canRoute('user-management/permission')) {
                $menu[0]['submenu'][] = [
                    'etiqueta' => 'Permisos',
                    'icono' => 'fas fa-check',
                    'url' => '/user-management/permission'
                ];
            }

            return array_filter($menu, fn($menuItem, $ix) => count($menuItem['submenu']) > 0, ARRAY_FILTER_USE_BOTH);
        }
    }