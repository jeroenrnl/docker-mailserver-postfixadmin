<?php
/** 
 * Postfix Admin 
 * 
 * LICENSE 
 * This source file is subject to the GPL license that is bundled with  
 * this package in the file LICENSE.TXT. 
 * 
 * Further details on the project are available at http://postfixadmin.sf.net 
 * 
 * @version $Id$ 
 * @license GNU GPL v2 or later. 
 * 
 * File: config.inc.php
 * Contains configuration options.
 */


################################################################################
#                                                                              #
#    PostfixAdmin default configuration                                        #
#                                                                              #
#    This file contains the PostfixAdmin default configuration settings.       #
#                                                                              #
#    Please do not edit this file.                                             #
#                                                                              #
#    Instead, add the options you want to change/override to                   #
#    config.local.php (if it doesn't exist, create it).                        #
#    This will make version upgrades much easier.                              #
#                                                                              #
################################################################################



/*****************************************************************
 *  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! 
 * You have to set $CONF['configured'] = true; before the
 * application will run!
 * Doing this implies you have changed this file as required.
 * i.e. configuring database etc; specifying setup.php password etc.
 */
$CONF['configured'] = true;

$PFA_VARS = array('PFA_SETUP_PASS', 'PFA_DB_USER', 'PFA_DB_HOST', 'PFA_DB_PASS', 'PFA_DB_NAME', 'PFA_POSTFIX_SERVER', 'PFA_ABUSE_EMAIL', 'PFA_HOSTMASTER_EMAIL', 'PFA_POSTMASTER_EMAIL', 'PFA_WEBMASTER_EMAIL');
$PFA_ERROR = false;
foreach ($PFA_VARS AS $PFA_VAR) {
	if (!isset($_ENV[$PFA_VAR])) {
		$PFA_ERROR = true;
		break;
	}
}
if ($PFA_ERROR) {
	echo '<h1>Configuration Error</h1>You must set environment these variables:<ul>';
	foreach ($PFA_VARS AS $PFA_VAR) {
		echo '<li>'.$PFA_VAR.': '.(isset($_ENV[$PFA_VAR]) ? '<span style="color: green; font-weight: bold;">configured</span>' : '<span style="color: red; font-weight: bold;">missing</span>').'</li>';
	}
	echo '</ul>';
	exit(1);
}

// In order to setup Postfixadmin, you MUST specify a hashed password here.
// To create the hash, visit setup.php in a browser and type a password into the field,
// on submission it will be echoed out to you as a hashed value.
$PFA_SALT = md5(time() . '*' . $_SERVER['REMOTE_ADDR'] . '*' . mt_rand(0, 60000));
# SHA1 encoding is obsolete since 3.3
#$CONF['setup_password'] = $PFA_SALT.':'.sha1($PFA_SALT.':'.($_ENV['PFA_SETUP_PASS'] ? $_ENV['PFA_SETUP_PASS'] : $_ENV['PFA_DB_PASS']));
# New password hashing since 3.3
$CONF['setup_password'] = password_hash(($_ENV['PFA_SETUP_PASS'] ? $_ENV['PFA_SETUP_PASS'] : $_ENV['PFA_DB_PASS']), PASSWORD_DEFAULT);

// Language config
// Language files are located in './languages', change as required..
$CONF['default_language'] = 'en';

// Hook to override or add translations in $PALANG
// Set to the function name you want to use as hook function (see language_hook example function below)
$CONF['language_hook'] = '';

/*
    language_hook example function
 
    Called if $CONF['language_hook'] == '<name_of_the_function>'
    Allows to add or override $PALANG interface texts.

    If you add new texts, please always prefix them with 'x_' (for example 
    $PALANG['x_mytext'] = 'foo') to avoid they clash with texts that might be
    added to languages/*.lang in future versions of PostfixAdmin.

    Please also make sure that all your added texts are included in all
    sections - that includes all 'case "XY":' sections and the 'default:'
    section (for users that don't have any of the languages specified
    in the 'case "XY":' section). 
    Usually the 'default:' section should contain english text.

    If you modify an existing text/translation, please consider to report it
    to the bugtracker on http://sf.net/projects/postfixadmin so that all users
    can benefit from the corrected text/translation.

    Returns: modified $PALANG array
*/
/*
function language_hook($PALANG, $language) {
    switch ($language) {
        case "de":
            $PALANG['x_whatever'] = 'foo';
            break;
        case "fr":
            $PALANG['x_whatever'] = 'bar';
            break;
        default:
            $PALANG['x_whatever'] = 'foobar';
    }

    return $PALANG;
}
*/

