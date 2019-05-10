<?php
header("Content-Type: text/html; charset=utf-8");
date_default_timezone_set("UTC");


if (empty($_POST)) die(json_encode( array("error" => "hack") )); // если не пост то выход


$token = fix_text($_POST['token']);

if ($token != 'token--token') die(json_encode( array("error" => "token") ));
// првоеряем токен и другие данные

$post_type = fix_text($_POST['type']); // тип запроса

// вызываем функцию исходя из типа
if ($post_type == 'stats') {
	if ($result_stats = write_info_stats($_POST)) {
		if ($result_stats === true) $result_stats = "";
		die(json_encode( array("status" => "OK", "info" => "stats", "gets" => $result_stats) ));
	}
	die(json_encode( array("error" => "ERR stats") ));
} else if ($post_type == 'users') {
	if (write_info_users($_POST)) die(json_encode( array("status" => "OK", "info" => "users") ));
	die(json_encode( array("error" => "ERR users") ));
} else if ($post_type == 'guilds') {
	if (write_info_guilds($_POST)) die(json_encode( array("status" => "OK", "info" => "guilds") ));
	die(json_encode( array("error" => "ERR guilds") ));
} else if ($post_type == 'settings') {
	if (get_info_settings()) die();
	die(json_encode( array("error" => "ERR settings") ));
} else {
	die(json_encode( array("error" => "type") ));
}








function connect_db_stats() {
	try {
		//return new PDO(тут типо подключение);
	} catch (PDOException $e) {
		die(json_encode( array("error" => "connect db stats") ));
	}
}
function connect_db_users() {
	try {
		//return new PDO(тут типо подключение);
	} catch (PDOException $e) {
		die(json_encode( array("error" => "connect db users") ));
	}
}
function connect_db_guilds() {
	try {
		//return new PDO(тут типо подключение);
	} catch (PDOException $e) {
		die(json_encode( array("error" => "connect db guilds") ));
	}
}
function connect_db_settings() {
	try {
		//return new PDO(тут типо подключение);
	} catch (PDOException $e) {
		die(json_encode( array("error" => "connect db settings") ));
	}
}



/* create_table_stats - создает таблицу в бд
 * @new_db_name - имя таблицы
*/
function create_table_stats($new_db_name) {
	try {
		$pdo = connect_db_stats();

		$query = "CREATE TABLE $new_db_name (
		-- date_s TIMESTAMP default CURRENT_TIMESTAMP,
		date_s DATETIME,
		count INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		online VARCHAR(10),
		game_name VARCHAR(255),
		game_type TINYINT(1),
		guilds_id TEXT(9999),
		nickname TEXT(9999),
		channel_id VARCHAR(255),
		channel_name VARCHAR(255),
		mess TEXT(9999)
		)";

		// fetch() fetchAll()
		// use exec() because no results are returned
		$answer = $pdo->exec($query);
		$pdo->connection = null; // закрываем соединение
		return true;
	} catch(PDOException $e) {
		//echo $e->getMessage();
		//echo $query;
		return false;
	}
}
//die( create_table_stats("test") );


function create_table_users($new_db_name) {
	try {
		$pdo = connect_db_users();

		$query = "CREATE TABLE $new_db_name (
		date_s DATETIME,
		count INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(255),
		discriminator INT(4),
		avatar VARCHAR(50),
		createdAt VARCHAR(25)
		)";

		$answer = $pdo->exec($query);
		$pdo->connection = null; // закрываем соединение
		return true;
	} catch(PDOException $e) {
		//echo $e->getMessage();
		return false;
	}
}


function create_table_guilds($new_db_name) {
	try {
		$pdo = connect_db_guilds();

		$query = "CREATE TABLE $new_db_name (
		date_s DATETIME,
		count INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255),
		ownerID VARCHAR(30),
		icon VARCHAR(50),
		createdAt VARCHAR(25)
		)";

		$answer = $pdo->exec($query);
		$pdo->connection = null; // закрываем соединение
		return true;
	} catch(PDOException $e) {
		//echo $e->getMessage();
		return false;
	}
}


function check_table($name, $pdo) { // возвращает true если таблица есть
	$name_str = strval($name);
	try {
		//$pdo = func_bd_connect();
		$query = "SELECT * FROM $name_str WHERE count = 0";
		$answer = $pdo->exec($query);
		$pdo->connection = null; // закрываем соединение
		return ($answer == 0) ? true : false;
	} catch(PDOException $e) {
		//echo $e->getMessage();
		return false;
	}
}
//echo check_table('site_chat');


function fix_text($text) {
	return preg_replace('/[^ \~\#\№\`\|\\\!\@\$\%\^\&\*\(\)\_\-\+\=\}\]\[\{\;\:\;\'\/\?\.\>\,\<a-zа-яё\d]/ui', '',addcslashes(htmlspecialchars($text, ENT_QUOTES), "\\"));
}


