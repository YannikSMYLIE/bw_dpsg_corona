<?php
namespace BoergenerWebdesign\BwDpsgCorona\Utility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PermissionUtility {
    /**
     * Prüft ob ein Frontend Benutzer eine Anwesenheitsliste bearbeiten darf.
     * @param \BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting
     * @return bool
     */
   public static function hasMeetingPersmission(\BoergenerWebdesign\BwDpsgCorona\Domain\Model\Meeting $meeting) : bool {
       if(TYPO3_MODE == "BE") {
           return true;
       }

       /** @var \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility $mitgliederUtility */
       $mitgliederUtility = GeneralUtility::makeInstance(MitgliederUtility::class);
       $leaders = $mitgliederUtility -> getLeiter($mitgliederUtility -> getFeUsersMitglieder());
       return $leaders -> contains($meeting -> getLeader());
   }

    /**
     * Prüft ob ein Frontend Benutzer auf eine*n Teilnehmer*in zugreifen darf.
     * @param \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant
     * @return bool
     */
   public static function hasParticipantPermission(\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant) : bool {
       if(TYPO3_MODE == "BE") {
           return true;
       }

       /** @var \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility $mitgliederUtility */
       $mitgliederUtility = GeneralUtility::makeInstance(MitgliederUtility::class);
       $participants = $mitgliederUtility -> getFeUsersMitglieder();
       return $participants -> contains($participant);
   }
}
