Reseller Users Info plugin for DirectAdmin
==========================================


Installation
------------

~~~~
cd /usr/local/directadmin/plugins/
git clone https://github.com/websafe/dap-reseller-users-info.git
cd dap-reseller-users-info
mkdir -p vendor/bin
wget -O - https://getcomposer.org/installer | php -- --install-dir=vendor/bin
vendor/bin/composer.php update
~~~~


----
[Directadmin] is a registered trademark of [JBMC Software].


[DirectAdmin]: http://www.directadmin.com/
[JBMC Software]: http://www.jbmc-software.com/
