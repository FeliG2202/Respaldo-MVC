<?php

namespace PHP\Models;

use LionDatabase\Drivers\MySQL\MySQL as DB;

class AlmDiaModelo {

	public function ListarAlmDiaMenuModelo() {
		return DB::table('nutridias')->select()->getAll();
	}

	public function ListarAlmSemanaMenuModelo() {
		return DB::table('nutrisemana')->select()->getAll();
	}
}