<?php

class Lisuke extends BaseModel {

	public $name, $price;
	public function __construct($attributes = null) {
                parent::__construct($attributes);
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


public static function add($res) {
        $db = DB::connection();
        $nimi = $res->request->post('a_lisukename');
        if ($nimi == null) {
                $res->redirect('/tsoha/');
        }
        $hinta = $res->request->post('a_lisukehinta');
        if ($hinta == null) {
	        $res->redirect('/tsoha/');
        }
        $sql = "INSERT INTO lisukkeet(lisuke_nimi, lisuke_hinta) VALUES(:lisuke_nimi, :lisuke_hinta)";
        $db->beginTransaction();
        $st = $db->prepare($sql);
        $st->execute(array(
                "lisuke_nimi" => $nimi,
                "lisuke_hinta" => $hinta));
        $db->commit();
        $res->redirect('/tsoha');
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
