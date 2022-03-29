<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class flipr_hub extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */

    
    // Fonction exécutée automatiquement toutes les heures par Jeedom
    public static function cronHourly() {
        // Pour tous les objets flipr_hub
        foreach(self::byType('flipr_hub') as $flipr_hub){
            // Si l'objet est actif
            if ($flipr_hub->getIsEnable() == 1) {
                // On lance la commande Refresh
                $cmd = $flipr_hub->getCmd(null, 'refresh');
                if (!is_object($cmd)) {
                    continue; 
                }
                $cmd->execCmd();
            }
        }
    }
     
    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        // Création des différentes infos que remonte flipr_hub
       $info = $this->getCmd(null, 'lastMesure');
		if (!is_object($info)) {
			$info = new fliprCmd();
			$info->setName(__('Dernier Relevé', __FILE__));
		}
		$info->setLogicalId('lastMesure');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
        $info->setSubType('string');
        $info->setIsVisible(true);
 		$info->save();	
        $info = $this->getCmd(null, 'Etat');
		if (!is_object($info)) {
			$info = new flipr_hubCmd();
			$info->setName(__('Etat', __FILE__));
		}
		$info->setLogicalId('Etat');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
        $info->setSubType('other');
        $info->setIsVisible(true);
        $info->setIsHistorized(true);
		$info->save();	

        $info = $this->getCmd(null, 'Mode');
		if (!is_object($info)) {
			$info = new flipr_hubCmd();
			$info->setName(__('Mode', __FILE__));
		}
		$info->setLogicalId('Mode');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
        $info->setSubType('string');
        $info->setIsVisible(true);
		$info->save();	
