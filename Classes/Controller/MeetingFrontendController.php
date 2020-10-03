<?php
namespace BoergenerWebdesign\BwDpsgCorona\Controller;
use BoergenerWebdesign\BwDpsgCorona\Utility\PermissionUtility;
use \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility;

class MeetingFrontendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
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
     * Listet alle Meetings auf an denen Mitglieder des aktuellen FE-Users teilgenommen haben.
     */
    public function listAction(?\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant = null) : void {
        // Alle möglichen Mitglieder einlesen
        /** @var \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility $mitgliederUtility */
        $mitgliederUtility = $this -> objectManager -> get(MitgliederUtility::class);
        $participants = $mitgliederUtility -> getFeUsersMitglieder();

        // Anzuzeigendes Mitglied bestimmen
        if(!$participant || $participant && !PermissionUtility::hasParticipantPermission($participant)) {
            $participants -> rewind();
            $participant = $participants -> current();
        }


        $meetings = $this -> meetingRepository -> findByParticipant($participant);

        $this -> view -> assignMultiple([
            'mitglieder' => $participants,
            'leaders' => $mitgliederUtility -> getLeiter($participants),
            'participant' => $participant,
            'meetings' => $meetings
        ]);
    }

    /**
     * Stellt eine Oberfläche zum Verwalten von Anwesenheitslisten zur Verfügung.
     */
    public function manageAction() : void {
        /** @var \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility $mitgliederUtility */
        $mitgliederUtility = $this -> objectManager -> get(MitgliederUtility::class);
        $mitglieder = $mitgliederUtility -> getFeUsersMitglieder();
        $leaders = $mitgliederUtility -> getLeiter($mitglieder);
        $meetings = $this -> meetingRepository -> findByLeader($leaders);

        if(!$leaders) {
            $this -> addFlashMessage("Du bist nicht berechtigt Anwesenheitslisten zu verwalte!");
            $this -> redirect('list');
        }

        $this -> view -> assignMultiple([
            'mitglieder' => $mitglieder,
            'leaders' => $leaders,
            'meetings' => $meetings
        ]);
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
        $this -> redirect('manage');
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
        $this -> redirect('show', null, null, ['meeting' => $meeting]);
    }

    /**
     * Fügt eine*n Teilnehmer*in zu einer Anwesenheitsliste hinzu.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     * @param \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant
     */
    public function addParticipantAction(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting, \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant) : void {
        if(PermissionUtility::hasMeetingPersmission($meeting)) {
            $meeting -> addParticipant($participant);
            $this -> meetingRepository -> update($meeting);
            $this -> addFlashMessage("Die Anwesenheitsliste wurde aktualisiert!");
        } else {
            $this -> addFlashMessage("Du hast nicht die notwendigen Rechte um die Anwesenheitsliste zu aktualisieren!", "Die Anwesenheitsliste konnte nicht aktualisiert werden:");
        }
        $this -> redirect('show', null, null, ['meeting' => $meeting]);
    }

    /**
     * Entfernt eine*n Teilnehmer*in von einer Anwesenheitsliste.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     * @param \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant
     */
    public function removeParticipantAction(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting, \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant) : void {
        if(PermissionUtility::hasMeetingPersmission($meeting)) {
            $meeting -> removeParticipant($participant);
            $this -> meetingRepository -> update($meeting);
            $this -> addFlashMessage("Die Anwesenheitsliste wurde aktualisiert!");
        } else {
            $this -> addFlashMessage("Du hast nicht die notwendigen Rechte um die Anwesenheitsliste zu aktualisieren!", "Die Anwesenheitsliste konnte nicht aktualisiert werden:");
        }
        $this -> redirect('show', null, null, ['meeting' => $meeting]);
    }
}
