Package Manager
================================================================================

Easy install extra packages
for the MODX Evolution content management framework

Features
--------------------------------------------------------------------------------
With this Module you could easy install extra packages in MODX Evolution. Just download the extra package zip file (see note 1), upload it (see note 2) in the Package Manager Module and install it. All Snippets, Plugins and Modules are created with no hazzle.

Installation
--------------------------------------------------------------------------------
1. Upload the folder *assets/modules/packagemanager* to the corresponding folder in your installation
2. Create a new Module called Package Manager and fill the Module code with `include_once(MODX_BASE_PATH . 'assets/modules/packagemanager/packagemanager.module.php');`
3. Create the folder *assets/packages* and make it writable for PHP.
4. Reload the Manager (or just the top frame of the Manager) and open the Package Manager Module.

Example Images
--------------

Install/Upload packages:

![Eventlist example](https://raw.github.com/Jako/PackageManager/master/packagemanager.install.jpg)

Manage packages:

![Images example](https://raw.github.com/Jako/PackageManager/master/packagemanager.manage.jpg)

MODX Evolution Packages
--------------------------------------------------------------------------------
The format requirements for these files are quite simple (since they do not provide any uninstall features like Revolution Transport Packages);

- They could contain an assets folder that contents will be copied to the MODX assets folder. If you use the backup option, the old files are saved in a `.old` folder. 
- They should contain an install folder with type based subfolders (templates, tvs, chunks, modules, snippets, plugins). In those subfolders a .tpl file has to be created for each installed template, template variable, chunk, module, snippet and plugin. This file starts with a DocBlock (templates, template variables and chunks too) and the code.

The DocBlock starts with the name of the installed extra. The extra description starts after an empty line. The relevant installation informations should be inserted as DocBlock tag. The following tags are recognized by Package Manager:

Tag | Description
--- | -----------
@version | Version
@author  | Author
@internal @modx_category | Category
@internal @caption | Caption (for Template Variables)
@internal @input_type | Input Type (for Template Variables)
@internal @input_options | Input Option Values (for Template Variables)
@internal @input_default | Default Value (for Template Variables)
@internal @output_widget | Widget (for Template Variables)
@internal @output_widget_params | Widget Properties (for Template Variables)
@internal @guid | GUID (for Modules with enabled parameter sharing)
@internal @enable_sharedparams | Enable parameter sharing (for Modules)
@internal @properties | Module/Plugin configuration (for Plugins and Modules)
@internal @disabled | Plugin Disabled (for Plugins)
@internal @installset | Install Set (for the MODX Evolution installer)

As an example follows the Package Manager Module .tpl DocBlock:

```
/**
 * Package Manager
 *
 * Easy install extra packages
 *
 * @category 	module
 * @version 	1.0-RC
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @author      Jako
 * @internal	@modx_category Manager and Admin
 * @internal    @installset base, sample
 */
```

Notes
--------------------------------------------------------------------------------
1. The extra packages have to use the MODX Evolution Package format.
2. If you want, you could upload multiple MODX Evolution Package files directly in the assets/packages folder. These are recognized by the Package Manager.

