<?php
namespace BoergenerWebdesign\BwDpsgCorona\Domain\Repository;

class MeetingRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    protected $defaultOrderings = [
        'beginDatetime' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    ];

    public function findByParticipant(\BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant) : \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult {
        $query = $this -> createQuery();
        $query -> matching(
            $query -> contains('participants', $participant)
        );
        return $query -> execute();
    }

    public function findByParticipants(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $participants) : \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult {
        $query = $this -> createQuery();
        $constraints = [];

        $participants -> rewind();
        /** @var \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $participant */
        while($participant = $participants -> current()) {
            $constraints[] = $query -> contains('participants', $participant -> getUid());
            $participants -> next();
        }

        if($constraints) {
            $query -> matching(
                $query -> logicalOr($constraints)
            );
        }
        $result = $query -> execute();
        return $result;
    }

    public function findByLeader(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $leaders) : \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult {
        $query = $this -> createQuery();
        $constraints = [];

        $leaders -> rewind();
        /** @var \BoergenerWebdesign\BwDpsgNami\Domain\Model\Mitglied $leader */
        while($leader = $leaders -> current()) {
            $constraints[] = $query -> equals('leader', $leader -> getUid());
            $leaders -> next();
        }

        if($constraints) {
            $query -> matching(
                $query -> logicalOr($constraints)
            );
        }
        $result = $query -> execute();
        return $result;
    }
}