// Database Config
// mysql = MySQL 3.23 and 4.0, 4.1 or 5
// mysqli = MySQL 4.1+ or MariaDB
// pgsql = PostgreSQL
// sqlite = SQLite 3
$CONF['database_type'] = 'mysqli';
$CONF['database_host'] = $_ENV['PFA_DB_HOST'];
$CONF['database_user'] = $_ENV['PFA_DB_USER'];
$CONF['database_password'] = $_ENV['PFA_DB_PASS'];
$CONF['database_name'] = $_ENV['PFA_DB_NAME'];

// Database SSL Config
$CONF['database_use_ssl'] = false;
$CONF['database_ssl_key'] = NULL;
$CONF['database_ssl_cert'] = NULL;
$CONF['database_ssl_ca'] = NULL;
$CONF['database_ssl_ca_path'] = NULL;
$CONF['database_ssl_cipher'] = NULL;

// If you need to specify a different port for a MYSQL database connection, use e.g.
//   $CONF['database_host'] = '172.30.33.66:3308';
//
// If you need to specify a different port for MySQLi(3306)/POSTGRESQL(5432) database connection
//   uncomment and change the following
// $CONF['database_port'] = '5432';
//
// If you wish to connect using a local socket file (e.g /var/run/mysql.sock) uncomment the below.
// $CONF['database_socket'] = '/var/run/mysql/mysqld.sock';
//
// If sqlite is used, specify the database file path:
//   $CONF['database_name'] = '/etc/postfix/sqlite/postfixadmin.db'

// Here, if you need, you can customize table names.
$CONF['database_prefix'] = '';
$CONF['database_tables'] = array (
    'admin' => 'admin',
    'alias' => 'alias',
    'alias_domain' => 'alias_domain',
    'config' => 'config',
    'domain' => 'domain',
    'domain_admins' => 'domain_admins',
    'fetchmail' => 'fetchmail',
    'log' => 'log',
    'mailbox' => 'mailbox',
    'vacation' => 'vacation',
    'vacation_notification' => 'vacation_notification',
    'quota' => 'quota',
	'quota2' => 'quota2',
);

// Site Admin
// Define the Site Admin's email address below.
// This will be used to send emails from to create mailboxes and
// from Send Email / Broadcast message pages.
// Leave blank to send email from the logged-in Admin's Email address.
$CONF['admin_email'] = '';

// Site admin name
// This will be used as signature in notification messages
$CONF['admin_name'] = 'Postmaster';

// Mail Server
// Hostname (FQDN) of your mail server.
// This is used to send email to Postfix in order to create mailboxes.
$CONF['smtp_server'] = $_ENV['PFA_POSTFIX_SERVER'];
$CONF['smtp_port'] = isset($_ENV['PFA_POSTFIX_PORT']) ? $_ENV['PFA_POSTFIX_PORT'] : 25;

// SMTP Client
// Hostname (FQDN) of the server hosting Postfix Admin
// Used in the HELO when sending emails from Postfix Admin
$CONF['smtp_client'] = $_ENV['HOSTNAME'].'.localdomain';

// Encrypt
// In what way do you want the passwords to be crypted?
// md5crypt = internal postfix admin md5
// md5 = md5 sum of the password
// system = whatever you have set as your PHP system default
// cleartext = clear text passwords (ouch!)
// mysql_encrypt = useful for PAM integration
// authlib = support for courier-authlib style passwords - also set $CONF['authlib_default_flavor']
// dovecot:CRYPT-METHOD = use dovecotpw -s 'CRYPT-METHOD'. Example: dovecot:CRAM-MD5
//     IMPORTANT:
//     - don't use dovecot:* methods that include the username in the hash - you won't be able to login to PostfixAdmin in this case
//     - you'll need at least dovecot 2.1 for salted passwords ('doveadm pw' 2.0.x doesn't support the '-t' option)
//     - dovecot 2.0.0 - 2.0.7 is not supported
$CONF['encrypt'] = 'md5crypt';

