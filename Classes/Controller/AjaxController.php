<?php
namespace BoergenerWebdesign\BwDpsgCorona\Controller;
use BoergenerWebdesign\BwDpsgCorona\Utility\PermissionUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    /**
     * @var \BoergenerWebdesign\BwDpsgCorona\Domain\Repository\MeetingRepository
     * @inject
     */
    protected ?\BoergenerWebdesign\BwDpsgCorona\Domain\Repository\MeetingRepository $meetingRepository = null;

    /**
     * Entfernt ein Mitglied aus einer Anwesenheitsliste.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     * @param \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant
     */
    public function removeParticipantAction(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting, \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant) : void {
        if(!PermissionUtility::hasMeetingPersmission($meeting)) {
            $this -> message([
                'error' => true,
                'message' => 'Du hast nicht die Rechte, diese Anwesenheitsliste zu bearbeiten.'
            ]);
        }

        $meeting -> removeParticipant($participant);
        $this -> meetingRepository -> update($meeting);
        $this -> persistAll();
        $this -> message([
            'error' => false,
            'message' => 'Der*die Teilnehmer*in wurde entfernt.'
        ]);
    }

    /**
     * @param $msg
     */
    private function message(array $msg) : void {
        header('Content-Type: application/json');
        echo json_encode($msg);
        die();
    }

    /**
     * Persistiert alle Änderungen
     */
    private function persistAll() : void {
        $persistenceManager = $this->objectManager->get(PersistenceManager::class);
        $persistenceManager->persistAll();
    }
}
