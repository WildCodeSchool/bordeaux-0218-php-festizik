<?php
namespace Model;

/**
 *
 */
class ConcertManager extends AbstractManager
{
    const TABLE = 'Concert';

    const AVAILABLE_SORT_CRITERIAS = [
        [ 'name' => 'Day', 'label' => 'journée' ],
        [ 'name' => 'ArtistName', 'label' => "nom d'artiste" ],
        [ 'name' => 'Scene', 'label' => 'scène' ]
    ];


    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        Concert::initStatics();
        parent::__construct(static::TABLE);
    }


    /**
     * Insert ONE row into the DB
     * @param array $values an associative array storing the values for the new row
     * @return bool the result of PDOPreparedStatement::execute()
     */
    public function insert(array $values) : bool
    {
        $query = static::$pdoConnection->prepare(
            "INSERT INTO $this->table ( id_day, hour, id_scene, id_artist, cancelled )
                VALUES ( :day, :hour, :scene, :artist, :cancelled )"
        );

        $query->bindValue(':day', $values['id_day'], \PDO::PARAM_INT);
        $query->bindValue(':hour', $values['hour'], \PDO::PARAM_STR);
        $query->bindValue(':scene', $values['id_scene'], \PDO::PARAM_INT);
        $query->bindValue(':artist', $values['id_artist'], \PDO::PARAM_INT);
        $query->bindValue(':cancelled', $values['cancelled'], \PDO::PARAM_INT);

        return $query->execute();
    }


//TODO : write insertMultiple() : multiple values in ONE SQL request, for more efficiency


//-------- sorting function --------

//TODO: this should be generalized to all tables into AbstractManager
    /**
    * returns an array of all sort criteria usable in sortConcertArray()
    */
    public static function getAvailableSortCriterias() : array
    {
        return static::AVAILABLE_SORT_CRITERIAS;
    }


    /**
    * sort the given array by the given criteria
    * @return bool true if all was ok, false otherwise
    **/
    public function sortArray(array &$list, string $criteria)
    {

        $valid = false;

        # is the criteria valid?
        foreach (static::AVAILABLE_SORT_CRITERIAS as $test) {
            if ($test['name'] == $criteria) {
                $valid = true;
                break;
            }
        }

        if (!$valid) {
            return false;
        }

        #now we can sort...
        $sortFunc='cmpBy' . $criteria;
        # a callable is definable by an array [ Object, method, params... ],
        # perhaps the only way when you must reference a static method ...
        return  usort($list, [ 'Model\Concert', $sortFunc ]);
    }
}