// In what flavor should courier-authlib style passwords be encrypted?
// (only used if $CONF['encrypt'] == 'authlib')
// md5 = {md5} + base64 encoded md5 hash
// md5raw = {md5raw} + plain encoded md5 hash
// SHA = {SHA} + base64-encoded sha1 hash
// crypt = {crypt} + Standard UNIX DES-encrypted with 2-character salt
$CONF['authlib_default_flavor'] = 'md5raw';

// If you use the dovecot encryption method: where is the dovecotpw binary located?
// for dovecot 1.x
// $CONF['dovecotpw'] = "/usr/sbin/dovecotpw";
// for dovecot 2.x (dovecot 2.0.0 - 2.0.7 is not supported!)
$CONF['dovecotpw'] = "/usr/sbin/doveadm pw";
if(@file_exists('/usr/bin/doveadm')) { // @ to silence openbase_dir stuff; see https://github.com/postfixadmin/postfixadmin/issues/171
    $CONF['dovecotpw'] = "/usr/bin/doveadm pw"; # debian
}

// Password validation
// New/changed passwords will be validated using all regular expressions in the array.
// If a password doesn't match one of the regular expressions, the corresponding
// error message from $PALANG (see languages/*) will be displayed.
// See http://de3.php.net/manual/en/reference.pcre.pattern.syntax.php for details
// about the regular expression syntax.
// If you need custom error messages, you can add them using $CONF['language_hook'].
// If a $PALANG text contains a %s, you can add its value after the $PALANG key
// (separated with a space).
$CONF['password_validation'] = array(
#    '/regular expression/' => '$PALANG key (optional: + parameter)',
    '/.{5}/'                => 'password_too_short 5',      # minimum length 5 characters
    '/([a-zA-Z].*){3}/'     => 'password_no_characters 3',  # must contain at least 3 characters
    '/([0-9].*){2}/'        => 'password_no_digits 2',      # must contain at least 2 digits
);

// Generate Password
// Generate a random password for a mailbox or admin and display it.
// If you want to automagically generate passwords set this to 'YES'.
$CONF['generate_password'] = 'NO';

// Show Password
// Always show password after adding a mailbox or admin.
// If you want to always see what password was set set this to 'YES'.
$CONF['show_password'] = 'NO';

// Page Size
// Set the number of entries that you would like to see
// in one page.
$CONF['page_size'] = '10';

// Default Aliases
// The default aliases that need to be created for all domains.
// You can specify the target address in two ways:
// a) a full mail address
// b) only a localpart ('postmaster' => 'admin') - the alias target will point to the same domain
$CONF['default_aliases'] = array (
    'abuse'      => $_ENV['PFA_ABUSE_EMAIL'],
    'hostmaster' => $_ENV['PFA_HOSTMASTER_EMAIL'],
    'postmaster' => $_ENV['PFA_POSTMASTER_EMAIL'],
    'webmaster'  => $_ENV['PFA_WEBMASTER_EMAIL']
);

// Mailboxes
// If you want to store the mailboxes per domain set this to 'YES'.
// Examples:
//   YES: /usr/local/virtual/domain.tld/username@domain.tld
//   NO:  /usr/local/virtual/username@domain.tld
$CONF['domain_path'] = 'YES';
// If you don't want to have the domain in your mailbox set this to 'NO'.
// Examples: 
//   YES: /usr/local/virtual/domain.tld/username@domain.tld
//   NO:  /usr/local/virtual/domain.tld/username
// Note: If $CONF['domain_path'] is set to NO, this setting will be forced to YES.
$CONF['domain_in_mailbox'] = 'NO';
// If you want to define your own function to generate a maildir path set this to the name of the function.
// Notes: 
//   - this configuration directive will override both domain_path and domain_in_mailbox
//   - the maildir_name_hook() function example is present below, commented out
//   - if the function does not exist the program will default to the above domain_path and domain_in_mailbox settings
$CONF['maildir_name_hook'] = 'NO';

