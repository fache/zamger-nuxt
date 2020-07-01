<?

// NASTAVNIK/KVIZOVI - kreiranje i administracija kvizova


function nastavnik_kvizovi() {

global $userid,$user_siteadmin;
global $_lv_;



// Parametri
$predmet = intval($_REQUEST['predmet']);
$ag = intval($_REQUEST['ag']);

// Naziv predmeta
$q5 = myquery("select naziv from predmet where id=$predmet");
if (mysql_num_rows($q5)<1) {
	biguglyerror("Nepoznat predmet");
	zamgerlog("ilegalan predmet $predmet",3); //nivo 3: greska
	return;
}
$predmet_naziv = mysql_result($q5,0,0);

// Da li korisnik ima pravo ući u modul?

if (!$user_siteadmin) {
	$q10 = myquery("select nivo_pristupa from nastavnik_predmet where nastavnik=$userid and predmet=$predmet and akademska_godina=$ag");
	if (mysql_num_rows($q10)<1 || mysql_result($q10,0,0)=="asistent") {
		zamgerlog("nastavnik/ispiti privilegije (predmet pp$predmet)",3);
		biguglyerror("Nemate pravo pristupa ovoj opciji");
		return;
	} 
}



?>

<p>&nbsp;</p>

<p><h3><?=$predmet_naziv?> - Kvizovi</h3></p>

<p>Napomena: Ovaj modul je još uvijek u fazi razvoja i nije dovoljno testiran. Ne preporučujemo njegovo korištenje.</p>

<?



// Akcija - editovanje pitanja

if ($_REQUEST['akcija'] == "pitanja") {

	$kviz = intval($_REQUEST['kviz']);
	$q200 = myquery("select naziv, predmet, akademska_godina from kviz where id=$kviz");
	if (mysql_num_rows($q200)<1) {
		niceerror("Nepostojeći kviz $kviz");
		zamgerlog("editovanje pitanja: nepostojeci kviz $kviz", 3);
		return;
	}
	if ((mysql_result($q200,0,1) != $predmet) || (mysql_result($q200,0,2) != $ag)) {
		niceerror("Kviz nije sa ovog predmeta");
		zamgerlog("editovanje pitanja: kviz $kviz nije sa predmeta pp$predmet ag$ag", 3);
		return;
	}
	$naziv_kviza = mysql_result($q200, 0, 0);

	// Subakcije
	if ($_REQUEST['subakcija'] == "potvrda_novo" && check_csrf_token()) {
		$tekst = my_escape($_REQUEST['tekst']);
		$bodova = floatval(str_replace(',', '.', $_REQUEST['bodova']));
		if ($_REQUEST['vidljivo']) $vidljivo=1; else $vidljivo=0;
		$tip = my_escape($_REQUEST['tip']);

		$q300 = myquery("insert into kviz_pitanje set kviz=$kviz, tip='$tip', tekst='$tekst', bodova=$bodova, vidljivo=$vidljivo");
		$q310 = myquery("select id from kviz_pitanje where kviz=$kviz and tekst='$tekst' and bodova=$bodova and vidljivo=$vidljivo");
		if (mysql_num_rows($q310)<1) {
			niceerror("Problem prilikom dodavanja novog pitanja");
			print "Pitanje je vjerovatno uspješno dodano, ali ako ste unijeli i neke odgovore oni nisu pridruženi odgovarajućem pitanju. U svakom slučaju ovo se ne bi smjelo desiti. Kontaktirajte administratora.";
			zamgerlog("nije pronadjeno pitanje koje je navodno dodano", 3);
			return;
		}
		
		$pitanje = mysql_result($q310,0,0);

		// Ako je korisnik unosio odgovore prije kreiranja pitanja, njihov id pitanja je 0
		$q315 = myquery("update kviz_odgovor set kviz_pitanje=$pitanje where kviz_pitanje=0");

		nicemessage("Pitanje uspješno dodano");
		?>
		<script language="JavaScript">
		location.href='?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=izmijeni&pitanje=<?=$pitanje?>';
		</script>
		<?
		return;
	}

	if ($_REQUEST['subakcija'] == "potvrda_izmjene" && check_csrf_token()) {
		$pitanje = intval($_REQUEST['pitanje']);
		$tekst = my_escape($_REQUEST['tekst']);
		$bodova = floatval(str_replace(',', '.', $_REQUEST['bodova']));
		if ($_REQUEST['vidljivo']) $vidljivo=1; else $vidljivo=0;
		$tip = my_escape($_REQUEST['tip']);

		$q320 = myquery("select kviz from kviz_pitanje where id=$pitanje");
		if (mysql_num_rows($q320)==0) {
			niceerror("Pitanje je obrisano!");
			zamgerlog("potvrda editovanja pitanja: pitanje $pitanje ne postoji", 3);
			return;
		}
		if (mysql_result($q320,0,0) != $kviz) {
			niceerror("Pitanje nije sa ovog kviza");
			zamgerlog("potvrda editovanja pitanja: pitanje $pitanje nije sa kviza $kviz (pp$predmet ag$ag)", 3);
			return;
		}

		$q330 = myquery("update kviz_pitanje set tekst='$tekst', tip='$tip', bodova=$bodova, vidljivo=$vidljivo where id=$pitanje");

		nicemessage("Pitanje uspješno izmijenjeno");
		?>
		<script language="JavaScript">
		location.href='?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=izmijeni&pitanje=<?=$pitanje?>';
		</script>
		<?
		return;
	}
	
	if ($_REQUEST['subakcija'] == "obrisi") { // brisanje pitanja - ovdje ce nam trebati potvrda!
		$pitanje = intval($_REQUEST['pitanje']);
		$q320 = myquery("select kviz from kviz_pitanje where id=$pitanje");
		if (mysql_num_rows($q320)==0) {
			niceerror("Pitanje je već obrisano!");
			zamgerlog("potvrda brisanja pitanja: pitanje $pitanje ne postoji", 3);
			return;
		}
		if (mysql_result($q320,0,0) != $kviz) {
			niceerror("Pitanje nije sa ovog kviza");
			zamgerlog("potvrda brisanja pitanja: pitanje $pitanje nije sa kviza $kviz (pp$predmet ag$ag)", 3);
			return;
		}
		
		$q335 = myquery("delete from kviz_odgovor where kviz_pitanje=$pitanje");
		$q336 = myquery("delete from kviz_pitanje where id=$pitanje");

		nicemessage("Pitanje uspješno obrisano");
		?>
		<script language="JavaScript">
		location.href='?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja';
		</script>
		<?
		return;
	}

	if ($_REQUEST['subakcija'] == "dodaj_odgovor" && check_csrf_token()) {
		$pitanje = intval($_REQUEST['pitanje']);
		$tekst = my_escape($_REQUEST['tekst']);
		if ($_REQUEST['tacan']) $tacan=1; else $tacan=0;

		if ($pitanje>0) {
			$q320 = myquery("select kviz from kviz_pitanje where id=$pitanje");
			if (mysql_num_rows($q320)==0 || mysql_result($q320,0,0) != $kviz) {
				niceerror("Pitanje nije sa ovog kviza");
				zamgerlog("dodavanje odgovora: pitanje $pitanje nije sa kviza $kviz (pp$predmet ag$ag)", 3);
				return;
			}
		}

		$q340 = myquery("insert into kviz_odgovor set kviz_pitanje=$pitanje, tekst='$tekst', tacan=$tacan");

		nicemessage("Odgovor uspješno dodan");
		if ($pitanje>0) {
			?>
			<script language="JavaScript">
			location.href='?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=izmijeni&pitanje=<?=$pitanje?>';
			</script>
			<?
		}
		else {
			?>
			<script language="JavaScript">
			location.href='?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja';
			</script>
			<?
		}
		return;
	}

	if ($_REQUEST['subakcija'] == "obrisi_odgovor") { // && check_csrf_token()) {
		$odgovor = intval($_REQUEST['odgovor']);
		$q350 = myquery("select kp.kviz, kp.id from kviz_pitanje as kp, kviz_odgovor as ko where ko.id=$odgovor and ko.kviz_pitanje=kp.id");
		if (mysql_num_rows($q350)==0) {
			// Moguće da je odgovor dat prije pitanja
			$q355 = myquery("select kviz_pitanje from kviz_odgovor where id=$odgovor");
			if (mysql_num_rows($q355)==0) {
				niceerror("Odgovor je već obrisan!");
				zamgerlog("brisanje odgovora: odgovor $odgovor ne postoji", 3);
				return;
			} 
		}
		else if (mysql_result($q350,0,0) != $kviz) {
			niceerror("Odgovor ne postoji ili pitanje nije sa ovog kviza");
			zamgerlog("brisanje odgovora: odgovor $odgovor nije sa kviza $kviz (pp$predmet ag$ag)", 3);
			return;
		}

		$q360 = myquery("delete from kviz_odgovor where id=$odgovor");

		nicemessage("Odgovor uspješno obrisan");
		$dodaj = "";
		if (mysql_num_rows($q350)!=0) { $dodaj = "&subakcija=izmijeni&pitanje=".mysql_result($q350,0,1); }
		
		?>
		<script language="JavaScript">
		location.href='?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja<?=$dodaj?>';
		</script>
		<?
		return;
	}
	
	if ($_REQUEST['subakcija'] == "toggle_tacnost") { // && check_csrf_token()) {
		$odgovor = intval($_REQUEST['odgovor']);
		$q370 = myquery("select kp.kviz, kp.id, ko.tacan from kviz_pitanje as kp, kviz_odgovor as ko where ko.id=$odgovor and ko.kviz_pitanje=kp.id");
		if (mysql_num_rows($q370)==0 || mysql_result($q370,0,0) != $kviz) {
			niceerror("Odgovor ne postoji ili pitanje nije sa ovog kviza");
			zamgerlog("brisanje odgovora: odgovor $odgovor nije sa kviza $kviz (pp$predmet ag$ag)", 3);
			return;
		}

		if (mysql_result($q370,0,2) == 1) $tacan=0; else $tacan=1;
		$q380 = myquery("update kviz_odgovor set tacan=$tacan where id=$odgovor");

		nicemessage("Odgovor proglašen za (ne)tačan");
		?>
		<script language="JavaScript">
		location.href='?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=izmijeni&pitanje=<?=mysql_result($q370,0,1)?>';
		</script>
		<?
		return;
	}

	?>
	<h3>Izmjena pitanja za kviz "<?=$naziv_kviza?>"</h3>
	<a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&_lv_nav_id=<?=$kviz?>">Nazad na podešavanje parametara kviza</a><br><br>
	<table border="0" cellspacing="1" cellpadding="2">
	<tr bgcolor="#999999">
		<td><font style="font-family:DejaVu Sans,Verdana,Arial,sans-serif;font-size:11px;color:white;">R.br.</font></td>
		<td><font style="font-family:DejaVu Sans,Verdana,Arial,sans-serif;font-size:11px;color:white;">Tekst pitanja</font></td>
		<td><font style="font-family:DejaVu Sans,Verdana,Arial,sans-serif;font-size:11px;color:white;">Odgovori</font></td>
		<td><font style="font-family:DejaVu Sans,Verdana,Arial,sans-serif;font-size:11px;color:white;">Bodova</font></td>
		<td><font style="font-family:DejaVu Sans,Verdana,Arial,sans-serif;font-size:11px;color:white;">Vidljivo?</font></td>
		<td>&nbsp;</td>
	</tr>
	<?

	$rbr=0;
	$q210 = myquery("select id, tip, tekst, bodova, vidljivo from kviz_pitanje where kviz=$kviz");
	while ($r210 = mysql_fetch_row($q210)) {
		// Pribavljamo odgovore
		$odgovori = "";
		$q220 = myquery("select tekst, tacan from kviz_odgovor where kviz_pitanje=$r210[0] order by tacan desc");
		if (mysql_num_rows($q220)<1)
			$odgovori = "<font color=\"red\">Nema ponuđenih odgovora</font>";
		$broj_tacnih = 0;
		while ($r220 = mysql_fetch_row($q220)) {
			$odgovori .= "'$r220[0]'";
			if ($r220[1]==1) { $odgovori .= " (*)"; $broj_tacnih++; }
			$odgovori .= ", ";
		}
		if (mysql_num_rows($q220)>0 && $broj_tacnih==0) {
			$odgovori = "<font color=\"red\">Nije ponuđen tačan odgovor</font><br>\n".$odgovori;
		}
		else if (mysql_num_rows($q220)>0 && $r210[1]=='mcma' && $broj_tacnih==1) {
			$odgovori = "<font color=\"red\">Ponuđen je samo jedan tačan odgovor</font><br>\n".$odgovori;
		}

		$vidljivo="NE";
		if ($r210[4]==1) $vidljivo="DA";

		$rbr++;
		?>
		<tr>
			<td><?=$rbr?></td>
			<td><?=$r210[2]?></td>
			<td><?=$odgovori?></td>
			<td><?=$r210[3]?></td>
			<td><?=$vidljivo?></td>
			<td><a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=obrisi&pitanje=<?=$r210[0]?>">Obriši</a> *
			<a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=izmijeni&pitanje=<?=$r210[0]?>">Izmijeni</a></td>
		</tr>
		<?
	}

	print "</table>\n<br><br>\n";

	if ($_REQUEST['subakcija']=="izmijeni") {
		?>
		<a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja">Dodaj novo pitanje</a><br><br>
		<?
		print "<b>Izmjena pitanja</b><br>\n";
		$pitanje = intval($_REQUEST['pitanje']);
		$q230 = myquery("select kviz, tip, tekst, bodova, vidljivo from kviz_pitanje where id=$pitanje");
		if (mysql_num_rows($q230)<1) {
			niceerror("Nepostojeće pitanje $pitanje");
			zamgerlog("editovanje pitanja: nepostojece pitanje $pitanje", 3);
			return;
		}
		if (mysql_result($q230,0,0) != $kviz) {
			niceerror("Pitanje nije sa ovog kviza");
			zamgerlog("editovanje pitanja: pitanje $pitanje nije sa kviza $kviz (pp$predmet ag$ag)", 3);
			return;
		}
		$tip = mysql_result($q230,0,1);
		$tekst = mysql_result($q230,0,2);
		$bodova = mysql_result($q230,0,3);
		if (mysql_result($q230,0,4)==1) $vidljivo = "CHECKED"; else $vidljivo = "";
		$subakcija="potvrda_izmjene";
	} else {
		print "<b>Dodajte novo pitanje</b><br>\n";
		$tekst = $vidljiv = "";
		$bodova = $pitanje = 0;
		$tip = "mcsa";
		$subakcija="potvrda_novo";
	}
	unset($_REQUEST['subakcija']);
	unset($_GET['subakcija']);
	
	?>
	<?=genform("POST");?>
	<input type="hidden" name="subakcija" value="<?=$subakcija?>">
	<input type="hidden" name="pitanje" value="<?=$pitanje?>">
	<table border="0">
		<tr><td>Tekst pitanja:</td><td><input type="text" size="50" name="tekst" value="<?=$tekst?>"></td></tr>
		<tr><td>Bodova:</td><td><input type="text" size="5" name="bodova" value="<?=$bodova?>"></td></tr>
		<tr><td>Tip pitanja:</td><td>
			<select name="tip">
				<option value="mcsa" <? if ($tip=="mcsa") print "SELECTED" ?>>MCSA</option>
				<option value="mcma" <? if ($tip=="mcma") print "SELECTED" ?>>MCMA</option>
				<option value="tekstualno" <? if ($tip=="tekstualno") print "SELECTED" ?>>Tekstualno</option>
			</select>
			<a href="#" onclick="javascript:window.open('legenda-pitanja.html','blah6','width=320,height=300');">Legenda tipova pitanja</a>
		</td></tr>
		<tr><td align="right"><input type="checkbox" name="vidljivo" value="1" <?=$vidljivo?>></td><td>Pitanje vidljivo</td></tr>
	</table>
	<br>Ponuđeni odgovori:<br>
	<ul>
	<?
	$q240 = myquery("select id, tekst, tacan, vidljiv from kviz_odgovor where kviz_pitanje=$pitanje");
	if (mysql_num_rows($q240)==0)
		print "<li>Do sada nije unesen nijedan odgovor</li>\n";
	while ($r240 = mysql_fetch_row($q240)) {
		print "<li>";
		if ($r240[3]==0) print "<font color=\"#AAAAAA\">";
		print $r240[1];
		if ($r240[2] == 1) { print " (TAČAN)"; $toggle_link = "Proglasi za netačan"; }
		else { $toggle_link = "Proglasi za tačan"; }
		if ($r240[3]==0) print "</font> - nevidljiv";
		?> - <a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=obrisi_odgovor&odgovor=<?=$r240[0]?>">Obriši</a>
		- <a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja&subakcija=toggle_tacnost&odgovor=<?=$r240[0]?>"><?=$toggle_link?></a></li>
		<?
	}

	?>
	</ul>
	<input type="submit" value="Promjena pitanja"><br>
	</form>
	<br>
	Dodajte odgovor na ovo pitanje:<br>
	<?=genform("POST");?>
	<input type="hidden" name="subakcija" value="dodaj_odgovor">
	<input type="hidden" name="pitanje" value="<?=$pitanje?>">
	Tekst odgovora: <input type="text" name="tekst" size="50"><br>
	<input type="checkbox" name="tacan" value="1"> Tačan<br>
	<input type="submit" value="Dodaj"><br>
	</form>
	<?

	return;
}


// Korektno brisanje kviza

if ($_REQUEST['_lv_action_delete']) {
	$kviz = intval($_REQUEST['_lv_column_id']);
	$q200 = myquery("select naziv, predmet, akademska_godina from kviz where id=$kviz");
	if (mysql_num_rows($q200)<1) {
		niceerror("Nepostojeći kviz $kviz");
		zamgerlog("editovanje pitanja: nepostojeci kviz $kviz", 3);
		return;
	}
	if ((mysql_result($q200,0,1) != $predmet) || (mysql_result($q200,0,2) != $ag)) {
		niceerror("Kviz nije sa ovog predmeta");
		zamgerlog("editovanje pitanja: kviz $kviz nije sa predmeta pp$predmet ag$ag", 3);
		return;
	}
	
	$q400 = myquery("select id from kviz_pitanje where kviz=$kviz");
	// Brisemo odgovore
	while ($r400 = mysql_fetch_row($q400)) {
		$q410 = myquery("delete from kviz_odgovor where kviz_pitanje=$r400[0]");
	}
	$q420 = myquery("delete from kviz_pitanje where kviz=$kviz");
	$q430 = myquery("delete from kviz_student where kviz=$kviz");
	// db_form() će pobrisati stavku iz tabele kviz
}


// Provjeravamo da li je raspon dobro unesen

if (($_REQUEST['_lv_action'] == "edit" || $_REQUEST['_lv_action'] == "add") && !$_REQUEST['_lv_action_delete']) {
	$ip_adresa_losa = false;

	if ($_REQUEST['_lv_action'] == "edit") {
		$id_kviza = intval($_REQUEST['_lv_column_id']);
		// Dodajemo logging
		zamgerlog("izmijenjen kviz $id_kviza (pp$predmet)", 2);
	} else {
		$labgrupa = intval($_REQUEST['_lv_column_labgrupa']);
		$naziv = my_escape($_REQUEST['_lv_column_naziv']);
		$pb = floatval($_REQUEST['_lv_column_prolaz_bodova']);
		$q100 = myquery("select id from kviz where predmet=$predmet and akademska_godina=$ag and naziv='$naziv' and labgrupa=$labgrupa and prolaz_bodova=$pb");
		$id_kviza = mysql_result($q100,0,0);
		zamgerlog("dodan novi kviz $id_kviza (pp$predmet)", 2);
	}

	$ip_adrese = $_REQUEST['_lv_column_ip_adrese'];

	foreach (explode(",", $ip_adrese) as $blok) {
		if (strstr($blok, "/")) { // blok adresa u CIDR formatu
			list ($baza, $maska) = explode("/", $blok);
			if ($baza != long2ip(ip2long($baza))) { $ip_adresa_losa = true; break; }
			if ($maska != intval($maska)) { $ip_adresa_losa = true; break; }
			if ($maska<1 || $maska>32) { $ip_adresa_losa = true; break; }
		}
		else if (strstr($blok, "-")) { // raspon adresa sa crticom
			list ($pocetak, $kraj) = explode("-", $blok);
			if ($pocetak != long2ip(ip2long($pocetak))) { $ip_adresa_losa = true; break; }
			if ($kraj != long2ip(ip2long($kraj))) { $ip_adresa_losa = true; break; }
		}
		else { // pojedinačna adresa
			if ($blok != long2ip(ip2long($blok))) { $ip_adresa_losa = true; break; }
		}
	}

	// Vraćamo se na editovanje lošeg kviza
	if ($ip_adresa_losa) {
		$_REQUEST['_lv_nav_id'] = $id_kviza;
		$_GET['_lv_nav_id'] = $id_kviza;
		$_POST['_lv_nav_id'] = $id_kviza;

		niceerror("Neispravan format IP adrese");
		?>
		<p>Raspon IP adresa treba biti u jednom od formata:<br>
		- CIDR format (npr. 123.45.67.89/24)<br>
		- raspon početak-kraj sa crticom (npr. 123.45.67.89-123.45.67.98)<br>
		- pojedinačna adresa<br>
		Takođe možete navesti više raspona ili pojedinačnih adresa razdvojenih zarezom.</p>
		<?
	}
}


// Spisak postojećih kvizova

$_lv_["where:predmet"] = $predmet;
$_lv_["where:akademska_godina"] = $ag;
$_lv_["new_link"] = "Unos novog kviza";

print "Odaberite neki od postojećih kvizova koji želite administrirati:<br/>\n";
print db_list("kviz");

print "<hr>\n";
$kviz = intval($_REQUEST['_lv_nav_id']);
if ($kviz > 0) {
	?>
	<h3>Izmjena kviza</h3>
	<ul>
		<li><a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=pitanja">Izmijenite pitanja na kvizu</a></li>
		<li><a href="?sta=nastavnik/kvizovi&predmet=<?=$predmet?>&ag=<?=$ag?>&kviz=<?=$kviz?>&akcija=rezultati">Rezultati kviza (do sada poslani odgovori)</a></li>
	</ul>
	<?
} else {
	?>
	<h3>Kreiranje novog kviza</h3>
	<p>Unesite podatke o novom kvizu koji želite kreirati:</p><br>
	<?
}


$_lv_["label:vrijeme_pocetak"] = "Početak";
$_lv_["label:vrijeme_kraj"] = "Kraj";
$_lv_["label:labgrupa"] = "Samo za studente iz grupe";
$_lv_["label:ip_adrese"] = "Ograniči na IP adrese";
$_lv_["label:prolaz_bodova"] = "Minimum bodova za prolaz";
$_lv_["label:trajanje_kviza"] = "Trajanje kviza (u sekundama)";
$_lv_["hidden:predmet"] = 1;
$_lv_["hidden:akademska_godina"] = 1;
print db_form("kviz", "kvizform");


// Markiramo loše polje
if ($ip_adresa_losa) {
	?>
	<script>
	var element = document.getElementsByName('_lv_column_ip_adrese');
	element[0].style.backgroundColor = "#FF9999";
	element[0].focus();
	element[0].select();
	</script>
	<?
}

}

?>