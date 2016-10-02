<?php

class Lisuke extends BaseModel {
	private $params = null;
	private $res = null;
	public $name, $price;
	public function __construct($attributes, $_res) {
                parent::__construct($attributes);
		$this->params = $attributes;
		$this->res = $_res;
        }       	

	public static function all() {
		$db = DB::connection();
		$sql = "SELECT id,lisuke_nimi,lisuke_hinta FROM lisukkeet ORDER BY id";
		$reesult = array();
		foreach($db->query($sql) as $row) {

			$result[] = $row;
		}
		return $result;
	}


	public static function get() {
		$conn = DB::connection();
        	$rv = array();
        	$sql = "SELECT id,lisuke_nimi,lisuke_hinta FROM lisukkeet ORDER BY id";
       		$sth = $conn->prepare($sql);
        	$sth->execute();
        	$result = $sth->fetchAll();
        	return ($result);
	}

	/*
	 * Return stuff about errors ...
	 */
	private function validate_add() {
		if ($this->params['lisukename'] == "virhe") {
			$this->res->redirect('/tsoha/');
			exit();
		}
		return true;

	}

	public function add() {
        	$db = DB::connection();
		$nimi = $this->params["lisukename"];
		$hinta = $this->params["lisukehinta"];

		$this->validate_add();

        	$sql = "INSERT INTO lisukkeet(lisuke_nimi, lisuke_hinta) VALUES(:lisuke_nimi, :lisuke_hinta)";
        	$db->beginTransaction();
        	$st = $db->prepare($sql);
        	$st->execute(array(
                	"lisuke_nimi" => $nimi,
                	"lisuke_hinta" => $hinta));
        	$db->commit();
        	//$res->redirect('/tsoha');
        }
                        

	public static function now() {
		$db = DB::connection();
		$sql = "SELECT now()";
		$rv = '';
		foreach ($conn->query($sql) as $row) {	
			$rv = $row[0];
			break;
		}
		return ($rv);
	}

}

?>
