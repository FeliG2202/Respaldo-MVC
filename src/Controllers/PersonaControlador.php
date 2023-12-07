<?php 

namespace PHP\Controllers;

use PHP\Models\PersonaModelo;
use LionSpreadsheet\Spreadsheet;

class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

	public function readCell($columnAddress, $row, $worksheetName = '') {
        // Read title row and rows 20 - 30
		if ($row > 2) {
			return true;
		}
		return false;
	}
}

class PersonaControlador {

	private $PersonaModelo;

	function __construct(){
		$this->PersonaModelo = new PersonaModelo();
	}

	public function registrarPersonaControlador() {
		$res = $this->PersonaModelo->registrarPersonaModelo([
			'personaDocumento' => request->personaDocumento,
			'personaNombreCompleto' => request->personaNombreCompleto,
			'personaCorreo' => request->personaCorreo,
			'personaNumberCell' => toNull(request->personaNumberCell)
		]);

		if ($res->status === "database-error") {
			return response->code(500)->error('Error al momento de registrar');
		}

		return response->code(200)->success('registrado correctamente');
	}

	public function consultarPersonaControlador() {
		return $this->PersonaModelo->consultarPersonaModelo();
	}

	public function actualizarPersonaControlador(string $idPersona) {
		$res = $this->PersonaModelo->actualizarPersonaModelo([
			'personaDocumento' => request->personaDocumento,
			'personaNombreCompleto' => request->personaNombreCompleto,
			'personaCorreo' => request->personaCorreo,
			'personaNumberCell' => request->personaNumberCell,
			'idPersona' => (int) $idPersona
		]);

		if ($res->status === 'database-error') {
			return response->code(500)->error('Error al momento de actualizar');
		}

		return response->code(200)->success('actualizado correctamente');
	}

	public function eliminarPersonaControlador(string $idPersona) {
		$res = $this->PersonaModelo->eliminarPersonaModelo([
			'idPersona' => (int) $idPersona
		]);

		if ($res->status === 'database-error') {
			return $res;
			return response->code(500)->error('Error al momento de Eliminar');
		}

		return response->code(200)->success('Eliminado correctamente');
	}

	public function uploadControlador() {

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		$inputFileName = $_FILES['excel']['tmp_name'];

		$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		$reader->setReadFilter(new MyReadFilter());
		$spreadsheet = $reader->load($inputFileName);

		$data = $spreadsheet->getActiveSheet()->toArray();
		$baseDocument = $this->PersonaModelo->existeDocumento();
		$baseEmail = $this->PersonaModelo->existeCorreo();
		$documentosBase = array();
		$correosBase = array();

		foreach($baseDocument as $doc) {
			$documentosBase[] = $doc->personaDocumento;
		}

		foreach($baseEmail as $email) {
			$correosBase[] = $email->personaCorreo;
		}

		foreach ($data as $row) {
			if ($row[1] != '') {
				if (in_array($row[2], $documentosBase)) {
					return response->code(500)->error("El documento {$row[2]} ya está registrado.");
					continue;
				}
				if (in_array($row[3], $correosBase)) {
					return response->code(500)->error("El correo {$row[3]} ya está registrado.");
					continue;
				}
				$dataParaGuardar = [
					'personaNombreCompleto' => $row[1],
					'personaDocumento' => $row[2],
					'personaCorreo' => $row[3],
					'personaNumberCell' => $row[4],
				];
				try {
					$this->PersonaModelo->uploadModelo($dataParaGuardar);
					return response->code(200)->success('Los datos se han enviado al modelo correctamente.');
				} catch (\Exception $e) {
					return response->code(500)->error('Error al guardar los datos: ',  $e->getMessage(), "\n");
				}
			}
		}
	}
}