/*
    maildir_name_hook example function
 
    Called when creating a mailbox if $CONF['maildir_name_hook'] == '<name_of_the_function>'
    - allows for customized maildir paths determined by a custom function
    - the example below will prepend a single-character directory to the
      beginning of the maildir, splitting domains more or less evenly over
      36 directories for improved filesystem performance with large numbers
      of domains.

    Returns: maildir path
    ie. I/example.com/user/
*/
/*
function maildir_name_hook($domain, $user) {
    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    $dir_index = hexdec(substr(md5($domain), 28)) % strlen($chars);
    $dir = substr($chars, $dir_index, 1);
    return sprintf("%s/%s/%s/", $dir, $domain, $user);
}
*/

/*  
    *_struct_hook - change, add or remove fields

    If you need additional fields or want to change or remove existing fields,
    you can write a hook function to modify $struct in the *Handler classes. 

    The edit form will automatically be updated according to the modified
    $struct. The list page is not yet updated automatically.

    You can define one hook function per class, named like the primary database
    table of that class.
    The hook function is called with $struct as parameter and must return the
    modified $struct. 

    Note: Adding a field to $struct adds the handling of this field in
    PostfixAdmin, but it does not create it in the database. You have to do
    that yourself. 
    Please follow the naming policy for custom database fields and tables on
    https://sourceforge.net/p/postfixadmin/wiki/Custom_fields/
    to avoid clashes with future versions of PostfixAdmin.

    See initStruct() in the *Handler class for the default $struct.
    See pacol() in functions.inc.php for the available flags on each column.
    
    Example:

    function x_struct_admin_modify($struct) {
        $struct['superadmin']['editable'] = 0;          # make the 'superadmin' flag read-only
        $struct['superadmin']['display_in_form'] = 0;   # don't display the 'superadmin' flag in edit form
        $struct['x_newfield'] = pacol( [...] );        # additional field 'x_newfield'
        return $struct; # important!
    }
    $CONF['admin_struct_hook'] = 'x_struct_admin_modify';
*/
$CONF['admin_struct_hook']          = '';
$CONF['domain_struct_hook']         = '';
$CONF['alias_struct_hook']          = '';
$CONF['mailbox_struct_hook']        = '';
$CONF['alias_domain_struct_hook']   = '';
$CONF['fetchmail_struct_hook']      = '';


// Default Domain Values
// Specify your default values below. Quota in MB.
$CONF['aliases'] = '10';
$CONF['mailboxes'] = '10';
$CONF['maxquota'] = '10';
$CONF['domain_quota_default'] = '2048';

// Quota
// When you want to enforce quota for your mailbox users set this to 'YES'.
$CONF['quota'] = 'NO';
// If you want to enforce domain-level quotas set this to 'YES'.
$CONF['domain_quota'] = 'YES';
// You can either use '1024000' or '1048576'
$CONF['quota_multiplier'] = '1024000';

// Transport
// If you want to define additional transport options for a domain set this to 'YES'.
// Read the transport file of the Postfix documentation.
$CONF['transport'] = 'NO';
// Transport options
// If you want to define additional transport options put them in array below.
$CONF['transport_options'] = array (
    'virtual',  // for virtual accounts
    'local',    // for system accounts
    'relay'     // for backup mx
);
// Transport default
// You should define default transport. It must be in array above.
$CONF['transport_default'] = 'virtual';


//
//
// Virtual Vacation Stuff
//
//

// If you want to use virtual vacation for you mailbox users set this to 'YES'.
// NOTE: Make sure that you install the vacation module. (See VIRTUAL-VACATION/)
$CONF['vacation'] = 'NO';

// This is the autoreply domain that you will need to set in your Postfix
// transport maps to handle virtual vacations. It does not need to be a
// real domain (i.e. you don't need to setup DNS for it).
// This domain must exclusively be used for vacation. Do NOT use it for "normal" mail addresses.
$CONF['vacation_domain'] = 'autoreply.change-this-to-your.domain.tld';

