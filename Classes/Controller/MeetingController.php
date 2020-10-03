<?php
namespace BoergenerWebdesign\BwDpsgCorona\Controller;
use BoergenerWebdesign\BwDpsgCorona\Utility\PermissionUtility;
use \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility;

class MeetingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    /**
     * @var \BoergenerWebdesign\BwDpsgCorona\Domain\Repository\MeetingRepository
     * @inject
     */
    protected ?\BoergenerWebdesign\BwDpsgCorona\Domain\Repository\MeetingRepository $meetingRepository = null;

    /**
     * @var \BoergenerWebdesign\BwDpsgNami\Domain\Repository\MitgliedRepository
     * @inject
     */
    protected ?\BoergenerWebdesign\BwDpsgNami\Domain\Repository\MitgliedRepository $mitgliedRepository = null;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected ?\TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository $feUserRepository = null;

    /**
     * Listet je nach Kontext entweder alle Meetings auf oder solche, an denen Mitglieder der aktuellen FE-Users teilgenommen haben oder die durch sie erstellt wurden.
     */
    public function listAction() : void {
        if(TYPO3_MODE == "BE") {
            $this -> view -> assignMultiple([
                'meetings' => $this -> meetingRepository -> findAll(),
                'leaders' => $this -> mitgliedRepository -> findByLeiter(true),
                'mitglieder' => $this -> mitgliedRepository -> findByActive(true)
            ]);
        } else {
            /** @var \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility $mitgliederUtility */
            $mitgliederUtility = $this -> objectManager -> get(MitgliederUtility::class);
            $mitglieder = $mitgliederUtility -> getFeUsersMitglieder();
            $attendedMeetings = $this -> meetingRepository -> findByParticipants($mitglieder);
            $createdMeetings = $this -> meetingRepository -> findByLeader($mitglieder);

            $this -> view -> assignMultiple([
                'mitglieder' => $mitglieder,
                'leaders' => $mitgliederUtility -> getLeiter($mitglieder),
                'attendedMeetings' => $attendedMeetings,
                'createdMeetings' => $createdMeetings
            ]);
        }
    }

    /**
     * Legt eine neue Anwesenheitsliste an.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     */
    public function createAction(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting) : void {
        if(PermissionUtility::hasMeetingPersmission($meeting)) {
            $this -> meetingRepository -> add($meeting);
            $this -> addFlashMessage("Die Anwesenheitsliste wurde angelegt!");
        } else {
            $this -> addFlashMessage("Du hast nicht die notwendigen Rechte um die Anwesenheitsliste anzulegen!", "Die Anwesenheitsliste konnte nicht angelegt werden:");
        }
        $this -> redirect('list');
    }

    /**
     * Zeigt eine Anwesenheitsliste an.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     */
    public function showAction(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting) : void {
        if(!PermissionUtility::hasMeetingPersmission($meeting)) {
            $this -> addFlashMessage("Du hast nicht die notwendigen Rechte um die Anwesenheitsliste anzuzeigen!", "Die Anwesenheitsliste konnte nicht angezeigt werden:");
            $this -> redirect('list');
        }

        $mitgliederUtility = $this -> objectManager -> get(MitgliederUtility::class);
        $this -> view -> assignMultiple([
            'meeting' => $meeting,
            'mitglieder' => [
                'alle' => $this -> mitgliedRepository -> findByActive(true),
                'woelflinge' => $this -> mitgliedRepository -> findByStufe(1),
                'jungpfadfinder' => $this -> mitgliedRepository -> findByStufe(2),
                'pfadfinder' => $this -> mitgliedRepository -> findByStufe(3),
                'rover' => $this -> mitgliedRepository -> findByStufe(4)
            ],
            'leaders' => $mitgliederUtility -> getLeiter($mitgliederUtility -> getFeUsersMitglieder())
        ]);
    }

    /**
     * Stellt eine Oberfläche zum Verwalten von Anwesenheitslisten im Frontend zur Verfügung.
     */
    public function manageAction() : void {
        /** @var \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility $mitgliederUtility */
        $mitgliederUtility = $this -> objectManager -> get(MitgliederUtility::class);
        $mitglieder = $mitgliederUtility -> getFeUsersMitglieder();
        $attendedMeetings = $this -> meetingRepository -> findByParticipants($mitglieder);
        $createdMeetings = $this -> meetingRepository -> findByLeader($mitglieder);
        $leaders = $mitgliederUtility -> getLeiter($mitglieder);

        if(!$leaders) {
            $this -> addFlashMessage("Du bist nicht berechtigt Anwesenheitslisten zu verwalte!");
            $this -> redirect('list');
        }

        $this -> view -> assignMultiple([
            'mitglieder' => $mitglieder,
            'leaders' => $leaders,
            'attendedMeetings' => $attendedMeetings,
            'createdMeetings' => $createdMeetings
        ]);
    }

    /**
     * Aktualisiert eine Anwesenheitslliste.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     */
    public function updateAction(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting) : void {
        if(PermissionUtility::hasMeetingPersmission($meeting)) {
            $this -> meetingRepository -> update($meeting);
            $this -> addFlashMessage("Die Anwesenheitsliste wurde aktualisiert!");
        } else {
            $this -> addFlashMessage("Du hast nicht die notwendigen Rechte um die Anwesenheitsliste zu aktualisieren!", "Die Anwesenheitsliste konnte nicht aktualisiert werden:");
        }

        if(TYPO3_MODE == "BE") {
            $this -> redirect('list');
        } else {
            $this -> redirect('show', 'Meeting', null, ['meeting' => $meeting]);
        }
    }

    /**
     * Entfernt eine Anwesenheitsliste.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     */
    public function deleteAction(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting) : void {
        if(TYPO3_MODE == "BE") {
            $this -> meetingRepository -> remove($meeting);
            $this -> addFlashMessage("Die Anwesenheitsliste wurde entfernt!");
        } else {
            $this -> addFlashMessage("Du hast nicht die notwendigen Rechte um die Anwesenheitsliste zu entfernen!", "Die Anwesenheitsliste konnte nicht entfernt werden:");
        }
        $this -> redirect('list');
    }
}
