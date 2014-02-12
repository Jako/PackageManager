<?php
/*
 * Package Manager
 *
 * @package packagemanager
 * @subpackage language_file
 *
 * @version 1.0.RC2
 * @author Thomas Jakobi <thomas.jakobi@partout.info>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
$_lang['modulename'] = 'Менеджер пакетов';
$_lang['packages'] = 'Пакеты';
$_lang['package'] = 'Пакет';
$_lang['packages_intro'] = 'Установленные пакеты.';
$_lang['local'] = 'Загруженные пакеты.';
$_lang['local_intro'] = '<p>Installable local packages on this MODX installation. New packages in the MODX Evolution Package Format could be uploaded below.</p>
<p>You could install each package by clicking on the install button. If you delete a package on this page, only the installation package will be removed and not the installed package in the system.</p>
<p>If you want, you could upload files in the MODX Evolution Package Format directly in the assets/packages folder. These are recognized by the Package Manager.</p>';
$_lang['reload'] = 'Обновить';
$_lang['close'] = 'Закрыть';
$_lang['close_button'] = 'Закрыть [+lang.modulename+]';
$_lang['no_local_packages'] = 'Пакеты не найдены.';
$_lang['snippets'] = 'Сниппеты';
$_lang['plugins'] = 'Плагины';
$_lang['modules'] = 'Модули';
$_lang['templates'] = 'Шаблоны';
$_lang['tmplvars'] = 'TV-параметры';
$_lang['temporary_files'] = 'Временные файлы';
$_lang['remote_files'] = 'Remote files';
$_lang['snippets_singular'] = 'Сниппет';
$_lang['plugins_singular'] = 'Плагин';
$_lang['modules_singular'] = 'Модуль';
$_lang['templates_singular'] = 'Шаблон';
$_lang['tmplvars_singular'] = 'TV-параметр';
$_lang['temporary_files_singular'] = 'Временный файл';
$_lang['remote_files_singular'] = 'Remote file';
$_lang['author'] = 'Автор';
$_lang['install'] = 'Установить';
$_lang['delete'] = 'Удалить';
$_lang['edit'] = 'Изменить';
$_lang['upload'] = 'Загрузить';
$_lang['upload_local'] = 'Local file:';
$_lang['upload_remote'] = 'Remote url:';
$_lang['results'] = 'Results';
$_lang['confirm_delete_extra'] = 'Вы уверены, что хотите удалить?';
$_lang['search_search'] = 'Искать';
$_lang['search_reset'] = 'Очистить';
$_lang['installed_version'] = 'Установленная версия';
$_lang['installable_version'] = 'Доступная версия';
$_lang['install_backup'] = 'Сохранить старую версию';
$_lang['install_replace'] = 'Заменить старую версию';
$_lang['install_results'] = 'Результаты установки';
$_lang['install_update_success'] = '[+type+] [+name+] успешно обновлен.';
$_lang['install_update_error'] = '[+type+] [+name+] не удалось обновить: [+error+].';
$_lang['install_add_success'] = '[+type+] [+name+] успешно установлен.';
$_lang['install_add_error'] = '[+type+] [+name+] не удалось установить: [+error+].';
$_lang['install_legacy'] = 'Legacy-плагины [+legacies+] отключены.';
$_lang['install_files_success'] = 'Файлы пакета сохранены в папку [+folder+].';
$_lang['install_files_success_backup'] = 'Копия файлов пакета сохранена в папку [+folder+].';
$_lang['install_depedency_created'] = 'Модуль [+modulename+]: Зависимость создана.';
$_lang['install_depedency_updated'] = 'Модуль [+modulename+]: Зависимость обновлена.';
$_lang['install_depedency_guid_set'] = '[+type+] [+dependancyname+]: задан GUID.';
$_lang['delete_success'] = '[+type+] [+name+] deleted.';
$_lang['delete_error'] = '[+type+] not deleted.';
$_lang['delete_error_type'] = '[+type+] not found.';
$_lang['file_delete_success'] = 'Пакет успешно удален.';
$_lang['file_upload_success'] = '[+filename+] успешно загружен.';
$_lang['file_upload_error'] = 'Не удалось загрузить [+filename+]. Проверьте права на папку assets/packages.';
$_lang['file_upload_error_exists'] = '[+lang.package:ucfirst+] [+package+] уже существует.';
$_lang['file_upload_error_package'] = '[+filename+] не является пакетом MODX Evolution.';
$_lang['file_upload_error_type'] = 'Неверный тип файла [+filename+].';
$_lang['file_upload_nofile'] = 'No file was uploaded.';
$_lang['file_extract_success'] = '[+filename+] successful extracted.';
$_lang['file_extract_error'] = 'Не удалось распаковать [+filename+]. Проверьте права на папку [+destination+].';
$_lang['file_extract_error_open'] = '[+filename+] не является zip-архивом.';
$_lang['runtime_error_packages_folder'] = 'The folder assets/packages could not be created. Please create it yourself and make it writable for PHP.';
$_lang['runtime_error_privileges_install'] = 'You don\'t have enough privileges for installing packages!';
$_lang['runtime_error_privileges_delete'] = 'You don\'t have enough privileges for deleting [+type+]!';
?>