function write_info_stats($data) { // записываем инфу в бд
	$date_s = date("Y-m-d H:i:s");
	$need_gets_users = array(); // список id которые нужно запросить на запись
	for ($i = 0; $i < $data['count']; $i++) { // пребираем всех пользователей
		$db_name = "u" . $data['users_list'][$i]; // записываем имя бд в переменную для удобства

		if (!check_table($db_name, connect_db_users())) { // проверяем есть ли user в BD users
			$need_gets_users[] = $data['users_list'][$i]; // добавляем в список
			if (!create_table_users($db_name)) die('err table create'); //если таблица не создана
		}

		if (!check_table($db_name, connect_db_stats())) { // если таблица не создана, то создаем
			if (!create_table_stats($db_name)) die('err table create'); //если таблица не создана
		}
		// таблица уже была создана либо мы ее создали...
		try {
			$pdo = connect_db_stats();
			$di = $data[$data['users_list'][$i]]; // data info

			$online = fix_text($di['online']);
			$game_name = base64_encode($di['game_name']);
			$game_type = fix_text($di['game_type']);
			if (empty($game_type) && empty($di['game_name'])) $game_type = 9;
			$guilds_id = fix_text(implode(",", $di['guilds_id']));
			$nickname = base64_encode(implode(",", $di['nickname']));
			$channel_id = fix_text(implode(",", $di['channel_id']));
			if ( empty($di['channel_name']) ) {
				$channel_name = '';
			} else {
				$channel_name = base64_encode(implode(",", $di['channel_name']));
			}
			if (empty($di['mess'])) {
				$mess = "";
			} else {
				$mess = base64_encode(json_encode($di['mess']));
			}
			// важно помнить что при экспорте с БД там может получиться SQL иньекция !

			$query = "INSERT INTO $db_name (date_s, online, game_name, game_type, guilds_id, nickname, channel_id, channel_name, mess) VALUES (\"$date_s\", \"$online\", \"$game_name\", \"$game_type\", \"$guilds_id\", \"$nickname\", \"$channel_id\", \"$channel_name\", \"$mess\")";

			$answer = $pdo->exec($query); // выполняем запрос
			$pdo->connection = null; // закрываем соединение
		} catch (PDOException $e) {
			//echo $e;
			return false;
		}
	}
	return empty($need_gets_users) ? true : $need_gets_users;
}


function write_info_users($data) { // записываем инфу в бд
	$date_s = date("Y-m-d H:i:s");
	for ($i = 0; $i < $data['count']; $i++) { // пребираем всех пользователей
		$db_name = "u" . $data['users_list'][$i]; // записываем имя бд в переменную для удобства

		if (!check_table($db_name, connect_db_users())) { // если таблица не создана, то создаем
			if (!create_table_users($db_name)) die('err table create'); //если таблица не создана
		}
		// таблица уже была создана либо мы ее создали...
		try {
			$pdo = connect_db_users();
			$di = $data[$data['users_list'][$i]]; // data info

			$username = base64_encode($di['username']);
			$discriminator = fix_text($di['discriminator']);
			$avatar = fix_text($di['avatar']);
			$createdAt = fix_text($di['createdAt']);
			// важно помнить что при экспорте с БД там может получиться SQL иньекция !

			$query = "INSERT INTO $db_name (date_s, username, discriminator, avatar, createdAt) VALUES (\"$date_s\", \"$username\", \"$discriminator\", \"$avatar\", \"$createdAt\")";

			$answer = $pdo->exec($query); // выполняем запрос
			$pdo->connection = null; // закрываем соединение
		} catch (PDOException $e) {
			//echo $e;
			return false;
		}
	}
	return true;
}


function write_info_guilds($data) { // записываем инфу в бд
	$date_s = date("Y-m-d H:i:s");
	for ($i = 0; $i < $data['count']; $i++) { // пребираем всех пользователей
		$db_name = "u" . $data['guilds_list'][$i]; // записываем имя бд в переменную для удобства

		if (!check_table($db_name, connect_db_guilds())) { // если таблица не создана, то создаем
			if (!create_table_guilds($db_name)) die('err table create'); //если таблица не создана
		}
		// таблица уже была создана либо мы ее создали...
		try {
			$pdo = connect_db_guilds();
			$di = $data[$data['guilds_list'][$i]]; // data info

			$name = base64_encode($di['name']);
			$icon = fix_text($di['icon']);
			$ownerID = fix_text($di['ownerID']);
			$createdAt = fix_text($di['createdAt']);
			// важно помнить что при экспорте с БД там может получиться SQL иньекция !

			$query = "INSERT INTO $db_name (date_s, name, icon, ownerID, createdAt) VALUES (\"$date_s\", \"$name\", \"$icon\", \"$ownerID\", \"$createdAt\")";

			$answer = $pdo->exec($query); // выполняем запрос
			$pdo->connection = null; // закрываем соединение
		} catch (PDOException $e) {
			//echo $e;
			//echo "\n";
			//echo $query;
			return false;
		}
	}
	return true;
}



function get_info_settings() { // получает информацию о настройках сервера
	try {
		$pdo = connect_db_settings();
		$query = "SELECT `guild_id`, `value` FROM `guilds_track` WHERE 1";
		$answer = $pdo->query($query); // выполняем запрос

		$ans = array("status" => "OK", "guildsTrack" => array(), "admins" => array());
		while ($row = $answer->fetch()) {
			$item = $row['guild_id'];
			$ans["guildsTrack"]["$item"] = $row['value'];
		}


		$query = "SELECT `id`, `type`, `guilds` FROM `admins` WHERE 1";
		$answer = $pdo->query($query); // выполняем запрос

		while ($row = $answer->fetch()) {
			$item = $row['id'];
			$ans["admins"]["$item"] = array("type" => $row['type'], "guilds" => $row['guilds']);
		}

		$pdo->connection = null; // закрываем соединение
		echo json_encode($ans);
		return true;
	} catch (PDOException $e) {
		return false;
	}
}


