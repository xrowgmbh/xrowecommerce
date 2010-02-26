@echo on
cd C:\workspace\shop
RMDIR /S /Q var\storage\packages\eZ-systems
RMDIR /S /Q var\storage\packages\local
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin create xrowecommerce_classes "xrowecommerce classes" 1.1-0 install
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin add xrowecommerce_classes ezcontentclass xrow_product_category,xrow_product_review,xrow_product,manufacturer,coupon,client
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin set xrowecommerce_classes type contentclass
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin set xrowecommerce_classes 'vendor' 'xrow GmbH'
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin set xrowecommerce_classes 'state' 'stable'
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin create xrowecommerce_content "xrowecommerce content" 1.1-0 install
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin set xrowecommerce_content type contentobject
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin set xrowecommerce_content 'vendor' 'xrow GmbH'
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin set xrowecommerce_content 'state' 'stable'
"C:\Program Files (x86)\Zend\Zend Studio - 7.0.1\plugins\org.zend.php.debug.debugger.win32.x86_5.2.26.v20090817\resources\php5\php-win.exe" ezpm.php -s ezwebin_site_admin add xrowecommerce_content ezcontentobject --exclude-classes --exclude-templates --current-version "democontent/*" "manufacturer/*" "shop2" "news/*" "beer/*" "coupons/*" "products/*"
RMDIR /S /Q var\storage\packages\local\xrowecommerce_classes\.cache
RMDIR /S /Q var\storage\packages\local\xrowecommerce_content\.cache
cd C:\svn\xrowecommerce\trunk\packages
svn rm xrowecommerce_content
svn rm xrowecommerce_classes
RMDIR /S /Q xrowecommerce_classes
RMDIR /S /Q xrowecommerce_content
svn commit -m"remove packages"
XCOPY /Y /E /I C:\workspace\shop\var\storage\packages\local\xrowecommerce_classes C:\svn\xrowecommerce\trunk\packages\xrowecommerce_classes
XCOPY /Y /E /I C:\workspace\shop\var\storage\packages\local\xrowecommerce_content C:\svn\xrowecommerce\trunk\packages\xrowecommerce_content
svn add xrowecommerce_content
svn add xrowecommerce_classes
svn commit -m"add packages"