// Vacation Control
// If you want users to take control of vacation set this to 'YES'.
$CONF['vacation_control'] ='YES';

// Vacation Control for admins
// Set to 'YES' if your domain admins should be able to edit user vacation.
$CONF['vacation_control_admin'] = 'YES';

// ReplyType options
// If you want to define additional reply options put them in array below.
// The array has the format   seconds between replies => $PALANG text
// Special values for seconds are: 
// 0 => only reply to the first mail while on vacation 
// 1 => reply on every mail
$CONF['vacation_choice_of_reply'] = array (
   0 => 'reply_once',        // Sends only Once the message during Out of Office
   # considered annoying - only send a reply on every mail if you really need it
   # 1 => 'reply_every_mail',       // Reply on every email
   60*60 *24*7 => 'reply_once_per_week'        // Reply if last autoreply was at least a week ago
);

//
// End Vacation Stuff.
//

// Alias Control
// Postfix Admin inserts an alias in the alias table for every mailbox it creates.
// The reason for this is that when you want catch-all and normal mailboxes
// to work you need to have the mailbox replicated in the alias table.
// If you want to take control of these aliases as well set this to 'YES'.

// If you don't want edit alias tab (user mode) set this to 'NO';
$CONF['edit_alias'] = 'YES';

// Alias control for superadmins
$CONF['alias_control'] = 'YES';

// Alias Control for domain admins
$CONF['alias_control_admin'] = 'YES';

// Special Alias Control
// Set to 'NO' if your domain admins shouldn't be able to edit the default aliases
// as defined in $CONF['default_aliases']
$CONF['special_alias_control'] = 'NO';

// Alias Goto Field Limit
// Set the max number of entries that you would like to see
// in one 'goto' field in overview, the rest will be hidden and "[and X more...]" will be added.
// '0' means no limits.
$CONF['alias_goto_limit'] = '0';

// Alias Domains
// Alias domains allow to "mirror" aliases and mailboxes to another domain. This makes 
// configuration easier if you need the same set of aliases on multiple domains, but
// also requires postfix to do more database queries.
// Note: If you update from 2.2.x or earlier, you will have to update your postfix configuration.
// Set to 'NO' to disable alias domains.
$CONF['alias_domain'] = 'YES';

// Backup
// If you don't want backup tab set this to 'NO';
$CONF['backup'] = 'NO';

// Send Mail
// If you don't want sendmail tab set this to 'NO';
$CONF['sendmail'] = 'YES';
// Set this to YES if you want to allow non-super-admins to
// send mails to their users
$CONF['sendmail_all_admins'] = 'NO';

// Logging
// If you don't want logging set this to 'NO';
$CONF['logging'] = 'YES';

// Fetchmail
// If you don't want fetchmail tab set this to 'NO';
$CONF['fetchmail'] = 'YES';

// fetchmail_extra_options allows users to specify any fetchmail options and any MDA
// (it will even accept 'rm -rf /' as MDA!)
// This should be set to NO, except if you *really* trust *all* your users.
$CONF['fetchmail_extra_options'] = 'NO';

// Header
$CONF['show_header_text'] = 'NO';
$CONF['header_text'] = ':: Postfix Admin ::';

// Footer
// Below information will be on all pages.
// If you don't want the footer information to appear set this to 'NO'.
$CONF['show_footer_text'] = 'YES';
$CONF['footer_text'] = 'Return to change-this-to-your.domain.tld';
$CONF['footer_link'] = 'http://change-this-to-your.domain.tld';

// MOTD ("Motto of the day")
// You can display a MOTD below the menu on all pages.
// This can be configured seperately for users, domain admins and superadmins
$CONF['motd_user'] = '';
$CONF['motd_admin'] = '';
$CONF['motd_superadmin'] = '';

// Welcome Message
// This message is send to every newly created mailbox.
// Change the text between EOM.
$CONF['welcome_text'] = <<<EOM
Hi,

Welcome to your new account.
EOM;

