Package Manager
================================================================================

Easy install extra packages
for the MODX Evolution content management framework

Features
--------------------------------------------------------------------------------
With this Module you could easy install extra packages in MODX Evolution. Just download the extra package zip file, upload it in the Package Manager Module and install it. All Snippets, Plugins and Modules are created with no hazzle.

Installation
--------------------------------------------------------------------------------
1. Upload the folder *assets/modules/packagemanager* to the corresponding folder in your installation
2. Create a new Module called Package Manager and fill the Module code with `include_once(MODX_BASE_PATH . 'assets/modules/packagemanager/packagemanager.module.php');`
3. Reload the Manager (or just the top frame of the Manager) and open the Package Manager Module.

Notes
--------------------------------------------------------------------------------
1. If you want, you could upload MODX installer compatible package files directly in the assets/packages folder. These are recognized by the Package Manager.


