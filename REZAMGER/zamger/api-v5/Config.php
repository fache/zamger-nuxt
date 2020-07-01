<?php

// Modul: core
// Klasa: Config
// Opis: ovdje ćemo za sada nabacati konfiguraciju svega i svačega... popraviti!

// Ovaj fajl treba biti iskopiran u sve frontende uz moguće neke modifikacije

// Osnovna ideja je sljedeća: ko god bude require-ovao ostale klase "zna" gdje se nalazi klasa Config
// i već je odradio require ovog fajla. Na taj način se rješava koka-jaje problem sa varijablom
// $backend_path

class Config {
	// Standardne stvari
	public static $debug = true;

	// Konfiguracija backenda
	public static $backend_path = "/opt/lampp/htdocs/rezamger/zamger/backend-v5/";
	public static $backend_file_path = "/opt/lampp/htdocs/rezamger/zamger/backend-v5/";

	// Konfiguracija frontenda
	public static $frontend_path = "/opt/lampp/htdocs/rezamger/zamger/frontend-v5/";
	public static $frontend_url = "/opt/lampp/htdocs/rezamger/zamger/frontend-v5/";

	// ---------- Konfiguracija baze podataka
	public static $dbhost = "localhost";
	public static $dbuser = "root";
	public static $dbpass = "";
	public static $dbdb = "zamger";

	public static $use_mysql_utf8 = true;
	
	public static $database_debug = true;

	// Konfiguracija RSS frontenda
	public static $rss_url = "/opt/lampp/htdocs/rezamger/zamger/frontend-v5/";
	
	// ---------- Autentikacijski parametri (klasa Session)
	
	// Gdje su smještene šifre korisnika?
	// "table" - u tabeli auth zamgerove baze podataka
	// "ldap" - na LDAP serveru; ako izaberete ovu opciju, promjena šifre je onemogućena
	public static $system_auth = "table";
	
	// Pristupni podaci za LDAP
	// Zamger će koristiti anonimni pristup
	public static $ldap_server = "";
	// string koji se dodaje na uid da bi se dobila email adresa
	// Vidjeti funkciju gen_ldap_uid() u klasi Session
	public static $ldap_domain = "";
	public static $ldap_dn = "";

	
	// ---------- CAS podrška

	public static $cas_server = ""; // hostname CAS servera, ne možete koristiti localhost (mora biti FQDN)
	public static $cas_port = 443; // CAS uvijek koristi HTTPS
	public static $cas_context = "cas"; // dio url-a iza hostname
	
	
	// ----------- API konfiguracija
	public static $api_url = "/opt/lampp/htdocs/rezamger/zamger/api-v5/";
	
	// Za PHP<5.4.0 koristiti konfiguraciju ispod
	//public static $conf_json_options = 0;
	public static $json_options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;

	public static $api_allowed_uris = array( '*' );
	
}

?>