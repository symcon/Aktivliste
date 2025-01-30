<?php

declare(strict_types=1);

class ActiveList extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();

        //Properties
        $this->RegisterPropertyString('VariableList', '[]');
        $this->RegisterPropertyBoolean('TurnOffAction', true);
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
        $variableList = json_decode($this->ReadPropertyString('VariableList'), true);
        foreach ($variableList as $line) {
            $variableIDs[] = $line['VariableID'];
        }

        //Creating links for all variable IDs in VariableList
        foreach ($variableList as $line) {
            $variableID = $line['VariableID'];
            $this->RegisterMessage($variableID, VM_UPDATE);
            $this->RegisterReference($variableID);
            if (!@$this->GetIDForIdent('Link' . $variableID)) {

                //Create links for variables
                $linkID = IPS_CreateLink();
                IPS_SetParent($linkID, $this->InstanceID);
                IPS_SetLinkTargetID($linkID, $variableID);
                IPS_SetIdent($linkID, 'Link' . $variableID);

                //Setting initial visibility
                IPS_SetHidden($linkID, (GetValue($variableID) == $this->GetSwitchValue($variableID)));
            }
        }

        //Deleting unlisted links
        foreach (IPS_GetChildrenIDs($this->InstanceID) as $linkID) {
            if (IPS_LinkExists($linkID)) {
                if (!in_array(IPS_GetLink($linkID)['TargetID'], $variableIDs)) {
                    $this->UnregisterMessage(IPS_GetLink($linkID)['TargetID'], VM_UPDATE);
                    $this->UnregisterReference(IPS_GetLink($linkID)['TargetID']);
                    $this->UnregisterReference($linkID);
                    IPS_DeleteLink($linkID);
                }
            }
        }

        //Script for turn off
        if ($this->ReadPropertyBoolean('TurnOffAction')) {
            $this->RegisterScript('TurnOff', $this->Translate('Turn Off'), "<?php\n\nAL_SwitchOff(IPS_GetParent(\$_IPS['SELF']));");
        } elseif (@$this->GetIDForIdent('TurnOff')) {
            IPS_DeleteScript($this->GetIDForIdent('TurnOff'), true);
        }
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        if ($Message == VM_UPDATE) {
            $linkID = $this->GetIDForIdent('Link' . $SenderID);
            IPS_SetHidden($linkID, $Data[0] == $this->GetSwitchValue($SenderID));
        }
    }

    public function SwitchOff()
    {
        foreach (IPS_GetChildrenIDs($this->InstanceID) as $linkID) {
            //Only links
            if (IPS_LinkExists($linkID)) {
                $targetID = IPS_GetLink($linkID)['TargetID'];

                if (IPS_VariableExists($targetID)) {
                    $v = IPS_GetVariable($targetID);

                    if ($v['VariableCustomAction'] > 0) {
                        $actionID = $v['VariableCustomAction'];
                    } else {
                        $actionID = $v['VariableAction'];
                    }
                    if (($actionID >= 10000) && GetValue($targetID) !== $this->GetSwitchValue($targetID)) {
                        RequestAction($targetID, $this->GetSwitchValue($targetID));
                    }
                }
            }
        }
    }

    public function UpdateLinkNames($Type)
    {
        $variableList = json_decode($this->ReadPropertyString('VariableList'), true);
        foreach ($variableList as $line) {
            $variableID = $line['VariableID'];
            $linkID = @$this->GetIDForIdent('Link' . $variableID);
            if ($linkID) {
                $linkName = '';
                switch ($Type) {
                    case 0:
                        // Leave empty to inherit name from variableID
                        break;
                    case 1:
                        $linkName = IPS_GetName(IPS_GetParent($variableID));
                        break;
                    case 2:
                        $parent1 = IPS_GetParent($variableID);
                        $parent2 = IPS_GetParent($parent1);
                        $linkName = sprintf('%s (%s)', IPS_GetName($parent1), IPS_GetName($parent2));
                        break;
                    case 99:
                        $parent = IPS_GetParent($variableID);
                        $linkName = IPS_GetName($parent);
                        $location = [];
                        $parent = IPS_GetParent($parent);
                        while ($parent != 0) {
                            $location[] = IPS_GetName($parent);
                            $parent = IPS_GetParent($parent);
                        }
                        $linkName = sprintf('%s (%s)', $linkName, implode(', ', $location));
                        break;
                }
                if ($Type == 1) {
                    $linkName = IPS_GetName(IPS_GetParent($variableID));
                }
                IPS_SetName($linkID, $linkName);
            }
        }
    }

    private function GetSwitchValue($VariableID)
    {
        //Return the value corresponding to the variable type.
        switch (IPS_GetVariable($VariableID)['VariableType']) {
            //Boolean
            case 0:
                return $this->IsProfileInverted($VariableID);

                //Integer
            case 1:

                //Float
            case 2:
                if (IPS_VariableProfileExists($this->GetVariableProfile($VariableID))) {
                    if ($this->IsProfileInverted($VariableID)) {
                        return IPS_GetVariableProfile($this->GetVariableProfile($VariableID))['MaxValue'];
                    } else {
                        return IPS_GetVariableProfile($this->GetVariableProfile($VariableID))['MinValue'];
                    }
                } else {
                    return 0;
                }

                // no break
                //Integer
                // FIXME: No break. Please add proper comment if intentional
                // No break. Add additional comment above this line if intentional
            case 3:
                return '';

        }
    }

    private function GetVariableProfile($VariableID)
    {
        $variableProfileName = IPS_GetVariable($VariableID)['VariableCustomProfile'];
        if ($variableProfileName == '') {
            $variableProfileName = IPS_GetVariable($VariableID)['VariableProfile'];
        }
        return $variableProfileName;
    }

    private function IsProfileInverted($VariableID)
    {
        return substr($this->GetVariableProfile($VariableID), -strlen('.Reversed')) === '.Reversed';
    }
}
