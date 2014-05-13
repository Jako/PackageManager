<?php
/*
 * Package Manager
 *
 * @package packagemanager
 * @subpackage language_file
 *
 * @version 1.0-RC4
 * @author Thomas Jakobi <thomas.jakobi@partout.info>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
$_lang['modulename'] = 'Package-Verwaltung';
$_lang['packages'] = 'Extras';
$_lang['package'] = 'Extra';
$_lang['packages_intro'] = '<p>Installierte Extras in dieser MODX Installation.</p>';
$_lang['local'] = 'Lokal';
$_lang['local_intro'] = '<p>Installierbare lokale Extras in dieser MODX Installation. Neue Extras im MODX Evolution Package Format können unterhalb hochgeladen werden.</p>
<p>Sie können jedes Extra installieren, indem Sie auf den Installieren Button klicken. Wenn Sie ein Extra auf dieser Seite löschen, wird nur das Installations Paket gelöscht und nicht das installierte Paket im System.</p>
<p>Sie können Dateien im MODX Evolution Package Format direkt im Ordner assets/packages in Ihrer MODX Installation hochladen. Diese werden vom Package-Manager erkannt.</p>';
$_lang['reload'] = 'Neu laden';
$_lang['close'] = 'Schließen';
$_lang['close_button'] = '[+lang.modulename+] Schließen';
$_lang['no_local_packages'] = 'Keine installierbaren lokalen Extras gefunden.';
$_lang['snippets'] = 'Snippets';
$_lang['plugins'] = 'Plugins';
$_lang['modules'] = 'Module';
$_lang['templates'] = 'Templates';
$_lang['tmplvars'] = 'Template-Variablen';
$_lang['temporary_files'] = 'Temporäre Dateien';
$_lang['remote_files'] = 'Entfernte Dateien';
$_lang['snippets_singular'] = 'Snippet';
$_lang['plugins_singular'] = 'Plugin';
$_lang['modules_singular'] = 'Modul';
$_lang['templates_singular'] = 'Template';
$_lang['tmplvars_singular'] = 'Template-Variable';
$_lang['temporary_files_singular'] = 'Temporäre Datei';
$_lang['remote_files_singular'] = 'Entfernte Datei';
$_lang['author'] = 'Autor';
$_lang['install'] = 'Installieren';
$_lang['delete'] = 'Löschen';
$_lang['edit'] = 'Editieren';
$_lang['upload'] = 'Hochladen';
$_lang['upload_local'] = 'Lokale Datei:';
$_lang['upload_remote'] = 'Entfernte URL:';
$_lang['results'] = 'Ergebnis';
$_lang['confirm_delete_extra'] = 'Sind Sie sicher, das Sie dieses Extra löschen möchten?';
$_lang['search_search'] = 'Suchen';
$_lang['search_reset'] = 'Zurücksetzen';
$_lang['installed_version'] = 'Installierte Version';
$_lang['installable_version'] = 'Installierbare Version';
$_lang['install_backup'] = 'Alte Version sichern';
$_lang['install_replace'] = 'Alte Version überschreiben';
$_lang['install_results'] = 'Installations-Ergebnisse';
$_lang['install_update_success'] = '[+type+] [+name+] erfolgreich aktualisiert.';
$_lang['install_update_error'] = '[+type+] [+name+] konnte nicht aktualisiert werden: [+error+].';
$_lang['install_add_success'] = '[+type+] [+name+] erfolgreich hinzugefügt.';
$_lang['install_add_error'] = '[+type+] [+name+] konnte nicht hinzugefügt werden: [+error+].';
$_lang['install_legacy'] = 'Veraltete Plugins [+legacies+] wurden deaktiviert.';
$_lang['install_files_success'] = 'Die Dateien des Extras wurden im Ordner [+folder+] gespeichert.';
$_lang['install_files_success_backup'] = 'Die Backup-Dateien des Extras wurden im Ordner [+folder+] gespeichert.';
$_lang['install_depedency_created'] = '[+modulename+] Modul: Abhängigkeit erstellt';
$_lang['install_depedency_updated'] = '[+modulename+] Modul: Abhängigkeit aktualisiert';
$_lang['install_depedency_guid_set'] = '[+type+] [+depedencyname+]: GUID gesetzt';
$_lang['delete_success'] = '[+type+] [+name+] gelöscht.';
$_lang['delete_error'] = '[+type+] nicht gelöscht.';
$_lang['delete_error_type'] = '[+type+] nicht gefunden.';
$_lang['file_delete_success'] = 'Extra erfolgreich gelöscht.';
$_lang['file_upload_success'] = '[+filename+] erfolgreich hochgeladen.';
$_lang['file_upload_error'] = '[+filename+] wurde nicht hochgeladen. Ist der Ordner assets/packages beschreibbar?';
$_lang['file_upload_error_exists'] = '[+lang.package:ucfirst+] [+package+] existiert schon.';
$_lang['file_upload_error_package'] = '[+filename+] ist kein gültiges MODX Evolution Package.';
$_lang['file_upload_error_type'] = '[+filename+] hat den falschen Datei-Typ.';
$_lang['file_upload_nofile'] = 'Es wurde keine Datei hochgeladen.';
$_lang['file_extract_success'] = '[+filename+] erfolgreich entpackt.';
$_lang['file_extract_error'] = '[+filename+] konnte nicht entpackt werden. Ist der Ordner [+destination+] beschreibbar?';
$_lang['file_extract_error_open'] = '[+filename+] konnte nicht als ZIP-Datei geöffnet werden.';
$_lang['create_folder_error'] = '[+folder+] konnte nicht erstellt werden.';
$_lang['copy_folder_error'] = '[+folder+] konnte nicht kopiert werden.';
$_lang['runtime_error_packages_folder'] = 'Der Ordner assets/packages kann nicht angelegt werden. Bitte legen Sie ihn selbst an und machen Sie ihn für PHP beschreibbar.';
$_lang['runtime_error_privileges_install'] = 'Sie haben nicht genügend Rechte, um Extras zu installieren!';
$_lang['runtime_error_privileges_delete'] = 'Sie haben nicht genügend Rechte um [+type+] zu löschen!';
?>