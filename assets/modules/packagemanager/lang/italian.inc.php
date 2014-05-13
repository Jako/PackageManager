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
$_lang['modulename'] = 'Gestione dei Pacchetti';
$_lang['packages'] = 'Pacchetti';
$_lang['package'] = 'Pacchetto';
$_lang['packages_intro'] = '<p>Pacchetti installati con questa installazione.</p>';
$_lang['local'] = 'Locale';
$_lang['local_intro'] = '<p>Pacchetti locali disponibili per questa installazione di MODX. Qui potete caricare nuovi pacchetti nel formato MODX Evolution.</p>
<p>Per installare un pacchetto fate click sul bottone Installa. Se cancellate un pacchetto da questa pagina verrà rimosso solo il pacchetto impedendo nuove installazioni, se è già stato installato non verrà automaticamente disinstallato.</p>
<p>E\' possibile caricare i pacchetti nel formato MODX Evolution direttamente nella cartella assets/packages e saranno automaticamente riconosciuti dal Package Manager.</p>';
$_lang['reload'] = 'Ricarica';
$_lang['close'] = 'Chiudi';
$_lang['close_button'] = 'Chiudi [+lang.modulename+]';
$_lang['no_local_packages'] = 'Non è stato trovato nessun pacchetto locale da installare.';
$_lang['snippets'] = 'Snippets';
$_lang['plugins'] = 'Plugins';
$_lang['modules'] = 'Moduli';
$_lang['templates'] = 'Templates';
$_lang['tmplvars'] = 'Variabili di Template';
$_lang['temporary_files'] = 'Files temporanei';
$_lang['remote_files'] = 'File remoti';
$_lang['snippets_singular'] = 'Snippet';
$_lang['plugins_singular'] = 'Plugin';
$_lang['modules_singular'] = 'Modulo';
$_lang['templates_singular'] = 'Template';
$_lang['tmplvars_singular'] = 'Variabile di Template';
$_lang['temporary_files_singular'] = 'File temporaneo';
$_lang['remote_files_singular'] = 'File remoto';
$_lang['author'] = 'Autore';
$_lang['install'] = 'Installa';
$_lang['delete'] = 'Cancella';
$_lang['edit'] = 'Modifica';
$_lang['upload'] = 'Carica';
$_lang['upload_local'] = 'File locale:';
$_lang['upload_remote'] = 'URL remoto:';
$_lang['results'] = 'Risultati';
$_lang['confirm_delete_extra'] = 'Siete sicuri di voler cancellare questo Extra?';
$_lang['search_search'] = 'Cerca';
$_lang['search_reset'] = 'Resetta';
$_lang['installed_version'] = 'Versione installata';
$_lang['installable_version'] = 'Versione installabile';
$_lang['install_backup'] = 'Backup della vecchia versione';
$_lang['install_replace'] = 'Sostituisci la vecchia versione';
$_lang['install_results'] = 'Risultati dell\'installazione';
$_lang['install_update_success'] = '[+type+] [+name+] aggiornato con successo.';
$_lang['install_update_error'] = 'Impossibile aggiornare [+type+] [+name+]: [+error+].';
$_lang['install_add_success'] = '[+type+] [+name+] aggiunto con successo.';
$_lang['install_add_error'] = 'Impossibile aggiungere [+type+] [+name+]: [+error+].';
$_lang['install_legacy'] = 'Sono stati disabilitati i vecchi plugins [+legacies+].';
$_lang['install_files_success'] = 'Il pacchetto è stato salvato in [+folder+].';
$_lang['install_files_success_backup'] = 'Il backup dei files del pacchetto è stato salvato in [+folder+].';
$_lang['install_depedency_created'] = 'Modulo [+modulename+]: creata la dipendenza.';
$_lang['install_depedency_updated'] = 'Modulo [+modulename+]: aggiornata la dipendenza.';
$_lang['install_depedency_guid_set'] = '[+type+] [+depedencyname+]: impostata GUID.';
$_lang['delete_success'] = 'Cancellato [+type+] [+name+].';
$_lang['delete_error'] = 'Impossibile cancellare [+type+].';
$_lang['delete_error_type'] = 'Impossibile trovare [+type+].';
$_lang['file_delete_success'] = 'Il pacchetto è stato cancellato.';
$_lang['file_upload_success'] = '[+filename+] è stato caricato con successo.';
$_lang['file_upload_error'] = '[+filename+] non è stato caricato. Occorre aggiungere i permessi di scrittura alla cartella assets/packages?';
$_lang['file_upload_error_exists'] = 'Il pacchetto [+lang.package:ucfirst+] [+package+] esiste già.';
$_lang['file_upload_error_package'] = '[+filename+] non è un pacchetto per MODX Evolution.';
$_lang['file_upload_error_type'] = '[+filename+] ha un tipo di file errato.';
$_lang['file_upload_nofile'] = 'Nessun file caricato.';
$_lang['file_extract_success'] = '[+filename+] estratto con successo.';
$_lang['file_extract_error'] = 'Impossibile estrarre [+filename+]. Si può scrivere nella cartella [+destination+]?';
$_lang['file_extract_error_open'] = 'impossibile aprire [+filename+] come file zip.';
$_lang['create_folder_error'] = '[+folder+] could not be created.';
$_lang['copy_folder_error'] = '[+folder+] could not be copied.';
$_lang['runtime_error_packages_folder'] = 'Impossibile creare la cartella assets/packages. Occorre crearla manualmente e dare i permessi di scrittura a PHP.';
$_lang['runtime_error_privileges_install'] = 'Non avete abbastanza privilegi per installare pacchetti!';
$_lang['runtime_error_privileges_delete'] = 'Non avete abbastanza provilegi per cancellare [+type+]!';
?>