<?php
	require('connection.inc.php');

	//MYSQL SIMPLE SELECT
	/*
	** Die sql_select() - function selectiert je nach Bedarf werte aus der Datenbank.
	** OPTIONEN: 
	** $selectors 	- Mögliche Werte: 'all' oder die gewünschten Spaltennamen einer Tabelle
	** $table 		- Gibt den Namen der Tabelle an
	** $orderby 	- Gibt den Spaltennamen nachdem sortiert werden soll an, ist der Wert '' wird der ORDER BY Befehl ignoriert
	** $order 		- Mögliche Werte: 'ASC', 'DESC'
	*/
	function sql_select($selectors, $table, $orderby, $order) {
		global $db;

		if ($selectors = 'all') { $selectors = '*'; }
		if ($order == '') { 
			$sql = "SELECT $selectors FROM $table"; 
		} else {
			$sql = "SELECT $selectors FROM $table ORDER BY $orderby $order";
		}

		$res = mysqli_query($db, $sql) or die(mysqli_error($db));

		return $res;
	}

	//MYSQL EXTEND SELECT -> WHERE
	/*
	** Die sql_select_where() - function arbeitet wie die sql_select() - function, mit where clause
	** OPTIONEN: 
	** $selectors 		- Mögliche Werte: 'all' oder die gewünschten Spaltennamen einer Tabelle
	** $table 			- Gibt den Namen der Tabelle an
	** $whereSelectors 	- Gibt die gewünschten Spaltennamen als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $whereValues 	- Gibt die Inhalte nach denen selectiert werden soll als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $orderby 		- Gibt den Spaltennamen nachdem sortiert werden soll an, ist der Wert '' wird der ORDER BY Befehl ignoriert
	** $order 			- Mögliche Werte: 'ASC', 'DESC'
	*/
	function sql_select_where($selectors, $table, $whereSelectors, $whereValues, $orderby, $order) {
		global $db;

		if ($selectors = 'all') { $selectors = '*'; }

		$sql = "SELECT $selectors FROM $table WHERE ";

		if (is_array($whereSelectors) & is_array($whereValues)) {
			$sql .= "$whereSelectors[0] = '" . $whereValues[0] . "'";
			$i = 0;
			foreach($whereSelectors as $key => $val) {
				if (!$i == 0) { $sql .= " AND $val = '" . $whereValues[$i] . "'"; }
				$i++;
			}
		} else {
			$sql .= "$whereSelectors = '" . $whereValues . "'";
		}

		if ($orderby != '') {
			$sql .= " ORDER BY $orderby $order";
		}
		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	//MYSQL EXTEND SELECT AND JOIN -> WHERE
	/*
	** Die sql_select_where() - function arbeitet wie die sql_select() - function, mit where clause
	** OPTIONEN: 
	** $selectors 		- Mögliche Werte: 'all' oder die gewünschten Spaltennamen einer Tabelle
	** $table 			- Gibt den Namen der Tabelle an
	** $joinTable 		- Gibt den Namen der zu kombinierenden Tabelle an
	** $joinSelector 	- Gibt die zu verbindene Spalte an.
	** $joinValue 		- Gibt die zu vergleichende Spalte an.
	** $whereSelectors 	- Gibt die gewünschten Spaltennamen als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $whereValues 	- Gibt die Inhalte nach denen selectiert werden soll als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $orderby 		- Gibt den Spaltennamen nachdem sortiert werden soll an, ist der Wert '' wird der ORDER BY Befehl ignoriert
	** $order 			- Mögliche Werte: 'ASC', 'DESC'
	*/
	function sql_select_join_where($selectors, $table, $joinTable, $joinSelector, $joinValue, $whereSelectors, $whereValues, $orderby, $order) {
		global $db;

		if ($selectors = 'all') { $selectors = '*'; }

		$sql = "SELECT $selectors FROM $table ";

		if (is_array($joinTable) && is_array($joinSelector) && is_array($joinValue)) {
			$i = 0;
			foreach($joinSelector as $key => $val) {
				$sql .= " LEFT JOIN " . $joinTable[$i] . " ON";
				$sql .= " $val = ".$joinValue[$i]." ";
				$i++;
			}
		} else {
			$sql .= " LEFT JOIN $joinTable ON";
			$sql .= " $joinSelector = $joinValue ";
		}

		if ($whereSelectors && $whereValues != '') {
			$sql .= " WHERE ";
			if (is_array($whereSelectors) & is_array($whereValues)) {
				$sql .= "$whereSelectors[0] = '" . $whereValues[0] . "'";
				$i = 0;
				foreach($whereSelectors as $key => $val) {
					if (!$i == 0) { $sql .= " AND $val = '" . $whereValues[$i] . "'"; }
					$i++;
				}
			} else {
				$sql .= "$whereSelectors = '" . $whereValues . "'";
			}
		}

		if ($orderby != '') {
			$sql .= " ORDER BY $orderby $order";
		}
		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	//MYSQL EXTEND SELECT -> WHERE NOT IN
	/*
	** Die sql_select_where_not_in() - function arbeitet wie die sql_select() - function, mit where clause
	** OPTIONEN: 
	** $selectors 		- Mögliche Werte: 'all' oder die gewünschten Spaltennamen einer Tabelle
	** $table 			- Gibt den Namen der Tabelle an
	** $whereSelectors 	- Gibt die gewünschten Spaltennamen als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $whereValues 	- Gibt die Inhalte nach denen selectiert werden soll als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $orderby 		- Gibt den Spaltennamen nachdem sortiert werden soll an, ist der Wert '' wird der ORDER BY Befehl ignoriert
	** $order 			- Mögliche Werte: 'ASC', 'DESC'
	*/
	function sql_select_where_not_in($selectors, $table, $whereSelectors, $whereValues, $orderby, $order) {
		global $db;

		if ($selectors = 'all') { $selectors = '*'; }

		$sql = "SELECT $selectors FROM $table WHERE ";

		$sql .= "$whereSelectors NOT IN ('" . $whereValues . "')";

		if ($orderby != '') {
			$sql .= " ORDER BY $orderby $order";
		}
		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	//MYSQL EXTEND SELECT -> PHASE
	/*
	** Die sql_select_where_not_in() - function arbeitet wie die sql_select() - function, mit where clause
	** OPTIONEN: 
	** $selectors 		- Mögliche Werte: 'all' oder die gewünschten Spaltennamen einer Tabelle
	** $table 			- Gibt den Namen der Tabelle an
	** $whereSelectors 	- Gibt die gewünschten Spaltennamen als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $whereValues 	- Gibt die Inhalte nach denen selectiert werden soll als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $orderby 		- Gibt den Spaltennamen nachdem sortiert werden soll an, ist der Wert '' wird der ORDER BY Befehl ignoriert
	** $order 			- Mögliche Werte: 'ASC', 'DESC'
	*/
	function sql_select_phase($selectors, $table, $startDate, $endDate, $orderby, $order) {
		global $db;
		global $institut_id;

		if ($selectors = 'all') { $selectors = '*'; }

		$sql = "SELECT $selectors FROM $table WHERE ";

		$sql .= "pha_startdate <= '" . $startDate . "' AND ";
		$sql .= "pha_enddate >= '" . $endDate . "' AND ";
		$sql .= "pha_institut = '" . $institut_id . "' AND ";
		$sql .= "pha_status = '1'";


		if ($orderby != '') {
			$sql .= " ORDER BY $orderby $order";
		}
		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	function sql_select_max($selector, $table) {
		global $db;

		$sql = "SELECT MAX(".$selector.") FROM ".$table;

		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	//MYSQL DELETE -> WHERE
	/*
	** Die sql_delete() - function arbeitet wie die sql_select() - function, mit where clause
	** OPTIONEN: 
	** $table 			- Gibt den Namen der Tabelle an
	** $whereSelectors 	- Gibt die gewünschten Spaltennamen als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $whereValues 	- Gibt die Inhalte nach denen selectiert werden soll als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $orderby 		- Gibt den Spaltennamen nachdem sortiert werden soll an, ist der Wert '' wird der ORDER BY Befehl ignoriert
	** $order 			- Mögliche Werte: 'ASC', 'DESC'
	*/
	function sql_delete($table, $whereSelectors, $whereValues) {
		global $db;

		$sql = "DELETE FROM $table WHERE ";

		if (is_array($whereSelectors) & is_array($whereValues)) {
			$sql .= "$whereSelectors[0] = '" . $whereValues[0] . "'";
			$i = 0;
			foreach($whereSelectors as $key => $val) {
				if (!$i == 0) { $sql .= " AND $val = '" . $whereValues[$i] . "'"; }
				$i++;
			}
		} else {
			$sql .= "$whereSelectors = '" . $whereValues . "'";
		}

		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	//MYSQL INSERT INTO
	/*
	** Die sql_insert() - function erstellt einen INSERT INTO mysql-Befehl
	** $table 		- Gibt den Namen der Tabelle an
	** $selectors 	- Gibt die Spaltennamen als array an. !Ein Einzelner Wert wird als String angegeben
	** $values 		- Gibt die Spalteninhalte als array an. !Ein Einzelner Wert wird als String angegeben
	*/
	function sql_insert($table, $selectors, $values) {
		global $db;

		$sql = "INSERT INTO $table (";

		if (is_array($selectors) & is_array($values)) {
			foreach($selectors as $key => $val) {
				$sql .= "$val, ";
			}

			$sql = substr_replace($sql, '', -2);
			$sql .= ", new_time, new_date, chg_time, chg_date) VALUES (";

			foreach($values as $key => $val) {
				$sql .= "'$val', ";
			}

			$sql = substr_replace($sql, '', -2);
			$sql .= ", curtime(), curdate(), curtime(), curdate())";
		} else {
			$sql .= "$selectors, new_time, new_date, chg_time, chg_date) VALUES ('$values', curtime(), curdate(), curtime(), curdate())";
		}

		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	//MYSQL UPDATE
	/*
	** Die sql_update() - function erstellt einen INSERT INTO mysql-Befehl
	** $table 		- Gibt den Namen der Tabelle an
	** $selectors 	- Gibt die Spaltennamen als array an. !Ein Einzelner Wert wird als String angegeben
	** $values 		- Gibt die Spalteninhalte als array an. !Ein Einzelner Wert wird als String angegeben
	** $whereSelectors 	- Gibt die gewünschten Spaltennamen als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	** $whereValues 	- Gibt die Inhalte nach denen selectiert werden soll als array an. !Eine Einzelne Spalte kann per string eingetragen werden.
	*/
	function sql_update($table, $selectors, $values, $whereSelectors, $whereValues) {
		global $db;

		$sql = "UPDATE $table SET ";

		if (is_array($selectors) & is_array($values)) {
			$sql .= "$selectors[0] = '" . $values[0] . "'";
			$i = 0;
			foreach($selectors as $key => $val) {
				if (!$i == 0) { 
					$sql .= ", $val = '" . $values[$i] . "'"; 
				}
				$i++;
			}
		} else {
			$sql .= "$selectors  = '" . $values . "'";
		}
		$sql .= ", chg_time = curtime(), chg_date = curdate()";

		if (is_array($whereSelectors) & is_array($whereValues)) {
			$sql .= " WHERE $whereSelectors[0] = '" . $whereValues[0] . "'";
			$i = 0;
			foreach($whereSelectors as $wkey => $whereVal) {
				if (!$i == 0) { $sql .= " AND $whereVal = '" . $whereValues[$i] . "'"; }
				$i++;
			}
		} else {
			$sql .= " WHERE $whereSelectors = '" . $whereValues . "'";
		}

		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	//MYSQL OUTPUT
	/*
	** Die sql_output() - function gibt nach bedarf entsprechende Werte aus der Datenbank aus
	** $source 		- Gibt die Quelle des querys an
	** $type 		- Bestimmt die Art der Ausgabe
	** $selectors 	- Legt die Auszugebenen Spalten fest. !per '{custom} ...' kann $selectors übergangen werden
	** Beispiel: echo sql_output($result, 'table', array('hid', 'hersteller', 'strasse', 'hausnummer', 'plz', 'ort', 'tel', 'email'));
	*/
	function sql_output($source, $type, $selectors) {
		$output = '';
		while($row = mysqli_fetch_object($source)) {
			switch ($type) {
				case 'table':
					$output .= '<tr>';
					$i=0;
					foreach($selectors as $key => $val) {
						if ($i==0) {
							if ($val!=str_replace('{custom}','',$val)) { 
								$val = str_replace('{custom}','',$val);
								$output .= '<th scope="row">'.$val.'</th>'; 
							} else { 
								$output .= '<th scope="row">'.$row->$val.'</th>'; 
							}
							$i++;
						} else {
							if ($val!=str_replace('{custom}','',$val)) { 
								$val = str_replace('{custom}','',$val);
								$output .= '<td>'.$val.'</td>'; 
							}
							else { 
								$output .= '<td>'.$row->$val.'</td>'; 
							}
						}
					}
					$output .= '</tr>';
					break;
				case 'select': 
					if (is_array($selectors)) {
						foreach ($selectors as $key => $val) {
							if ($val!=str_replace('{custom}','',$val)) { 
								$val = str_replace('{custom}','',$val);
								$output .= '<option>'.$val.'</option>'; 
							} else { 
								$output .= '<option>'.$row->$val.'<option>'; 
							}
						}
					} else {
						$output .= '<option>'.$row->$selectors.'</option>'; 
					}
			}
		}

		return $output;
	}

	/* Prüfen, ob ein String eine bestimmte Zeichenkette enthält
	** OPTIONEN:
	** $string 		- Gibt den zu durchsuchenden String an.
	** $character 	- Gibt die Zeichenkette an, nach der gesucht werden soll.
	*/
	function str_searching($string, $character) {
		if(!is_array($character)) $character = array($character);

		foreach($character as $query) {
			if (strpos($string, $query) !== false) {
				return true; // stoppt bei erstem true
			}
		}
		return false;
	}

	/* Alter in Jahren errechnen */
	function alterberechnung($date){
		$birthday 	= $date;
		$today 		= date("Y-m-d");
		$diff 		= date_diff(date_create($birthday), date_create($today));
		return $diff->format('%y');
	}

	/* ZUFALLSSTRING ERSTELLEN */
	function zufallsstring($length) {
		//Mögliche Zeichen für den String
		$character = '0123456789';
		$character .= 'abcdefghijklmnopqrstuvwxyz';
		$character .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		//String wird generiert
		$str 	= '';
		$num 	= strlen($character);
		for ($i=0; $i < $length; $i++) {
			$str .= $character[rand(0,$num-1)];
		}
		return $str;
	}

		//IMAGE RESIZING
	/* Die fc_imgresize() 	- function zur Größenanpassung von Motiven
	** $imgfile 			- anzupassende Bilddatei
	** $location 			- Speicherort für das verkleinerte Bild
	*/
	function fc_imgresize($imgfile, $location, $filenameOnly = true) {
		//Dateiname erzeugen
		$filename = basename($imgfile);

		//Fügt den Pfad zur Datei dem Dateinamen hinzu
		//Aus folder/img/bild1.jpg wird dann folder_bilder_bild1.jpg
		if (!$filenameOnly) {
			$replace = array('/','\\','.');
			$filename = str_replace($replace,'_',dirname($imgfile)).'_'.$filename;
		}

		//Schreibarbeit sparen
		$folder = $location;

		//Speicherfolder vorhanden
		if ( !is_dir($folder) ) {
			return false;
		}

		//Wenn Datei schon vorhanden, kein Thumbnail erstellen
		if ( file_exists($folder.$filename) ) {
			return $folder.$filename;
		}

		//Ausgansdatei vorhanden? Wenn nicht, false zurückgeben
		if ( !file_exists($imgfile) ) {
			return false;
		}

		//Infos über das Bild
		$extension = strrchr($imgfile,'.');

		list($width, $height) = getimagesize($imgfile);
		$imgratio=$width/$height;

		//Ist das Bild höher als breit?
		if ($imgratio > 1) {
			$newwidth = 1920;
			$newheight = 1920 / $imgratio;
		} else {
			$newheight = 1920;
			$newwidth = 1920 * $imgratio;
		}

		//Bild erstellen
		//Achtung: imagecreatetruecolor funktioniert nur bei bestimmten GD Versionen
		//Falls ein Fehler auftritt, imagecreate nutzen
		if ( function_exists('imagecreatetruecolor') ) {
			$thumb = imagecreatetruecolor($newwidth,$newheight); 
		} else {
			$thumb = imagecreate($newwidth,$newheight);
		}

		if ($extension == '.jpg') {
			imageJPEG($thumb,$folder.'temp.jpg');
			$thumb = imagecreatefromjpeg($folder.'temp.jpg');
			$source = imagecreatefromjpeg($imgfile);
		} elseif ($extension == '.gif') {
			imageGIF($thumb,$folder.'temp.gif');
			$thumb = imagecreatefromgif($folder.'temp.gif');
			$source = imagecreatefromgif($imgfile);
		} elseif ($extension == '.png') {
			imagePNG($thumb,$folder.'temp.png');
			$thumb = imagecreatefrompng($folder.'temp.png');
			$source = imagecreatefrompng($imgfile);

			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);
			$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
			imagefilledrectangle($thumb, 0, 0, $newwidth, $newheight, $transparent);
		}

		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		//Bild speichern
		if ($extension == '.png') {
			imagepng($thumb,$folder.$filename);
		} elseif($extension == '.gif') {
			imagegif($thumb,$folder.$filename);
		} else {
			imagejpeg($thumb,$folder.$filename,100);
		}

		//Speicherplatz wieder freigeben
		ImageDestroy($thumb);
		ImageDestroy($source);

		//Pfad zu dem Bild zurückgeben
		return $folder.$filename;
	}

	//IMAGE THUMBNAIL
	/* Die fc_imgthumbnail() 	- function zur Größenanpassung von Motiven
	** $imgfile 				- anzupassende Bilddatei
	** $location 				- Speicherort für das verkleinerte Bild
	*/
	function fc_imgthumbnail($imgfile, $location, $filenameOnly = true) {
		//Dateiname erzeugen
		$filename = basename($imgfile);

		//Fügt den Pfad zur Datei dem Dateinamen hinzu
		//Aus folder/img/bild1.jpg wird dann folder_bilder_bild1.jpg
		if (!$filenameOnly) {
			$replace = array('/','\\','.');
			$filename = str_replace($replace,'_',dirname($imgfile)).'_'.$filename;
		}

		//Schreibarbeit sparen
		$folder = $location;

		//Speicherfolder vorhanden
		if ( !is_dir($folder) ) {
			return false;
		}

		//Wenn Datei schon vorhanden, kein Thumbnail erstellen
		if ( file_exists($folder.$filename) ) {
			return $folder.$filename;
		}

		//Ausgansdatei vorhanden? Wenn nicht, false zurückgeben
		if ( !file_exists($imgfile) ) {
			return false;
		}

		//Infos über das Bild
		$extension = strrchr($imgfile,'.');

		list($width, $height) = getimagesize($imgfile);
		$imgratio=$width/$height;

		//Ist das Bild höher als breit?
		if ($imgratio > 1) {
			$newwidth = 500;
			$newheight = 500 / $imgratio;
		} else {
			$newheight = 500;
			$newwidth = 500 * $imgratio;
		}

		//Bild erstellen
		//Achtung: imagecreatetruecolor funktioniert nur bei bestimmten GD Versionen
		//Falls ein Fehler auftritt, imagecreate nutzen
		if ( function_exists('imagecreatetruecolor') ) {
			$thumb = imagecreatetruecolor($newwidth,$newheight); 
		} else {
			$thumb = imagecreate($newwidth,$newheight);
		}

		if ($extension == '.jpg') {
			imageJPEG($thumb,$folder.'temp.jpg');
			$thumb = imagecreatefromjpeg($folder.'temp.jpg');
			$source = imagecreatefromjpeg($imgfile);
		} elseif ($extension == '.gif') {
			imageGIF($thumb,$folder.'temp.gif');
			$thumb = imagecreatefromgif($folder.'temp.gif');
			$source = imagecreatefromgif($imgfile);
		} elseif ($extension == '.png') {
			imagePNG($thumb,$folder.'temp.png');
			$thumb = imagecreatefrompng($folder.'temp.png');
			$source = imagecreatefrompng($imgfile);

			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);
			$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
			imagefilledrectangle($thumb, 0, 0, $newwidth, $newheight, $transparent);
		}

		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		//Bild speichern
		if ($extension == '.png') {
			imagepng($thumb,$folder.$filename);
		} elseif($extension == '.gif') {
			imagegif($thumb,$folder.$filename);
		} else {
			imagejpeg($thumb,$folder.$filename,100);
		}

		//Speicherplatz wieder freigeben
		ImageDestroy($thumb);
		ImageDestroy($source);

		//Pfad zu dem Bild zurückgeben
		return $folder.$filename;
	}

	

?>