// When creating mailboxes or aliases, check that the domain-part of the
// address is legal by performing a name server look-up.
$CONF['emailcheck_resolve_domain']='YES';


// Optional:
// Analyze alias gotos and display a colored block in the first column
// indicating if an alias or mailbox appears to deliver to a non-existent
// account.  Also, display indications, for POP/IMAP mailboxes and
// for custom destinations (such as mailboxes that forward to a UNIX shell
// account or mail that is sent to a MS exchange server, or any other
// domain or subdomain you use)
// See http://www.w3schools.com/html/html_colornames.asp for a list of
// color names available on most browsers

//set to YES to enable this feature
$CONF['show_status']='YES';
//display a guide to what these colors mean
$CONF['show_status_key']='YES';
// 'show_status_text' will be displayed with the background colors
// associated with each status, you can customize it here
$CONF['show_status_text']='&nbsp;&nbsp;';
// show_undeliverable is useful if most accounts are delivered to this
// postfix system.  If many aliases and mailboxes are forwarded
// elsewhere, you will probably want to disable this.
$CONF['show_undeliverable']='YES';
$CONF['show_undeliverable_color']='tomato';
// mails to these domains will never be flagged as undeliverable
$CONF['show_undeliverable_exceptions']=array("unixmail.domain.ext","exchangeserver.domain.ext");
$CONF['show_popimap']='YES';
$CONF['show_popimap_color']='darkgrey';
// you can assign special colors to some domains. To do this,
// - add the domain to show_custom_domains
// - add the corresponding color to show_custom_colors
$CONF['show_custom_domains']=array("subdomain.domain.ext","domain2.ext");
$CONF['show_custom_colors']=array("lightgreen","lightblue");
// If you use a recipient_delimiter in your postfix config, you can also honor it when aliases are checked.
// Example: $CONF['recipient_delimiter'] = "+";
// Set to "" to disable this check.
$CONF['recipient_delimiter'] = "";

// Optional:
// Script to run after creation of mailboxes.
// Note that this may fail if PHP is run in "safe mode", or if
// operating system features (such as SELinux) or limitations
// prevent the web-server from executing external scripts.
// Parameters: (1) username (2) domain (3) maildir (4) quota
// $CONF['mailbox_postcreation_script']='sudo -u courier /usr/local/bin/postfixadmin-mailbox-postcreation.sh';
$CONF['mailbox_postcreation_script'] = '';

// Optional:
// Script to run after alteration of mailboxes.
// Note that this may fail if PHP is run in "safe mode", or if
// operating system features (such as SELinux) or limitations
// prevent the web-server from executing external scripts.
// Parameters: (1) username (2) domain (3) maildir (4) quota
// $CONF['mailbox_postedit_script']='sudo -u courier /usr/local/bin/postfixadmin-mailbox-postedit.sh';
$CONF['mailbox_postedit_script'] = '';

// Optional:
// Script to run after deletion of mailboxes.
// Note that this may fail if PHP is run in "safe mode", or if
// operating system features (such as SELinux) or limitations
// prevent the web-server from executing external scripts.
// Parameters: (1) username (2) domain
// $CONF['mailbox_postdeletion_script']='sudo -u courier /usr/local/bin/postfixadmin-mailbox-postdeletion.sh';
$CONF['mailbox_postdeletion_script'] = '';

// Optional:
// Script to run after creation of domains.
// Note that this may fail if PHP is run in "safe mode", or if
// operating system features (such as SELinux) or limitations
// prevent the web-server from executing external scripts.
// Parameters: (1) domain
//$CONF['domain_postcreation_script']='sudo -u courier /usr/local/bin/postfixadmin-domain-postcreation.sh';
$CONF['domain_postcreation_script'] = '';

// Optional:
// Script to run after deletion of domains.
// Note that this may fail if PHP is run in "safe mode", or if
// operating system features (such as SELinux) or limitations
// prevent the web-server from executing external scripts.
// Parameters: (1) domain
// $CONF['domain_postdeletion_script']='sudo -u courier /usr/local/bin/postfixadmin-domain-postdeletion.sh';
$CONF['domain_postdeletion_script'] = '';

