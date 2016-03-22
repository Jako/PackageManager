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
$_lang['modulename'] = 'Pakethanterare';
$_lang['packages'] = 'Paket';
$_lang['package'] = 'Paket';
$_lang['packages_intro'] = '<p>Installerade paket i den här installationen.</p>';
$_lang['local'] = 'Lokal';
$_lang['local_intro'] = '<p>Installationsbara lokala paket för den här MODX-installationen. Nya paket i MODX Evolutions paketformat kan laddas upp nedan.</p>
<p>Du kan installera ett paket genom att klicka på installationsknappen. Om du tar bort ett paket på den här sidan kommer bara installationspaketet att tas bort och inte det installerade paketet i systemet.</p>
<p>Du kan ladda upp filer i paketformatet för MODX Evolution direkt till katalogen assets/packages. De kommer att upptäckas av pakethanteraren.</p>';
$_lang['reload'] = 'Ladda om';
$_lang['close'] = 'Stäng';
$_lang['close_button'] = 'Stäng [+lang.modulename+]';
$_lang['no_local_packages'] = 'Det gick inte att hitta några lokala installationsbara paket.';
$_lang['snippets'] = 'Snippets';
$_lang['plugins'] = 'Plugins';
$_lang['modules'] = 'Modul';
$_lang['templates'] = 'Mallar';
$_lang['tmplvars'] = 'Mallvariabler';
$_lang['temporary_files'] = 'Temporära filer';
$_lang['remote_files'] = 'Fjärrfiler';
$_lang['snippets_singular'] = 'Snippet';
$_lang['plugins_singular'] = 'Plugin';
$_lang['modules_singular'] = 'Modul';
$_lang['templates_singular'] = 'Mall';
$_lang['tmplvars_singular'] = 'Mallvariabel';
$_lang['temporary_files_singular'] = 'Temporär fil';
$_lang['remote_files_singular'] = 'Fjärrfil';
$_lang['author'] = 'Författare';
$_lang['install'] = 'Installera';
$_lang['delete'] = 'Ta bort';
$_lang['edit'] = 'Redigera';
$_lang['upload'] = 'Ladda upp';
$_lang['upload_local'] = 'Lokal fil:';
$_lang['upload_remote'] = 'Fjärr-URL:';
$_lang['results'] = 'Resultat';
$_lang['confirm_delete_extra'] = 'Är du säker på att du vill ta bort detta Extra?';
$_lang['search_search'] = 'Sök';
$_lang['search_reset'] = 'Återställ';
$_lang['installed_version'] = 'Installerad version';
$_lang['installable_version'] = 'Installationsbar version';
$_lang['install_backup'] = 'Säkerhetskopiera gammal version';
$_lang['install_replace'] = 'Ersätt gammal version';
$_lang['install_results'] = 'Installationsresultat';
$_lang['install_update_success'] = '[+type+] [+name+] uppdaterades.';
$_lang['install_update_error'] = '[+type+] [+name+] kunde inte uppdateras: [+error+].';
$_lang['install_add_success'] = '[+type+] [+name+] lades till.';
$_lang['install_add_error'] = '[+type+] [+name+] kunde inte läggas till: [+error+].';
$_lang['install_legacy'] = 'Föråldrade plugins [+legacies+] inaktiverade.';
$_lang['install_files_success'] = 'Paketfilerna sparade i [+folder+].';
$_lang['install_files_success_backup'] = 'Säkerhetskopierade paketfiler sparade i [+folder+].';
$_lang['install_depedency_created'] = '[+modulename+] Modul: Beroende skapat.';
$_lang['install_depedency_updated'] = '[+modulename+] Modul: Beroende uppdaterat.';
$_lang['install_depedency_guid_set'] = '[+type+] [+depedencyname+]: GUID angivet.';
$_lang['delete_success'] = '[+type+] [+name+] togs bort.';
$_lang['delete_error'] = '[+type+] togs inte bort.';
$_lang['delete_error_type'] = '[+type+] kunde inte hittas.';
$_lang['file_delete_success'] = 'Paketet togs bort.';
$_lang['file_upload_success'] = '[+filename+] laddades upp.';
$_lang['file_upload_error'] = '[+filename+] laddades inte upp. Är katalogen assets/packages skrivbar?';
$_lang['file_upload_error_exists'] = '[+lang.package:ucfirst+] [+package+] finns redan.';
$_lang['file_upload_error_package'] = '[+filename+] är inte ett giltigt paket för MODX Evolution.';
$_lang['file_upload_error_type'] = '[+filename+] har fel filtyp.';
$_lang['file_upload_nofile'] = 'Ingen fil laddades upp.';
$_lang['file_extract_success'] = '[+filename+] extraherades.';
$_lang['file_extract_error'] = '[+filename+] kunde inte packas upp. Är katalogen [+destination+] skrivbar?';
$_lang['file_extract_error_open'] = '[+filename+] kunde inte öppnas som en zip-fil.';
$_lang['create_folder_error'] = '[+folder+] kunde inte skapas.';
$_lang['copy_folder_error'] = '[+folder+] kunde inte kopieras.';
$_lang['runtime_error_packages_folder'] = 'Mappen assets/packages kunde inte skapas. Skapa den själv och gör den skrivbar för PHP.';
$_lang['runtime_error_privileges_install'] = 'Du har inte tillräcklig behörighet för att kunna installera paket.';
$_lang['runtime_error_privileges_delete'] = 'Du har inte tillräcklig behörighet för att kunna ta bort [+type+]!';
?>