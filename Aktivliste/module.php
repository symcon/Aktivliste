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
		$this->RegisterScript("TurnOff", $this->Translate("Turn Off"), "<?php\n\nAL_SwitchOff(IPS_GetParent(\$_IPS['SELF']));");
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

		//Creating links for all variable IDs in VariableList
		foreach ($variableList as $line) {
			$variableID = $line["VariableID"];
			$this->RegisterMessage($variableID, VM_UPDATE);
			if (!@$this->GetIDForIdent("Link" . $variableID)) {

				//Create links for variables
				$linkID = IPS_CreateLink();
				IPS_SetParent($linkID, $this->InstanceID);
				IPS_SetLinkTargetID($linkID, $variableID);
				IPS_SetIdent($linkID, "Link" . $variableID);

				//Setting initial visibility
				IPS_SetHidden($linkID, (GetValue($variableID) == $this->GetSwitchValue($variableID)));
			}
		}

		//Deleting unlisted links
		foreach (IPS_GetChildrenIDs($this->InstanceID) as $linkID) {
			if (IPS_LinkExists($linkID)) {
				if (!in_array(IPS_GetLink($linkID)["TargetID"], $variableIDs)) {
					$this->UnregisterMessage(IPS_GetLink($linkID)["TargetID"], VM_UPDATE);
					IPS_DeleteLink($linkID);
				}
			}
		}
	}


	public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
	{
		if ($Message == VM_UPDATE) {
			$linkID = $this->GetIDForIdent("Link" . $SenderID);
			IPS_SetHidden($linkID, $Data[0] == $this->GetSwitchValue($SenderID));
		}
	}

	public function SwitchOff()
	{
		foreach (IPS_GetChildrenIDs($this->InstanceID) as $linkID) {
			//Only links
			if (IPS_LinkExists($linkID)) {
				$targetID = IPS_GetLink($linkID)["TargetID"];

				if (IPS_VariableExists($targetID)) {

					$v = IPS_GetVariable($targetID);

					if ($v['VariableCustomAction'] > 0) {
						$actionID = $v['VariableCustomAction'];
					} else {
						$actionID = $v['VariableAction'];
					}
					if (($actionID >= 10000) && !(GetValue($targetID) == $this->GetSwitchValue($targetID))) {
						RequestAction($targetID, $this->GetSwitchValue($targetID));
					}
				}
			}	
		}
	}

	private function GetSwitchValue($VariableID)
	{
		switch(IPS_GetVariable($VariableID)["VariableType"]) {
			case 0:
				return($this->IsProfileInverted($VariableID));

			case 1:
			case 2:
				if (IPS_VariableProfileExists($this->GetVariableProfile($VariableID))) {
					if ($this->IsProfileInverted($VariableID)) {
						return(IPS_GetVariableProfile($this->GetVariableProfile($VariableID))["MaxValue"]);
					} else {
						return(IPS_GetVariableProfile($this->GetVariableProfile($VariableID))["MinValue"]);
					}
				} else {
					return(0);
				}
				
			
			case 3:
				return("");
		}
	}

	private function GetVariableProfile($VariableID)
	{
		$variableProfileName = IPS_GetVariable($VariableID)["VariableCustomProfile"];
		if($variableProfileName == "") {
			$variableProfileName = IPS_GetVariable($VariableID)["VariableProfile"];
		}
		return($variableProfileName);
	}	

	private function IsProfileInverted($VariableID)
	{
		
		return substr($this->GetVariableProfile($VariableID), -strlen(".Reversed")) === ".Reversed";
	}

}