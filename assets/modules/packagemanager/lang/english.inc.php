<?php
/*
 * Package Manager
 *
 * @package packagemanager
 * @subpackage language_file
 *
 * @version 1.0-RC3
 * @author Thomas Jakobi <thomas.jakobi@partout.info>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
$_lang['modulename'] = 'Package Manager';
$_lang['packages'] = 'Packages';
$_lang['package'] = 'Package';
$_lang['packages_intro'] = '<p>Installed packages on this installation.</p>';
$_lang['local'] = 'Local';
$_lang['local_intro'] = '<p>Installable local packages on this MODX installation. New packages in the MODX Evolution Package Format could be uploaded below.</p>
<p>You could install each package by clicking on the install button. If you delete a package on this page, only the installation package will be removed and not the installed package in the system.</p>
<p>If you want, you could upload files in the MODX Evolution Package Format directly in the assets/packages folder. These are recognized by the Package Manager.</p>';
$_lang['reload'] = 'Reload';
$_lang['close'] = 'Close';
$_lang['close_button'] = 'Close [+lang.modulename+]';
$_lang['no_local_packages'] = 'No installable local packages found.';
$_lang['snippets'] = 'Snippets';
$_lang['plugins'] = 'Plugins';
$_lang['modules'] = 'Modules';
$_lang['templates'] = 'Templates';
$_lang['tmplvars'] = 'Template Variables';
$_lang['temporary_files'] = 'Temporary files';
$_lang['remote_files'] = 'Remote files';
$_lang['snippets_singular'] = 'Snippet';
$_lang['plugins_singular'] = 'Plugin';
$_lang['modules_singular'] = 'Module';
$_lang['templates_singular'] = 'Template';
$_lang['tmplvars_singular'] = 'Template Variable';
$_lang['temporary_files_singular'] = 'Temporary file';
$_lang['remote_files_singular'] = 'Remote file';
$_lang['author'] = 'Author';
$_lang['install'] = 'Install';
$_lang['delete'] = 'Delete';
$_lang['edit'] = 'Edit';
$_lang['upload'] = 'Upload';
$_lang['upload_local'] = 'Local file:';
$_lang['upload_remote'] = 'Remote url:';
$_lang['results'] = 'Results';
$_lang['confirm_delete_extra'] = 'Are you sure you want to delete this Extra?';
$_lang['search_search'] = 'Search';
$_lang['search_reset'] = 'Reset';
$_lang['installed_version'] = 'Installed version';
$_lang['installable_version'] = 'Installable version';
$_lang['install_backup'] = 'Backup old version';
$_lang['install_replace'] = 'Replace old version';
$_lang['install_results'] = 'Installation Results';
$_lang['install_update_success'] = '[+type+] [+name+] successful updated.';
$_lang['install_update_error'] = '[+type+] [+name+] could not be updated: [+error+].';
$_lang['install_add_success'] = '[+type+] [+name+] successful added.';
$_lang['install_add_error'] = '[+type+] [+name+] could not be added: [+error+].';
$_lang['install_legacy'] = 'Legacy plugins [+legacies+] disabled.';
$_lang['install_files_success'] = 'Package files saved in [+folder+].';
$_lang['install_files_success_backup'] = 'Backup of package files saved in [+folder+].';
$_lang['install_depedency_created'] = '[+modulename+] Module: Depedency created.';
$_lang['install_depedency_updated'] = '[+modulename+] Module: Depedency updated.';
$_lang['install_depedency_guid_set'] = '[+type+] [+depedencyname+]: GUID set.';
$_lang['delete_success'] = '[+type+] [+name+] deleted.';
$_lang['delete_error'] = '[+type+] not deleted.';
$_lang['delete_error_type'] = '[+type+] not found.';
$_lang['file_delete_success'] = 'Package successful deleted.';
$_lang['file_upload_success'] = '[+filename+] successful uploaded.';
$_lang['file_upload_error'] = '[+filename+] was not uploaded. Is the folder assets/packages writable?';
$_lang['file_upload_error_exists'] = '[+lang.package:ucfirst+] [+package+] already exists.';
$_lang['file_upload_error_package'] = '[+filename+] is not a valid MODX Evolution package.';
$_lang['file_upload_error_type'] = '[+filename+] has the wrong file type.';
$_lang['file_upload_nofile'] = 'No file was uploaded.';
$_lang['file_extract_success'] = '[+filename+] successful extracted.';
$_lang['file_extract_error'] = '[+filename+] could not be extracted. Is the folder [+destination+] writable?';
$_lang['file_extract_error_open'] = '[+filename+] could not be opened as zip file.';
$_lang['create_folder_error'] = '[+folder+] could not be created.';
$_lang['copy_folder_error'] = '[+folder+] could not be copied.';
$_lang['runtime_error_packages_folder'] = 'The folder assets/packages could not be created. Please create it yourself and make it writable for PHP.';
$_lang['runtime_error_privileges_install'] = 'You don\'t have enough privileges for installing packages!';
$_lang['runtime_error_privileges_delete'] = 'You don\'t have enough privileges for deleting [+type+]!';
?>