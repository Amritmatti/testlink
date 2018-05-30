<?php
// MISC
$tlCfg->default_language = 'pt_BR';
$tlCfg->config_check_warning_mode = 'SILENT';

// EMAIL
$g_smtp_host = $_SERVER['TL_SMTP_HOST'];
$g_smtp_username = $_SERVER['TL_SMTP_USER'];
$g_smtp_password = $_SERVER['TL_SMTP_PASSWORD'];
$g_smtp_port = $_SERVER['TL_SMTP_PORT'];
$g_smtp_connection_mode = $_SERVER['TL_SMTP_CONN_MODE']; // Can be '', 'ssl','tls'
$g_tl_admin_email = $_SERVER['TL_ADMIN_EMAIL']; // for problem/error notification
$g_from_email = $g_tl_admin_email;  // email sender
$g_return_path_email = $g_tl_admin_email;
$g_SMTPAutoTLS = false;

// LDAP
$tlCfg->authentication['method'] = 'LDAP';
$tlCfg->authentication['ldap'] = array();
$tlCfg->authentication['ldap_automatic_user_creation'] = true;
$tlCfg->authentication['ldap'][1]['ldap_server'] = $_SERVER['TL_LDAP_SERVER'];
$tlCfg->authentication['ldap'][1]['ldap_port'] = intval($_SERVER['TL_LDAP_PORT']);
$tlCfg->authentication['ldap'][1]['ldap_version'] = '3'; // Can be '2'
$tlCfg->authentication['ldap'][1]['ldap_root_dn'] = $_SERVER['TL_LDAP_ROOT_DN'];
$tlCfg->authentication['ldap'][1]['ldap_bind_dn'] = $_SERVER['TL_LDAP_BIND_DN'];
$tlCfg->authentication['ldap'][1]['ldap_bind_passwd'] = $_SERVER['TL_LDAP_BIND_PASSWORD'];
$tlCfg->authentication['ldap'][1]['ldap_tls'] = $_SERVER['TL_LDAP_USE_TLS'] === 'false' ? false : true;
$tlCfg->authentication['ldap'][1]['ldap_organization'] = '';
$tlCfg->authentication['ldap'][1]['ldap_uid_field'] = $_SERVER['TL_LDAP_UID_FIELD'];
$tlCfg->authentication['ldap'][1]['ldap_email_field'] = $_SERVER['TL_LDAP_EMAIL_FIELD'];
$tlCfg->authentication['ldap'][1]['ldap_firstname_field'] = $_SERVER['TL_LDAP_FIRSTNAME_FIELD'];
$tlCfg->authentication['ldap'][1]['ldap_surname_field'] = $_SERVER['TL_LDAP_SURNAME_FIELD'];
