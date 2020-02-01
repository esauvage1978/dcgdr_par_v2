<?php


namespace App\Workflow;


class WorkflowData
{
    const STATE_STARTED = 'started';
    const STATE_COTECH = 'cotech';
    const STATE_CODIR = 'codir';
    const STATE_REJECTED = 'rejected';
    const STATE_FINALISED = 'finalised';
    const STATE_DEPLOYED = 'deployed';
    const STATE_MEASURED = 'measured';
    const STATE_CLOTURED = 'clotured';
    const STATE_ABANDONNED = 'abandonned';

    const TRANSITION_TO_STARTED = 'toStarted';
    const TRANSITION_TO_COTECH = 'toCotech';
    const TRANSITION_TO_CODIR = 'toCodir';
    const TRANSITION_TO_REJECTED = 'toRejected';
    const TRANSITION_TO_FINALISED = 'toFinalised';
    const TRANSITION_TO_DEPLOYED = 'toDeploye';
    const TRANSITION_TO_MEASURED = 'toMeasured';
    const TRANSITION_TO_CLOTURED = 'toClotured';
    const TRANSITION_UN_DEPLOYED = 'unDeployed';
    const TRANSITION_UN_MEASURED = 'unMeasured';
    const TRANSITION_UN_CLOTURED = 'unClotured';
    const TRANSITION_TO_ABANDONNED = 'toAbandonned';

    public static function getTransitionsForState($state)
    {
        $transitions = [];
        switch ($state) {
            case self::STATE_STARTED:
                $transitions = [
                    self::TRANSITION_TO_COTECH,
                    self::TRANSITION_TO_ABANDONNED
                ];
                break;
            case self::STATE_COTECH:
                $transitions = [
                    self::TRANSITION_TO_CODIR,
                    self::TRANSITION_TO_REJECTED,
                    self::TRANSITION_TO_ABANDONNED,
                    self::TRANSITION_TO_STARTED];
                break;
            case self::STATE_REJECTED:
                $transitions = [
                    self::TRANSITION_TO_ABANDONNED,
                    self::TRANSITION_TO_STARTED
                ];
                break;
            case self::STATE_CODIR:
                $transitions = [
                    self::TRANSITION_TO_FINALISED,
                    self::TRANSITION_TO_REJECTED,
                    self::TRANSITION_TO_ABANDONNED,
                    self::TRANSITION_TO_STARTED
                ];
                break;
            case self::STATE_FINALISED:
                $transitions = [
                    self::TRANSITION_TO_DEPLOYED,
                    self::TRANSITION_TO_ABANDONNED,
                    self::TRANSITION_TO_STARTED]
                ;
                break;
            case self::STATE_DEPLOYED:
                $transitions = [
                    self::TRANSITION_TO_MEASURED,
                    self::TRANSITION_TO_ABANDONNED,
                    self::TRANSITION_UN_DEPLOYED
                ];
                break;
            case self::STATE_MEASURED:
                $transitions = [
                    self::TRANSITION_TO_CLOTURED,
                    self::TRANSITION_TO_ABANDONNED,
                    self::TRANSITION_UN_MEASURED];
                break;
            case self::STATE_CLOTURED:
                $transitions = [
                    self::TRANSITION_UN_CLOTURED,
                    self::TRANSITION_TO_ABANDONNED
                ];
                break;
            case self::STATE_ABANDONNED:
                $transitions = [self::TRANSITION_TO_STARTED];
                break;
        }

        return $transitions;
    }
    public static function getNameOfState(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::STATE_STARTED:
                $stateName = ' 0. Action proposée';
                break;
            case self::STATE_COTECH:
                $stateName = ' 1. COTECH';
                break;
            case self::STATE_REJECTED:
                $stateName = ' 2. Action refusée';
                break;
            case self::STATE_CODIR:
                $stateName = ' 3. CODIR';
                break;
            case self::STATE_FINALISED:
                $stateName = ' 4. Rédaction méthodologie';
                break;
            case self::STATE_DEPLOYED:
                $stateName = ' 5. Action déployée';
                break;
            case self::STATE_MEASURED:
                $stateName = ' 6. Action à mesurer';
                break;
            case self::STATE_CLOTURED:
                $stateName = ' 7. Action clôturée';
                break;
            case self::STATE_ABANDONNED:
                $stateName = ' 8. Action abandonnée';
                break;
        }

