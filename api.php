<?php
/**
 * MAX_PARTICIPANTS - Максимальное количество участников
 * SUBSCRIBE_METHOD_SIZE - размер массива для метода SessionSubscribe
 * POST_NEWS_METHOD_TYPE - размер массива для метода PostNews
 */
define('MAX_PARTICIPANTS', 15);
define('SUBSCRIBE_METHOD_SIZE', 3);
define('POST_NEWS_METHOD_TYPE', 4);

/* Функция, собирающая ответ из обработанных данных.
 * @param string $status статус ответа (ok | error)
 * @param array  $payload Массив данных.
 *						  Если нет ответа или он не требуется - пустой массив.
 * @param string $message Сообщение пользователю.
 *
 * @return string JSON строка, возвращаемая по запросу.
 *
 * @access public
 * 
 * @since Доступна с 1.0.0
 */
function buildDataArray($status, $payload, $message){
	$data = array("status" => $status, "payload" => $payload, "message" => $message);
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}

/* Функция, обрабатывающая входные данные.
 * @param string $string Исходная строка
 *
 * @return string Обработанная строка, из которой удалены все опасные элементы.
 *
 * @access public
 * 
 * @since Доступна с 1.0.0
 */
function protector($string){
	// Удаляем HTML тэги
	$string = strip_tags($string);
	// Удаляем кодированные html символы вида '&amp;'
	$string = html_entity_decode($string);
	// Удаляем url-кодирование
	$string = urldecode($string);
	// Удаляем множественные пробелы
	$string = preg_replace('/ +/', ' ', $string);
	// Удаляем пробелы с начала и конца
	$string = trim($string);
	return $string;
}

/* Функция, возвращающая ассоциативный массив значений из ДБ.
 * @param string $query Строка запроса к ДБ
 * @param object $link Объект подключения к ДБ
 *
 * @return array Ассоциативный массив значений из ДБ.
 *
 * @access public
 *
 * @since Доступна с 1.0.0
 */
function getAssocArray($query, $link){
	$array = array();
	$result = $link->query($query);
	while($row = $result->fetch_assoc()){
		array_push($array, $row);
	}
	$result->free();
	return $array;
}

/* Функция, вовзращающая количество строк, которое вернул запрос.
 * @param string $query Строка запроса к ДБ
 * @param object $link Объект подключения к ДБ
 *
 * @return int Количество строк.
 *
 * @access public
 *
 * @since Доступна с 1.0.0
 */
function getRowNumbers($query, $link){
	$result = $link->query($query);
	$rows = $result->num_rows;
	$result->free();
	return $rows;
}

/* Функция, возвращающая уникальный id записи
 * (только тогда, когда такая запись одна).
 * @param string $query Строка запроса к ДБ
 * @param object $link Объект подключения к ДБ
 *
 * @return int ID записи, если запись одна, или -1,
 * если записей больше одной.
 *
 * @access public
 *
 * @since Доступна с 1.0.0
 */
function getUniqueId($query, $link){
	$result = $link->query($query);
	if ($result->num_rows == 1){
		$row = $result->fetch_row();
		$result->free();
		return $row[0];
	} else {
		$result->free();
		return -1;
	}
}

/* Функция, возвращающая ассоциативный массив значений, если
 * POST запрос пришел из HTML формы. В противном случае
 * возвращает строку без изменений.
 * @param string $query Строка, полученная из POST запроса
 *
 * @return array Ассоциативный массив, содержащий данные из запроса
 * @return string Входная строка, без изменений.
 *
 * @access public
 *
 * @since Доступна с 1.0.0
 */
