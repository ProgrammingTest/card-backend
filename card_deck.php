<?php
class card_deck
{
	private $types = array();
	private $deck = array();
	private $count = array();
	
	public function add_type($property_name, $type_names, $count = 1, $id = -1){
		$arr = array();
		$cnt = ($id < 0) ? sizeof($this->types): $id;
		foreach($type_names as $key => $value)
		{
			$arr[] = $value;
		}
		if(!isset($this->count[$cnt]))
		{
			$this->count[$cnt] = 1;
		}
		$this->count[$cnt] *= sizeof($arr);
		$this->count[$cnt] *= $count;
		$this->types[$cnt][$property_name] = $arr;
		$this->deck = range(0, array_sum($this->count) - 1);
		return $cnt;
	}
	
	public function shuffle($reset = true){
		if($reset)
		{
			$this->deck = range(0, array_sum($this->count) - 1);
		}
		shuffle($this->deck);
	}

	public function getCount(){
		return $this->count;
	}
	
	public function deal($count){
		$arr = array();
		for($i = 1; $i <= $count; $i++)
		{
			if(!empty($this->deck))
			{
				$cnt = sizeof($arr);
				$card = array_shift($this->deck);
				$sub = 0;
				foreach($this->types as $card_type => $value)
				{
					if($card < $this->count[$card_type] + $sub)
					{
						$mod = 1;
						foreach($value as $key => $val)
						{
							$arr[$cnt][$key] = $val[round(($card - $sub)/$mod) % sizeof($val)];
							$mod *= sizeof($val);
						}
						break;
					}
					else
					{
						$sub += $this->count[$card_type];
					}
				}
			}
			else
			{
				break;
			}
		}
		return $arr;
	}
}
?>