// Optional:
// Sub-folders which should automatically be created for new users.
// The sub-folders will also be subscribed to automatically.
// Will only work with IMAP server which implement sub-folders.
// Will not work with POP3.
// If you define create_mailbox_subdirs, then the
// create_mailbox_subdirs_host must also be defined.
//
// $CONF['create_mailbox_subdirs']=array('Spam');
$CONF['create_mailbox_subdirs'] = array();
$CONF['create_mailbox_subdirs_host']='localhost';
//
// Specify '' for Dovecot and 'INBOX.' for Courier.
$CONF['create_mailbox_subdirs_prefix']='INBOX.';

// Optional:
// Show used quotas from Dovecot dictionary backend in virtual
// mailbox listing.
// See: DOCUMENTATION/DOVECOT.txt
//      http://wiki.dovecot.org/Quota/Dict
//
$CONF['used_quotas'] = 'NO';

// if you use dovecot >= 1.2, set this to yes.
// Note about dovecot config: table "quota" is for 1.0 & 1.1, table "quota2" is for dovecot 1.2 and newer
$CONF['new_quota_table'] = 'YES';

//
// Normally, the TCP port number does not have to be specified.
// $CONF['create_mailbox_subdirs_hostport']=143;
//
// If you have trouble connecting to the IMAP-server, then specify
// a value for $CONF['create_mailbox_subdirs_hostoptions']. These
// are some examples to experiment with:
// $CONF['create_mailbox_subdirs_hostoptions']=array('notls');
// $CONF['create_mailbox_subdirs_hostoptions']=array('novalidate-cert','norsh');
// See also the "Optional flags for names" table at
// http://www.php.net/manual/en/function.imap-open.php
$CONF['create_mailbox_subdirs_hostoptions'] = array();

// Optional:
// Allows a user to reset his forgotten password with a code sent by email/SMS
$CONF['forgotten_user_password_reset'] = true;
// Allows an admin to reset his forgotten password with a code sent by email/SMS
$CONF['forgotten_admin_password_reset'] = false;

// Name of the function to send a SMS
// Please use a name that begins with "x_" to prevent collisions
// This function must accept 2 parameters: phone number and message,
// and return true on success or false on failure
// Note: if no sms_send_function is defined, the input field for the mobile
// number won't be displayed
$CONF['sms_send_function'] = '';

/*
// Example of send SMS function using Clickatell HTTP API
function x_send_sms_clickatell($to, $message) {

    $clickatell_api_id = 'CHANGEME';
    $clickatell_user = 'CHANGEME';
    $clickatell_password = 'CHANGEME';
    $clickatell_sender = 'CHANGEME';

    $url = 'https://api.clickatell.com/http/sendmsg?api_id=%s&user=%s&password=%s&to=%s&from=%s&text=%s';

    $url = sprintf($url, $clickatell_api_id, $clickatell_user, $clickatell_password, $to, $clickatell_sender, urlencode($message));

    $result = file_get_contents($url);

    return $result !== false;
}
*/

// Theme Config
// Specify your own logo and CSS file
$CONF['theme_logo'] = 'images/logo-default.png';
$CONF['theme_css'] = 'css/default.css';
// If you want to customize some styles without editing the $CONF['theme_css'] file,
// you can add a custom CSS file. It will be included after $CONF['theme_css'].
$CONF['theme_custom_css'] = '';

// XMLRPC Interface.
// This should be only of use if you wish to use e.g the
// Postfixadmin-Squirrelmail package
//  change to boolean true to enable xmlrpc
$CONF['xmlrpc_enabled'] = false;

// If you want to keep most settings at default values and/or want to ensure 
// that future updates work without problems, you can use a separate config 
// file (config.local.php) instead of editing this file and override some
// settings there.
if (file_exists(dirname(__FILE__) . '/config.local.php')) {
    require_once(dirname(__FILE__) . '/config.local.php');
}

//
// END OF CONFIG FILE
//
/* vim: set expandtab softtabstop=4 tabstop=4 shiftwidth=4: */
