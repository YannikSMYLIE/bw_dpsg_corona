<?php
namespace BoergenerWebdesign\BwDpsgCorona\Domain\Model;
use BoergenerWebdesign\BwDpsgCorona\Utility\DateTimeUtility;
use BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Meeting extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
    /** @var array */
    protected array $datetimes = [];
    /** @var string  */
    protected string $moreParticipants = "";
    /** @var string */
    protected string $name = "";

    /**
     * @param string $moreParticipants
     */
    public function setMoreParticipants(string $moreParticipants) : void {
        $this->moreParticipants = $moreParticipants;
    }
    /**
     * @return string
     */
    public function getMoreParticipants() : string {
        return $this->moreParticipants;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName(string $name) : void {
        $this->name = $name;
    }

    /**
     * @var \DateTime
     */
    protected ?\DateTime $beginDatetime = null;
    /**
     * @return \DateTime
     */
    public function getBeginDatetime() : ?\DateTime{
        return $this->beginDatetime;
    }
    /**
     * @param \DateTime $beginDatetime
     */
    public function setBeginDatetime(\DateTime $beginDatetime) : void {
        $this -> beginDatetime = $beginDatetime;
    }

    /**
     * @var \DateTime
     */
    protected ?\DateTime $endDatetime = null;
    /**
     * @return \DateTime
     */
    public function getEndDatetime() : ?\DateTime {
        return $this->endDatetime;
    }
    /**
     * @param \DateTime $endDatetime
     */
    public function setEndDatetime(\DateTime $endDatetime) : void {
        $this -> endDatetime = $endDatetime;
    }

    /**
     * @return array
     */
    public function getDatetimes() : array {
        return $this -> datetimes;
    }
    /**
     * Befüllt das Meeting mit den korrekten Zeitangaben.
     * @param string $beginDate
     * @param string $beginTime
     * @param string $endDate
     * @param string $endTime
     * @throws \Exception
     */
    public function setDatetimes(array $datetimes) : void {
        $this -> setBeginDatetime(DateTimeUtility::getDatetime(
            $datetimes["begin"]["date"], $datetimes["begin"]["time"]
        ));
        $this -> setEndDatetime(DateTimeUtility::getDatetime(
            $datetimes["end"]["date"], $datetimes["end"]["time"]
        ));
    }

    /**
     * @var \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied
     */
    protected ?\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $leader = null;
    /**
     * @return \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied
     */
    public function getLeader() : ?\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied {
        return $this->leader;
    }
    /**
     * @param \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $leader
     */
    public function setLeader(\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $leader) : void {
        $this->leader = $leader;
    }

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied>
     */
    protected ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage $participants = null;
    /**
     * @param \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant
     */
    public function addParticipant(\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant) : void {
        $this -> participants -> attach($participant);
    }
    /**
     * @param \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant
     */
    public function removeParticipant(\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant) : void {
        $this -> participants -> detach($participant);
    }
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied>
     */
    public function getParticipants() : ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage {
        return $this -> participants;
    }
    /**
     * @return array
     */
    public function getParticipantsArray() : array {
        $array = $this -> participants -> toArray();
        usort($array, function($a, $b) {
            return $a -> getName() > $b -> getName();
        });
        return $array;
    }
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied>
     */
    public function setParticipants(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $participants) : void {
        $this -> participants = $participants;
    }

    /**
     * Gibt alle Mitglieder zurück, auf welche durch den aktuell angemeldeten Frontend Benutzer zugegriffen werden können und auf der Anwesenheitsliste stehen.
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getParticipantsFromFeUser() : \TYPO3\CMS\Extbase\Persistence\ObjectStorage {
        // Alle Mitglieder auf die mit diesem Benutzeraccount zugegriffen werden kann ermitteln
        /** @var \BoergenerWebdesign\BwDpsgCorona\Utility\MitgliederUtility $mitgliederUtility */
        $mitgliederUtility = GeneralUtility::makeInstance(MitgliederUtility::class);
        $possibleMitglieder = $mitgliederUtility -> getFeUsersMitglieder();
        $possibleMitgliederUids = [];
        $possibleMitglieder -> rewind();
        while($mitglied = $possibleMitglieder -> current()) {
            $possibleMitgliederUids[] = $mitglied -> getUid();
            $possibleMitglieder -> next();
        }

        // Prüfen ob sie auch hier vorkommen
        $attendedMitglieder = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        /** @var \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant */
        foreach($this -> getParticipantsArray() as $participant) {
            if(in_array($participant -> getUid(), $possibleMitgliederUids)) {
                $attendedMitglieder -> attach($participant);
            }
        }
        return $attendedMitglieder;
    }
}