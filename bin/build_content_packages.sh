rm -Rf var/storage/packages/eZ-systems/*
rm -Rf var/storage/packages/local/*
php ezpm.php -s ezwebin_site_admin create xrowecommerce_classes "xrowecommerce classes" 1.1-0 install
php ezpm.php -s ezwebin_site_admin add xrowecommerce_classes ezcontentclass xrow_product_category,xrow_product_review,xrow_product,manufacturer,coupon,client,xrow_package
php ezpm.php -s ezwebin_site_admin set xrowecommerce_classes type contentclass
php ezpm.php -s ezwebin_site_admin set xrowecommerce_classes 'vendor' 'xrow GmbH'
php ezpm.php -s ezwebin_site_admin set xrowecommerce_classes 'state' 'stable'
php ezpm.php -s ezwebin_site_admin create xrowecommerce_content "xrowecommerce content" 1.1-0 install
php ezpm.php -s ezwebin_site_admin set xrowecommerce_content type contentobject
php ezpm.php -s ezwebin_site_admin set xrowecommerce_content 'vendor' 'xrow GmbH'
php ezpm.php -s ezwebin_site_admin set xrowecommerce_content 'state' 'stable'
php ezpm.php -s ezwebin_site_admin add xrowecommerce_content ezcontentobject --exclude-classes --exclude-templates --current-version "democontent/*" "manufacturer/*" "shop2" "news/*" "beer/*" "coupons/*" "packages/*" "products/*"

