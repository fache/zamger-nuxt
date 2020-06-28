<?php

// Modul: lms/homework
// Klasa: ProgrammingLanguage
// Opis: pomoćna klasa za zadatke koji predstavljaju programski kod


class ProgrammingLanguage {
	public $id;
	public $name, $geshi, $extension, $compilerName, $compilerOptions, $compilerDebugOptions;
	
	public static function fromId($id) {
		$pl = DB::query_assoc("SELECT id, naziv name, geshi, ekstenzija extension, kompajler compilerName, opcije_kompajlera compilerOptions, opcije_kompajlera_debug compilerDebugOptions FROM programskijezik WHERE id=$id");
		if (!$pl) throw new Exception("Unknown programming language $id", "404");
		return Util::array_to_class($pl, "ProgrammingLanguage", array());
	}
}

?>
