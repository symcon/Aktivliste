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

		//Creating links for all variable IDs in VariableList
		foreach ($variableList as $line) {
			$variableID = $line["VariableID"];
			$this->RegisterMessage($variableID, VM_UPDATE);
			if (!@$this->GetIDForIdent($variableID)) {

				//Create links for variables
				$linkID = IPS_CreateLink();
				IPS_SetName($linkID, IPS_GetName($variableID));
				IPS_SetParent($linkID, $this->InstanceID);
				IPS_SetLinkTargetID($linkID, $variableID);
				IPS_SetIdent($linkID, $variableID);

				//Setting initial visibility
				IPS_SetHidden($linkID, !(GetValue($variableID) ^ $this->IsProfileInverted($variableID)));
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
			$link = $this->GetIDForIdent($SenderID);
			IPS_SetHidden($link, !($Data[0] ^ $this->IsProfileInverted($SenderID)));
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

					$value = GetValue($targetID) ^ $this->IsProfileInverted($targetID);
					if (($actionID >= 10000) && $value) {
						RequestAction($targetID, !GetValue($targetID));
					}
				}
			}	
		}
	}


	public function IsProfileInverted($VariableID)
	{
		$variableProfileName = IPS_GetVariable($VariableID)["VariableCustomProfile"];
		if($variableProfileName == "") {
			$variableProfileName = IPS_GetVariable($VariableID)["VariableProfile"];
		}
		return substr($variableProfileName, -strlen(".Reversed")) === ".Reversed";
	}

}