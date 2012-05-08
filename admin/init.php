<?php

define('IN_ADMIN_PANEL', TRUE);

include '../init.php';


define('ON_LOGIN_OK',       1);
define('ON_LOGIN_FAIL',     2);
define('ON_LOGIN_EXPIRED',  3);

if (!defined('LOGIN_START')) {
  include 'auth.php';
}


$_top_menu = array(
                   array(
                         array('index.php', 'dashborad',_('Dashboard')),
                         ),
                   array(
                         array('list_products.php', 'products', _('Products')),
                         array('product.php','prdadd',_('New Product')),
                         array('list_products.php','prdlst',_('List Products')),
                         array('product_search.php','replace',_('Product Search')),
                         ),
                   array(
                         array('list_categories.php','cats',_('Categories')),
                         array('category.php','catadd',_('New Category')),
                         array('list_categories.php','catlst',_('List Categories')),
                         ),
                   array(
                         array('news.php','cms', _('Content')),
                         array('news.php','nwsadd', _('New News')),
                         array('list_news.php','nwslst', _('List News')),
                         array('banner.php','banneradd', _('New Banner')),
                         array('list_banner.php','bannerlst', _('List Banner')),
                         array('faq.php','faqadd', _('New Faq')),
                         array('list_faqs.php','faqlst', _('List Faq')),
                         array('newsletter.php','newsletteradd', _('New E-Bulletin')),
                         array('newsletter_send.php','newslettersend', _('Send E-Bullettin')),
                         array('upload_file.php','uploadfile', _('Upload Document')),
                         ),
                   array(array('list_payments.php', 'payments', _('Orders')),
                         array('list_payments.php' ,'listpayments', _('New Orders')),
                         array('list_payments.php?type=' . ON_ORDER_COMPLETED ,'listpayments', _('Order Completed')),
                         array('list_payments.php?type=' . ON_ORDER_CANCELLED ,'listpayments', _('Order Cancelled')),
                         ),
                   array(array('stat.php','report', _('Reports')),
                         array('stat.php?type='.ON_STAT_PRODUCT, 'report_pro',  _('Product Reports')),
                         array('stat.php?type='.ON_STAT_CATEGORY, 'report_bra', _('Category Reports')),
                         array('error_logs.php', 'report_err', _('Error Reports')),
                         ),
                   array(array('list_users.php','user', _('Users')),
                         array('user.php','useradd',_('New User')),
                         array('list_users.php','userlst',_('List Users')),
                         ),
                   array(array('settings-store.php','settings',_('Settings')),
                         array('settings-store.php', '', _('Store Information')),
                         array('list_payment_gateways.php', '', _('Payment Gateway')),
                         array('list_payment_accounts.php', '', _('Payment Accounts')),
                         array('myinfo.php','', _('My Information')),
                         ),
                   array(array('logout.php','logout',_('Logout'))),
                   );



$show['rightcolumn']=true;

start_timing();

?>
