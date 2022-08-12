SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO origenes VALUES (1, 'WEB'), (2, 'MOVIL');

INSERT INTO empresas VALUES (1, 'Empresa 1', '', 1, CURRENT_TIMESTAMP, 1);

INSERT INTO usuarios VALUES (1, 'superadmin', 'uK_MurTu_J8lfwmoOYcuSdUelnbt-Lhl', '$2y$13$pteNp/lmyNMtMN.B9jZy6el6plclbtcBrLsVcWxFS/CrUi4gkXfTe', NULL, 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, 1, NULL, NULL, 'donal_56@hotmail.com', 1, 'Carlos Donaldo', 'Ramón', 'Gómez', NULL, NULL, NULL, NULL, NULL, 1);

SET FOREIGN_KEY_CHECKS = 1;
