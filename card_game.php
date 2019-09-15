<?php
header("Access-Control-Allow-Origin: *");
header('Content-type:application/json;charset=utf-8');

$json = file_get_contents('php://input');
$data = json_decode($json, TRUE);

if (isset($data['no_of_players'])) {
	if ($data['no_of_players'] < 1) {
		$response = json_encode([
			'success' => false,
			'message' => 'Player must not less than 0'
		], true);

		echo $response;
		exit;
	}
	//suit of cards
	$suit = array("2", "3", "4", "5", "6", "7", "8", "9", "X", "J", "Q", "K", "A");
	//strength of cards
	$strength = array("C-", "D-", "H-", "S-");

	//inlcude class and create card deck
	include("./card_deck.php");
	$deck = new card_deck();

	//add type with strength property and values from array
	//and get id of type
	$id = $deck->add_type("strength", $strength);

	//add suit property to same type by providing id
	$deck->add_type("suit", $suit, 1, $id);

	//shuffle cards
	$deck->shuffle();


	//distribute cards for N people, N cards for each
	$number_of_player = $data['no_of_players'];
	$number_of_distribute_cards = floor(52 / $number_of_player);
	if ($number_of_distribute_cards < 1) {
		$number_of_distribute_cards = 1;
	}
	$serial_count = 1;
	$newarr = array();
	for ($x = 1; $x <= $number_of_player; $x++) {
		$arr = $deck->deal($number_of_distribute_cards);

		foreach ($arr as $key => $val) {
			$arr[$key] = implode("", $val);
		}
		array_push($newarr, $arr);
	}
	$extra_card_arr = $deck->deal($number_of_player);


	if ($extra_card_arr) {
		foreach ($extra_card_arr as $key => $val) {
			$str = implode(",", $val);
			$str = str_replace(',', '', $str);
			array_push($newarr[$key], $str);
		}
	}

	$results = array();
	foreach ($newarr as $key => $val) {
		$results[] = [
			'player' => $serial_count,
			'hand' => $val
		];
		$serial_count++;
	}

	$response = json_encode([
		'success' => true,
		'message' => 'Cards distributed to ' . ($serial_count - 1) . ' people',
		'data' => $results
	], true);

	echo $response;
}
