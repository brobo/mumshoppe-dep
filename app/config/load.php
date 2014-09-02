<?php

// TODO: Unfortunately, this is not atomic. That could be problematic.
class VariableConfig {
	const CONFIG_FILE = "config.json";
	
	private $loaded;
	
	private $vars = [];
	
	public function __construct() {
		$loaded = false;
	}
	
	public function get($name) {
		if(!$this->loaded)
			$this->load();
		
		return $this->vars[$name];
	}
	
	public function set($name, $val) {
		if(!$this->loaded)
			$this->load();
		
		$this->vars[$name] = $val;
	}
	
	public function save() {
		file_put_contents(static::CONFIG_FILE, json_encode($this->vars));
	}
	
	public function load() {
		if(file_exists(static::CONFIG_FILE)) {
			$this->vars = json_decode(file_get_contents(static::CONFIG_FILE));
		}
		$this->loaded = true;
	}
}

$app->persisted = new VariableConfig();

?>