function getPostDataFromForm($string){
	// Тестируем строку на совпадение с POST запросом из формы HTML вида
	// 'method=Table&table=News&id=1'. Если совпадений нет, то возвращаем строку
	// без изменений.
	// ВНИМАНИЕ! Передача в JSON кодированном запросе элемента вида =News& приведет к
	// нарушению работу API.
	if (preg_match("/\=\w+\&/i", $string)){
		// Вытаскиваем все элементы.
		preg_match_all("/\w+/", $string, $matcharray);
		// Так как функция preg_match_all возвращает массив в массиве, то затираем его
		// чтобы избежать обращения вида $array[0][0]
		$matcharray = $matcharray[0];
		$postdata = array();
		// Превращаем массив в ассоциативный
		for($i = 0; $i < count($matcharray); $i += 2){
			if (isset($matcharray[$i + 1])){
				$postdata[$matcharray[$i]] = $matcharray[$i + 1];
			}
		}
		return $postdata;
	}
	return $string;
}
// Подключаемся к ДБ.
$link = mysqli_connect('localhost', 'root', '', 'test_task');
if($link === false) {
     // Подключение не установлено? Уведомляем пользователя через обратный JSON массив.
	 echo buildDataArray("error", array(), mysqli_connect_error());
} else {
	mysqli_set_charset($link, 'utf8');
	$json = file_get_contents('php://input');
	// Обработка пост запроса
	$postdata = getPostDataFromForm($json);
	// Если getPostDataFromForm вернула не массив, понимаем, что пришла JSON строка.
	if (gettype($postdata) != "array"){
		$postdata = json_decode($json, true);
	}
	// Обрабатываем полученные данные.
	if (!is_null($postdata)){
		foreach ($postdata as $data){
		$data = protector($data);
		}
		switch($postdata["Method"]){
			case 'Table':
				if (isset($postdata["table"])){
					// Ищем по ID только если был получен ID в POST запросе
					if (isset($postdata["id"])) $addon = " WHERE ID = ".$postdata["id"];
					else $addon = "";
					switch($postdata["table"]){
						case "News":
							$query = "SELECT * FROM News".$addon;
						break;
						case "Session":
							$query = "SELECT * FROM Session".$addon;
							// Так как нужно вернуть список докладчиков, используем добавленную связывающую таблицу и вложенные запросы.
							$subquery = 'SELECT * FROM Speaker WHERE ID IN (SELECT SpeakerID FROM SessionSpeaker WHERE SessionID IN (SELECT ID FROM Session WHERE ID = ';
						break;
					}
					$data = getAssocArray($query, $link);
					// Если нужно вывести Session таблицу, присоединяем к ней докладчиков из таблицы Speakers
					if ($postdata["table"] == 'Session'){
						for ($i = 0; $i < count($data); $i++){
							$speakers = getAssocArray($subquery.$data[$i]['ID'].'))', $link);
							$data[$i]["Speakers"] = $speakers;
						}
					}
					if (count($data) === 0) $message = "Данные отсутствуют";
					else $message = "";
					echo buildDataArray("ok", $data, $message);
				}
				else {
					echo buildDataArray("error", array(), "Недостаточно параметров в запросе");
				}
			break;
			case 'SessionSubscribe':
				if (count($postdata) == SUBSCRIBE_METHOD_SIZE){
					$query = "SELECT * FROM SessionParticipant WHERE SessionID = ".$postdata["sessionId"];
					if (getRowNumbers($query, $link) > MAX_PARTICIPANTS){
						echo buildDataArray("ok", array(), "Извините, все места заняты");
					} else {
						$query = "SELECT ID FROM Participant WHERE EMail = \"".$postdata["userEmail"]."\"";
						$id = getUniqueId($query, $link);
						// Если такого пользователя нет в системе, то высылаем соответствующее сообщение.
						if ($id != -1){
							$query = "SELECT ID FROM SessionParticipant WHERE SessionID = ".$postdata["sessionId"]." AND ParticipantID = $id";
							if (getRowNumbers($query, $link) == 1){
								echo buildDataArray("error", array(), "Вы уже записаны на данную сессию!");
							} else{
								$query = "INSERT INTO SessionParticipant (SessionID, ParticipantID) VALUES (".$postdata["sessionId"].", $id.)";
								$link->query($query);
								echo buildDataArray("ok", array(), "Спасибо, вы успешно записаны!");
							}
						} else {
							echo buildDataArray("error", array(), "Вы не можете быть записаны, потому что вас нет в списке");
						}
						
					}
				} else {
					echo buildDataArray("error", array(), "Недостаточно параметров в запросе");
				}
			break;
			case 'PostNews':
				if (count($postdata) == POST_NEWS_METHOD_TYPE){
					$query = "SELECT ID FROM Participant WHERE EMail = \"".$postdata["userEmail"]."\"";
					$id = getUniqueId($query, $link);
					if ($id != -1){
						$query = "SELECT ID FROM News WHERE NewsTitle = \"".$postdata["newsTitle"]."\" AND NewsMessage = \"".$postdata["newsMessage"]."\" AND ParticipantId = $id";
						if (getRowNumbers($query, $link) == 0){
							$query = "INSERT INTO News (ParticipantId, NewsTitle, NewsMessage, LikesCounter) VALUES ($id, \"".$postdata["newsTitle"]."\", \"".$postdata["newsMessage"]."\", 0)";
							$link->query($query);
							echo buildDataArray("ok", array(), "Спасибо, ваша новость сохранена!");
						} else {
							echo buildDataArray("error", array(), "Дупликат новости недопустим!");
						}
					} else {
						echo buildDataArray("error", array(), "Участник с таким EMail не найден!");
					}
				} else {
					echo buildDataArray("error", array(), "Недостаточно параметров в запросе");
				}
			break;
			default:
				echo buildDataArray("error", array(), "Неизвестный метод!");
			break;
		}
		mysqli_close($link);
	} else {
		echo buildDataArray("error", array(), "Пустой POST запрос");
	}
	
}
?>