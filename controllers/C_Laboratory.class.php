<?php

require_once($GLOBALS['fileroot'] . "/library/classes/Controller.class.php");
require_once($GLOBALS['fileroot'] . "/library/classes/Laboratory.class.php");
require_once($GLOBALS['fileroot'] . "/library/classes/WSWrapper.class.php");

class C_Laboratory extends Controller {

	var $template_mod;
	var $ilaboratories;

	function C_Laboratory($template_mod = "general") {
		parent::Controller();
		$this->ilaboratories = array();
		$this->template_mod = $template_mod;
		$this->assign("FORM_ACTION", $GLOBALS['webroot']."/controller.php?" . $_SERVER['QUERY_STRING']);
		$this->assign("CURRENT_ACTION", $GLOBALS['webroot']."/controller.php?" . "practice_settings&laboratory&");
		$this->assign("STYLE", $GLOBALS['style']);
		$this->assign("WEB_ROOT", $GLOBALS['webroot'] );		
	}

	function default_action() {
		return $this->list_action();
	}

	function edit_action($id = "",$patient_id="",$p_obj = null) {
		if ($p_obj != null && get_class($p_obj) == "laboratory") {
			$this->ilaboratories[0] = $p_obj;
		}
		elseif (get_class($this->ilaboratories[0]) != "laboratory" ) {
			$this->ilaboratories[0] = new Laboratory($id);
		}

		$this->assign("laboratory", $this->ilaboratories[0]);
		return $this->fetch($GLOBALS['template_dir'] . "laboratories/" . $this->template_mod . "_edit.html");
	}

	function list_action($sort = "") {

		if (!empty($sort)) {
			$this->assign("ilaboratories", Laboratory::laboratories_factory("",$sort));
		}
		else {
			$this->assign("ilaboratories", Laboratory::laboratories_factory());
		}

		return $this->fetch($GLOBALS['template_dir'] . "laboratories/" . $this->template_mod . "_list.html");
	}


	function edit_action_process() {
		if ($_POST['process'] != "true")
			return;
		if (is_numeric($_POST['id'])) {
			$this->ilaboratories[0] = new Laboratory($_POST['id']);
		}
		else {
			$this->ilaboratories[0] = new Laboratory();
		}

  		parent::populate_object($this->ilaboratories[0]);

		$this->ilaboratories[0]->persist();
		$this->ilaboratories[0]->populate();

		$_POST['process'] = "";
	}

}

?>
