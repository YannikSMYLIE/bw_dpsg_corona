<?php
namespace BoergenerWebdesign\BwDpsgCorona\Utility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use BoergenerWebdesign\BwDpsgNami\Domain\Repository\MitgliedRepository;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;

class MitgliederUtility implements \TYPO3\CMS\Core\SingletonInterface {
    /**
     * @var MitgliedRepository
     */
    protected ?MitgliedRepository $mitgliedRepository = null;
    /**
     * @var FrontendUserRepository
     */
    protected ?FrontendUserRepository $feUserRepository = null;

    /**
     * Erstellt alle Repositories.
     */
    private function init() : void {
        if(!is_null($this -> mitgliedRepository)) {
            return;
        }
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
        $this -> mitgliedRepository = $objectManager->get(MitgliedRepository::class);
        $this -> feUserRepository = $objectManager->get(FrontendUserRepository::class);
    }



    /**
     * Ermittelt alle mit dem aktuellen Frontend Benutzer verbundenen Mitglieder.
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getFeUsersMitglieder() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage {
        $this -> init();

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $mitglieder */
        $mitglieder = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

        /** @var int $currentFeUserUid */
        $currentFeUserUid = $GLOBALS['TSFE']->fe_user->user['uid'] ?? 0;

        if($currentFeUserUid) {
            /** @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $currentFeUser */
            $currentFeUser = $this -> feUserRepository -> findByUid($currentFeUserUid);

            /** @var \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $mitglied */
            foreach($this -> mitgliedRepository -> findByFeUsers($currentFeUser) as $mitglied) {
                $mitglieder -> attach($mitglied);
            }
        }
        return $mitglieder;
    }

    /**
     * Gibt aus einer Menge von Mitgliedern die zurück, die Leiter*innen sind.
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $mitglieder
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getLeiter(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $mitglieder) : \TYPO3\CMS\Extbase\Persistence\ObjectStorage {
        $this -> init();

        $leiter = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

        $mitglieder -> rewind();
        /** @var \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $mitglied */
        while($mitglied = $mitglieder -> current()) {
            if($mitglied -> getLeiter()) {
                $leiter -> attach($mitglied);
            }
            $mitglied = $mitglieder -> next();
        }
        return $leiter;
    }
}
