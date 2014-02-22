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
$_lang['local_intro'] = '<p>Устанавливаемые пакеты, хранящиеся локально в MODX. Новые пакеты в формате MODX Evolution могут быть загружены ниже.</p>
<p>Вы можете установить каждый пакет кликая кнопку Установить. Если вы удаляете пакет на этой странице, то удаляются только файлы, а из системы нужно удалять самостоятельно в управлении елементами или в редактировании модулей.</p>
<p>Если вы хотите, вы можете загрузить файлы в формате MODX Evolution прямо в папку assets/packages. Они распознаются менеджером пакетов.</p>';
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
$_lang['remote_files'] = 'Удаленные файлы';
$_lang['snippets_singular'] = 'Сниппет';
$_lang['plugins_singular'] = 'Плагин';
$_lang['modules_singular'] = 'Модуль';
$_lang['templates_singular'] = 'Шаблон';
$_lang['tmplvars_singular'] = 'TV-параметр';
$_lang['temporary_files_singular'] = 'Временный файл';
$_lang['remote_files_singular'] = 'Удаленный фаил';
$_lang['author'] = 'Автор';
$_lang['install'] = 'Установить';
$_lang['delete'] = 'Удалить';
$_lang['edit'] = 'Редактировать';
$_lang['upload'] = 'Загрузить';
$_lang['upload_local'] = 'Локальный файл:';
$_lang['upload_remote'] = 'Удаленный адрес:';
$_lang['results'] = 'Результаты';
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
$_lang['delete_success'] = '[+type+] [+name+] удален.';
$_lang['delete_error'] = '[+type+] не удален.';
$_lang['delete_error_type'] = '[+type+] не найден.';
$_lang['file_delete_success'] = 'Пакет успешно удален.';
$_lang['file_upload_success'] = '[+filename+] успешно загружен.';
$_lang['file_upload_error'] = 'Не удалось загрузить [+filename+]. Проверьте права на папку assets/packages.';
$_lang['file_upload_error_exists'] = '[+lang.package:ucfirst+] [+package+] уже существует.';
$_lang['file_upload_error_package'] = '[+filename+] не является пакетом MODX Evolution.';
$_lang['file_upload_error_type'] = 'Неверный тип файла [+filename+].';
$_lang['file_upload_nofile'] = 'Файл не был загружен.';
$_lang['file_extract_success'] = '[+filename+] успешно распакован.';
$_lang['file_extract_error'] = 'Не удалось распаковать [+filename+]. Проверьте права на папку [+destination+].';
$_lang['file_extract_error_open'] = '[+filename+] не является zip-архивом.';
$_lang['runtime_error_packages_folder'] = 'Папка assets/packages не может быть создана. Пожалуйста, создайте её самостоятельно или сделайте папку доступную для записи через PHP.';
$_lang['runtime_error_privileges_install'] = 'У вас недостаточно прав для установки пакетов!';
$_lang['runtime_error_privileges_delete'] = 'У вас недостаточно прав для удаления [+type+]!';
?>