<?php

// Modul: core
// Klasa: ScoringElement
// Opis: jedan od elemenata koji ulaze u ukupan broj bodova na predmetu, npr: zadaća, ispit, prisustvo...


require_once(Config::$backend_path."core/CourseOffering.php");
require_once(Config::$backend_path."core/ScoringType.php");

class ScoringElement {
	public $id;
	public $name, $guiName, $abbrev, $ScoringType, $max, $pass, $option, $mandatory;
	
	public static function fromId($id) {
		$se = DB::query_assoc("SELECT id, naziv name, gui_naziv guiName, kratki_gui_naziv abbrev, tipkomponente ScoringType, maxbodova max, prolaz pass, opcija _option, uslov mandatory FROM komponenta WHERE id=$id");
		$se['option'] = $se['_option']; unset($se['_option']); // reserved word in SQL
		if (!$se) throw new Exception("Unknown scoring element $id", "404");
		$se = Util::array_to_class($se, "ScoringElement", array("ScoringType"));
		if ($se->mandatory == 1) $se->mandatory = true; else $se->mandatory = false; // FIXME use boolean in database
		return $se;
	}

	// Get list of scoring elements for Scoring of given type (see ScoringElementType), if type is 0 get all
	// If student is provided, get also score for student
	public static function fromScoring($scoringId, $seType = 0) {
		$sql = "";
		if ($seType != 0) $sql = " and k.tipkomponente=".intval($seType);

		$ses = DB::query_table("select k.id id, k.naziv name, k.gui_naziv guiName, k.kratki_gui_naziv abbrev, k.tipkomponente ScoringType, k.maxbodova max, k.prolaz pass, k.opcija _option, k.uslov mandatory from komponenta as k, tippredmeta_komponenta as tpk where tpk.komponenta=k.id and tpk.tippredmeta=".$scoringId.$sql);
		foreach($ses as &$se) {
			$se['option'] = $se['_option']; unset($se['_option']); // reserved word in SQL
			$se = Util::array_to_class($se, "ScoringElement", array("ScoringType"));
			if ($se->mandatory == 1) $se->mandatory = true; else $se->mandatory = false; // FIXME use boolean in database
		}
		return $ses;
	}
}

?>