/*
       $info = $this->getCmd(null, 'messageModeAutoFiltration');
		if (!is_object($info)) {
			$info = new flipr_hubCmd();
			$info->setName(__('Filtration', __FILE__));
		}
		$info->setLogicalId('messageModeAutoFiltration');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
        $info->setSubType('string');
        $info->setIsVisible(true);
		$info->save();	
*/
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new flipr_hubCmd();
			$refresh->setName(__('Rafraichir', __FILE__));
		}
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save(); 
      
       $action = $this->getCmd(null, 'mode_manuel');
		if (!is_object($action)) {
			$action = new flipr_hubCmd();
			$action->setName(__('Manuel', __FILE__));
		}
		$action->setLogicalId('mode_manuel');
		$action->setEqLogic_id($this->getId());
		$action->setType('action');
        $action->setSubType('other');
        $action->setIsVisible(true);
 		$action->save();

      $action = $this->getCmd(null, 'mode_auto');
		if (!is_object($action)) {
			$action = new flipr_hubCmd();
			$action->setName(__('Auto', __FILE__));
		}
		$action->setLogicalId('mode_auto');
		$action->setEqLogic_id($this->getId());
		$action->setType('action');
        $action->setSubType('other');
        $action->setIsVisible(true);
 		$action->save();	
      
      $action = $this->getCmd(null, 'mode_planning');
		if (!is_object($action)) {
			$action = new flipr_hubCmd();
			$action->setName(__('Planning', __FILE__));
		}
		$action->setLogicalId('mode_planning');
		$action->setEqLogic_id($this->getId());
		$action->setType('action');
        $action->setSubType('other');
        $action->setIsVisible(true);
 		$action->save();	     
        // Lancement d'un refresh pour récupérer un premier jeu de valeurs
        if ($this->getIsEnable() == 1) {
            $cmd = $this->getCmd(null, 'refresh');
            if (is_object($cmd)) {
                $cmd->execCmd();
            }
        }
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
  
    // Fonction de lecture des infos flipr_hub
	public function getflipr_hubInfo() {
        // Lecture de la configuration du plugin pour optenir les identifiants de connexion
        $flipr_hubID = $this->getConfiguration('flipr_hubId'); 
        $flipr_hubUsername = trim(config::byKey('flipr_hubUsername', 'flipr_hub'));
        $flipr_hubPassword = trim(config::byKey('flipr_hubPassword', 'flipr_hub'));
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- OAuth = flipr_hubId : '.$flipr_hubID.' User : '.$flipr_hubUsername.' Password : '.substr($flipr_hubPassword, 0, 2).'*****');

        // Récupération du token d'authentification
        $url = curl_init();
        curl_setopt($url, CURLOPT_URL,"https://apis.goflipr.com/OAuth2/token");
        curl_setopt($url, CURLOPT_POST, 3); 
        curl_setopt($url, CURLOPT_POSTFIELDS,
            http_build_query(array(	'grant_type' => 'password',
                        'username'   => $flipr_hubUsername,
                        'password'	 => $flipr_hubPassword)));
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);		
        curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Cache-Control: no-cache'));

        $server_output = curl_exec($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- server_output : '.$server_output);
        $token = json_decode($server_output,true);
        
        if(curl_errno($url)){
            log::add('flipr_hub', 'error', 'Curl error : ' . curl_error($url));
            exit();
        }
        curl_close ($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- token : '.$token['access_token']);

        // Lecture du dernier relevé
        $url = curl_init();
        curl_setopt($url, CURLOPT_URL,"https://apis.goflipr.com/hub/".$flipr_hubID."/state");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);		
        curl_setopt($url, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'], 'Cache-Control: no-cache'));

        $server_output = curl_exec($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- server_output : '.$server_output);
        $result = json_decode($server_output,true);
        if(curl_errno($url)){
            log::add('flipr_hub', 'error', 'Curl error : ' . curl_error($url));
            exit();
        }
        
        curl_close ($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- return stateEquipment: '.$result['stateEquipment']);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- return behavior: '.$result['behavior']);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- return messageModeAutoFiltration: '.$result['messageModeAutoFiltration']);
        return($result);
    }
  public function setflipr_hubMode($mode) {
        // Lecture de la configuration du plugin pour optenir les identifiants de connexion
    	log::add('flipr_hub', 'debug', 'setflipr_hubMode -- Mode : '.$mode);
        $flipr_hubID = $this->getConfiguration('flipr_hubId'); 
        $flipr_hubUsername = trim(config::byKey('flipr_hubUsername', 'flipr_hub'));
        $flipr_hubPassword = trim(config::byKey('flipr_hubPassword', 'flipr_hub'));
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- OAuth = flipr_hubId : '.$flipr_hubID.' User : '.$flipr_hubUsername.' Password : '.substr($flipr_hubPassword, 0, 2).'*****');

        // Récupération du token d'authentification
        $url = curl_init();
        curl_setopt($url, CURLOPT_URL,"https://apis.goflipr.com/OAuth2/token");
        curl_setopt($url, CURLOPT_POST, 3); 
        curl_setopt($url, CURLOPT_POSTFIELDS,
            http_build_query(array(	'grant_type' => 'password',
                        'username'   => $flipr_hubUsername,
                        'password'	 => $flipr_hubPassword)));
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);		
        curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'Cache-Control: no-cache'));

        $server_output = curl_exec($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- server_output : '.$server_output);
        $token = json_decode($server_output,true);
        
        if(curl_errno($url)){
            log::add('flipr_hub', 'error', 'Curl error : ' . curl_error($url));
            exit();
        }
        curl_close ($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- token : '.$token['access_token']);

        // Lecture du dernier relevé
        $url = curl_init();
        curl_setopt($url, CURLOPT_URL,"https://apis.goflipr.com/hub/".$flipr_hubID."/mode/".$mode);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($url,CURLOPT_CUSTOMREQUEST, 'PUT' );
        curl_setopt($url, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token'], 'Cache-Control: no-cache'));

        $server_output = curl_exec($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- server_output : '.$server_output);
        $result = json_decode($server_output,true);
        if(curl_errno($url)){
            log::add('flipr_hub', 'error', 'Curl error : ' . curl_error($url));
            exit();
        }
        
        curl_close ($url);
        log::add('flipr_hub', 'debug', 'getflipr_hubInfo -- return behavior: '.$result['behavior']);
        return($result);
    }

}

class flipr_hubCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
 		$eqlogic = $this->getEqLogic(); 
		switch ($this->getLogicalId()) {				
			case 'refresh': 
				$flipr_hubInfo = $eqlogic->getflipr_hubInfo(); 	//Récupération des infos du dernier relevé
                // Mise à jour des infos de l'objet flipr_hub
                $date = new DateTime();
                $timeZone = $date->getTimezone();
                $date = new DateTime($flipr_hubInfo['DateTime']);
                $date->setTimezone($timeZone);
            	$eqlogic->checkAndUpdateCmd('lastMesure', $date->format('d/m/Y H:i:s'));
				$eqlogic->checkAndUpdateCmd('Etat', $flipr_hubInfo['stateEquipment']); 
				$eqlogic->checkAndUpdateCmd('Mode', $flipr_hubInfo['behavior']); 
            	#$eqlogic->checkAndUpdateCmd('messageModeAutoFiltration', $flipr_hubInfo['messageModeAutoFiltration']);
				break;
            case 'mode_manuel':
                $eqlogic->setflipr_hubMode("manual");
            	$flipr_hubInfo = $eqlogic->getflipr_hubInfo(); 	//Récupération des infos du dernier relevé
                // Mise à jour des infos de l'objet flipr_hub
                $date = new DateTime();
                $timeZone = $date->getTimezone();
                $date = new DateTime($flipr_hubInfo['DateTime']);
                $date->setTimezone($timeZone);
            	$eqlogic->checkAndUpdateCmd('lastMesure', $date->format('d/m/Y H:i:s'));
                $eqlogic->checkAndUpdateCmd('Etat', $flipr_hubInfo['stateEquipment']); 
				$eqlogic->checkAndUpdateCmd('Mode', $flipr_hubInfo['behavior']); 
            	#$eqlogic->checkAndUpdateCmd('messageModeAutoFiltration', $flipr_hubInfo['messageModeAutoFiltration']);
                break;
             case 'mode_auto':
                $eqlogic->setflipr_hubMode("auto");
                $flipr_hubInfo = $eqlogic->getflipr_hubInfo(); 	//Récupération des infos du dernier relevé
                // Mise à jour des infos de l'objet flipr_hub
                $date = new DateTime();
                $timeZone = $date->getTimezone();
                $date = new DateTime($flipr_hubInfo['DateTime']);
                $date->setTimezone($timeZone);
            	$eqlogic->checkAndUpdateCmd('lastMesure', $date->format('d/m/Y H:i:s'));
            	$eqlogic->checkAndUpdateCmd('Etat', $flipr_hubInfo['stateEquipment']); 
				$eqlogic->checkAndUpdateCmd('Mode', $flipr_hubInfo['behavior']); 
            	#$eqlogic->checkAndUpdateCmd('messageModeAutoFiltration', $flipr_hubInfo['messageModeAutoFiltration']);
                break;
            case 'mode_planning':
                $eqlogic->setflipr_hubMode("planning");
            	$flipr_hubInfo = $eqlogic->getflipr_hubInfo(); 	//Récupération des infos du dernier relevé
                // Mise à jour des infos de l'objet flipr_hub
                $date = new DateTime();
                $timeZone = $date->getTimezone();
                $date = new DateTime($flipr_hubInfo['DateTime']);
                $date->setTimezone($timeZone);
            	$eqlogic->checkAndUpdateCmd('lastMesure', $date->format('d/m/Y H:i:s'));
                $eqlogic->checkAndUpdateCmd('Etat', $flipr_hubInfo['stateEquipment']); 
				$eqlogic->checkAndUpdateCmd('Mode', $flipr_hubInfo['behavior']); 
            	#$eqlogic->checkAndUpdateCmd('messageModeAutoFiltration', $flipr_hubInfo['messageModeAutoFiltration']);
                break;
            	
		}
       
    }

    /*     * **********************Getteur Setteur*************************** */
}