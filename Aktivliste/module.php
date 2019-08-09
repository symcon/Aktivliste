<?php

class Aktivliste extends IPSModule
{

	public function Create()
	{
		//Never delete this line!
		parent::Create();

		//Properties
		$this->RegisterPropertyString("VariableList", "[]");

		//Scripts
		$this->RegisterScript("TurnOff", $this->Translate("Turn Off"), "<?php\n\nOOA_SwitchOff(IPS_GetParent(\$_IPS['SELF']));");
		
	}

	public function Destroy()
	{
		//Never delete this line!
		parent::Destroy();
	}

	public function ApplyChanges()
	{
		//Never delete this line!
		parent::ApplyChanges();

		//Creating array containing variable IDs in List
		$variableIDs = [];
		$variableList = json_decode($this->ReadPropertyString("VariableList"), true);
		foreach ($variableList as $line) {
			$variableIDs[] = $line["VariableID"];
		}
		
		//Create array containing target IDs of links
		$targetedVars = [];
		$links = IPS_GetChildrenIDs($this->InstanceID);		
		foreach ($links as $link) {
			if (IPS_LinkExists($link)) {
				$targetedVars[] = IPS_GetLink($link)["TargetID"];
			}
		}

		//Creating links for all variable IDs in VariableList
		foreach ($variableList as $line) {
			$variableID = $line["VariableID"];
			$this->RegisterMessage($variableID, VM_UPDATE);
			if (!in_array($variableID, $targetedVars)) {

				//Create links for variables
				$linkID = IPS_CreateLink();				
				IPS_SetName($linkID, IPS_GetName($variableID));
				IPS_SetParent($linkID, $this->InstanceID);
				IPS_SetLinkTargetID($linkID, $variableID);
				
				//Setting initial visibility
				IPS_SetHidden($linkID, !GetValue($variableID));
			}	
		}

		//Deleting unlisted links
		foreach ($links as $link) {
			if (IPS_LinkExists($link)) {
				if (!in_array(IPS_GetLink($link)["TargetID"], $variableIDs)) {
					$this->UnregisterMessage(IPS_GetLink($link)["TargetID"], VM_UPDATE);
					IPS_DeleteLink($link);
				}
			}
		}	
	}


	public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) 
	{	
		if ($Message == VM_UPDATE) {
			$links = IPS_GetChildrenIDs($this->InstanceID);
			foreach ($links as $link) {
				//Only links
				if (IPS_LinkExists($link)) {
					if(IPS_GetLink($link)["TargetID"] == $SenderID) {
						IPS_SetHidden($link, !$Data[0]);
						break;
					}
				}
			}		
		}
	}

	public function SwitchOff()
	{
		$links = IPS_GetChildrenIDs($this->InstanceID);
		foreach ($links as $link) {
			//Only links
			if (IPS_LinkExists($link)) {
				$targetID = IPS_GetLink($link)["TargetID"];
				
				if (IPS_VariableExists($targetID)) {

					$v = IPS_GetVariable($targetID);

					if ($v['VariableCustomAction'] > 0) {
						$actionID = $v['VariableCustomAction'];
					} else {
						$actionID = $v['VariableAction'];
					}
					
					if (($actionID >= 10000) && GetValue($targetID)) {
						RequestAction($targetID, false);
					}
				}
			}
		}
	}

}
