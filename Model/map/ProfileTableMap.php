<?php

namespace Smirik\AdminBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'profiles' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Smirik.AdminBundle.Model.map
 */
class ProfileTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.AdminBundle.Model.map.ProfileTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('profiles');
        $this->setPhpName('Profile');
        $this->setClassname('Smirik\\AdminBundle\\Model\\Profile');
        $this->setPackage('src.Smirik.AdminBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'fos_user', 'ID', true, null, null);
        $this->addColumn('VK_ID', 'VkId', 'VARCHAR', false, 50, null);
        $this->addColumn('FIRST_NAME', 'FirstName', 'VARCHAR', false, 100, null);
        $this->addColumn('LAST_NAME', 'LastName', 'VARCHAR', false, 100, null);
        $this->addColumn('MIDDLE_NAME', 'MiddleName', 'VARCHAR', false, 100, null);
        $this->addColumn('BIRTHDAY', 'Birthday', 'TIMESTAMP', false, null, null);
        $this->addColumn('POSITION', 'Position', 'VARCHAR', false, 200, null);
        $this->addColumn('PHONE', 'Phone', 'VARCHAR', false, 50, null);
        $this->addColumn('SKYPE', 'Skype', 'VARCHAR', false, 50, null);
        $this->addColumn('FILE', 'File', 'VARCHAR', false, 100, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

} // ProfileTableMap
