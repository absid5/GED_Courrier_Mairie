<?php 

/**
 * 
 *
 * @author 	FTafel
 */
class TafelTreeBranch {
	
	
	/**
	 *------------------------------------------------------------------------------
	 *							Propri�t�s
	 *------------------------------------------------------------------------------
	 */
	
	/**
	 * @access 	public
	 * @var 	string			$id						L'id de la branche
	 */
	public $id;
	
	/**
	 * @access 	public
	 * @var 	string			$txt					Le texte de la branche
	 */
	public $txt;
	
	
	/**
	 *------------------------------------------------------------------------------
	 *							Constructeur
	 *------------------------------------------------------------------------------
	 */
	
	/**
	 * Constructeur
	 *
	 * @access	public
	 */
	public function __construct(){
	}
	
	/**
	 * Load les infos depuis une string JSON
	 *
	 * @access	public
	 * @param 	string			$json					La string JSON
	 * @return 	array									Les TafelTreeBranch cr��es
	 */
	public function loadJSON ($json) {
		$service = new Services_JSON();
		$obj = $service->decode($json);
		$branches = array();
		foreach ($obj as $s) {
			$branches[] = TafelTreeBranch::loadServiceJSON($s);
		}
		return $branches;
	}
	
	/**
	 * Load les infos depuis un objet Service_JSON
	 *
	 * @access	public
	 * @param 	Service_JSON	$service				L'objet Service_JSON
	 * @return 	array									Les TafelTreeBranch cr��es
	 */
	public function loadServiceJSON ($service) {
		$branch = new TafelTreeBranch();
		// On check toutes les propri�t�s de branche
		foreach ($service as $property => $value) {
			if ($property != 'items') {
				$branch->setParam($property, $value);
			}
		}
		// On check les enfants
		if (isset($service) && isset($service->items)) {
			$branch->items = array();
			foreach ($service->items as $b) {
				$branch->items[] = TafelTreeBranch::loadServiceJSON($b);
			}
		}
		return $branch;
	}
	
	
	
	/**
	 *------------------------------------------------------------------------------
	 *							Fonctions getters et setters
	 *------------------------------------------------------------------------------
	 */
	
	public function getId () {return $this->id;}
	public function setId ($id) {$this->id = $id;}
	
	public function getText () {return $this->txt;}
	public function setText ($txt) {$this->txt = $txt;}
	
	/**
	 * Retourne la valeur d'une propri�t�, si elle existe
	 *
	 * @access 	public
	 * @param 	string			$param					La propri�t� � chercher
	 * @return 	string									La valeur de la propri�t�
	 */
	public function getParam ($param) {
		if (isset($this->{$param})) {
			return $this->{$param};
		}
	}
	
	/**
	 * Set un param�tre pour la branche
	 *
	 * @access 	public
	 * @param 	string			$param					Le nom de la propri�t�
	 * @param 	string			$value					La valeur de la propri�t�
	 */
	public function setParam ($param, $value) {
		if ($param == 'id') {
			$this->setId($value);
		} elseif ($param == 'txt') {
			$this->setText($value);
		} else {
			$this->{$param} = $value;
		}
	}
	
	
	/**
	 *------------------------------------------------------------------------------
	 *							Fonctions publiques
	 *------------------------------------------------------------------------------
	 */
	
	/**
	 * Ajoute une branche comme enfant
	 *
	 * @access 	public
	 * @param 	TafelTreeBranch		$branch				La branche � ajouter
	 * @return 	void
	 */
	public function add (TafelTreeBranch $branch) {
		if (!isset($this->items)) {
			$this->items = array();
		}
		$this->items[] = $branch;
	}
	
	/**
	 * Ajoute une sous-branche � la branche courante
	 *
	 * @access 	public
	 * @param 	string			$id						L'id de la sous-branche
	 * @param 	string			$txt					Le texte de la sous-branche
	 * @param 	array			$options				Les informations compl�mentaires
	 * @return 	TafelTreeBranch							La sous-branche
	 */
	public function addBranch ($id, $txt, $options = array()) {
		$branch = new TafelTreeBranch ();
		$branch->setId($id);
		$branch->setText($txt);
		foreach ($options as $property => $value) {
			if ($property != 'items') {
				$branch->setParam($property, $value);
			}
		}
		if (isset($options['items'])) {
			foreach ($options['items'] as $opt) {
				$branch->addBranch(null, null, $opt);
			}
		}
		if (!isset($this->items)) {
			$this->items = array();
		}
		$this->items[] = $branch;
		return $branch;
	}
	
	/**
	 * Retourne la string JSON qui correspond � la structure de la branche et sous-branches
	 *
	 * @access 	public
	 * @return 	string									La string JSON de la branche
	 */
	public function getJSON () {
		$service = new Services_JSON();
		return $service->encode($this);
	}
	
	
}

?>