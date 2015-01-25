<pre><h3>Fichier <code>modules\RsnTODO\models\Record.php</code></h3>
class RsnTODO_Record_Model extends Vtiger_Record_Model {
	
	/**
	 * Function to set the entity instance of the record
	 * @param CRMEntity $entity
	 * @return Vtiger_Record_Model instance
	 *
	 * ED141004
	 * Affectation des valeurs par défaut avant l'affichage d'un nouvel enregistrement
	 */
	public function setEntity($entity) {
		parent::setEntity($entity);
		
		/* nouvel enregistrement */
		if(empty($this->get('id'))){
			/* valeur par défaut du champ create_user_id */
			global $current_user;
			$this->set('create_user_id', $current_user->id);
		}
		
		return $this;
	}
	
	/**
	 * Function to save the current Record Model
	 * ED141004
	 * Contrôle des valeurs de champs à l'enregistrement
	 */
	public function save() {
		/* sécurité en double */
		if(empty($this->get('create_user_id'))){
			global $current_user;
			$this->set('create_user_id', $current_user->id);
		}
		return parent::save();
	}
}


</pre>