        return $stateName;
    }
    public static function getTitleOfMail(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::STATE_STARTED:
                $stateName = ' Une action est revenue à l\'état "action proposée"';
                break;
            case self::STATE_COTECH:
                $stateName = ' Une action est proposée au COTECH';
                break;
            case self::STATE_REJECTED:
                $stateName = ' Une action est refusée';
                break;
            case self::STATE_CODIR:
                $stateName = ' Une action est proposée au CODIR';
                break;
            case self::STATE_FINALISED:
                $stateName = ' Une action est validée par le CORDIR et est en attente de la rédaction méthodologie';
                break;
            case self::STATE_DEPLOYED:
                $stateName = ' Une action est déployée';
                break;
            case self::STATE_MEASURED:
                $stateName = ' Une action est en attente de la mesure de l\efficacité';
                break;
            case self::STATE_CLOTURED:
                $stateName = ' Une action est clôturée';
                break;
            case self::STATE_ABANDONNED:
                $stateName = ' Une action est abandonnée';
                break;
        }

        return $stateName;
    }
    public static function getShortNameOfState(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::STATE_STARTED:
                $stateName = ' 0. Proposée';
                break;
            case self::STATE_COTECH:
                $stateName = ' 1. COTECH';
                break;
            case self::STATE_REJECTED:
                $stateName = ' 2. Refusée';
                break;
            case self::STATE_CODIR:
                $stateName = ' 3. CODIR';
                break;
            case self::STATE_FINALISED:
                $stateName = ' 4. Méthodologie';
                break;
            case self::STATE_DEPLOYED:
                $stateName = ' 5. Déployée';
                break;
            case self::STATE_MEASURED:
                $stateName = ' 6. A mesurer';
                break;
            case self::STATE_CLOTURED:
                $stateName = ' 7. Clôturée';
                break;
            case self::STATE_ABANDONNED:
                $stateName = ' 8. Abandonnée';
                break;
        }

        return $stateName;
    }
    public static function getColorOfState(string $state)
    {
        $stateColor = '';
        switch ($state) {
            case self::STATE_STARTED:
                $stateColor = '#000000';
                break;
            case self::STATE_COTECH:
                $stateColor = '#020efd';
                break;
            case self::STATE_REJECTED:
                $stateColor = '#028bfd';
                break;
            case self::STATE_CODIR:
                $stateColor = '#02e2fd';
                break;
            case self::STATE_FINALISED:
                $stateColor = '#00c3b4';
                break;
            case self::STATE_DEPLOYED:
                $stateColor = '#89d51b';
                break;
            case self::STATE_MEASURED:
                $stateColor = '#f21cec';
                break;
            case self::STATE_CLOTURED:
                $stateColor = '#9a1cf2';
                break;
            case self::STATE_ABANDONNED:
                $stateColor = '#f21c1c';
                break;
        }

        return $stateColor;
    }

    public static function getModalDataForTransition(string $transition)
    {
        $data=[
            'state'=>'',
            'transition'=>$transition,
            'titre'=>'',
            'btn_label'=>''
        ];

        switch ($transition) {
            case self::TRANSITION_TO_COTECH:
                $data['state']=self::STATE_COTECH;
                $data['titre']='Soumettre au COTECH';
                $data['btn_label']='Envoyer au COTECH';
                break;
            case self::TRANSITION_TO_ABANDONNED:
                $data['state']=self::STATE_ABANDONNED;
                $data['titre']='Abandonner l\'action';
                $data['btn_label']='Abandonner';
                break;
            case self::TRANSITION_TO_CODIR:
                $data['state']=self::STATE_CODIR;
                $data['titre']='Soumettre au CODIR';
                $data['btn_label']='Valider et envoyer au CODIR';
                break;
            case self::TRANSITION_TO_REJECTED:
                $data['state']=self::STATE_REJECTED;
                $data['titre']='Refusée l\'action';
                $data['btn_label']='Refuser';
                break;
            case self::TRANSITION_TO_STARTED:
                $data['state']=self::STATE_STARTED;
                $data['titre']='Reprendre l\'action';
                $data['btn_label']='Reprendre';
                break;
            case self::TRANSITION_TO_FINALISED:
                $data['state']=self::STATE_FINALISED;
                $data['titre']='Validé par le CODIR';
                $data['btn_label']='Valider';
                break;
            case self::TRANSITION_UN_DEPLOYED:
                $data['state']=self::STATE_FINALISED;
                $data['titre']='Reprendre l\'action';
                $data['btn_label']='Reprendre l\'action';
                break;
            case self::TRANSITION_TO_DEPLOYED:
                $data['state']=self::STATE_DEPLOYED;
                $data['titre']='Déployer l\'action';
                $data['btn_label']='Déployer l\'action';
                break;
            case self::TRANSITION_UN_MEASURED:
                $data['state']=self::STATE_DEPLOYED;
                $data['titre']='Redéployer l\'action';
                $data['btn_label']='Redéployer l\'action';
                break;
            case self::TRANSITION_TO_MEASURED:
                $data['state']=self::STATE_MEASURED;
                $data['titre']='Mesurer l\'efficacité de l\'action';
                $data['btn_label']='Mesurer l\'action';
                break;
            case self::TRANSITION_UN_CLOTURED:
                $data['state']=self::STATE_MEASURED;
                $data['titre']='Ré-ouvrir  l\'action';
                $data['btn_label']='Ré-ouvrir l\'action';
                break;
            case self::TRANSITION_TO_CLOTURED:
                $data['state']=self::STATE_CLOTURED;
                $data['titre']='Clôturer  l\'action';
                $data['btn_label']='Clôturer l\'action';
                break;
        }

        return $data;
    }

}