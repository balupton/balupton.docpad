<?php

/**
 * BaseTemplate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $code
 * @property string $title
 * @property string $description
 * @property string $content
 * @property boolean $system
 * @property integer $avatar_id
 * @property Image $Avatar
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6508 2009-10-14 06:28:49Z jwage $
 */
abstract class BaseTemplate extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('template');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('code', 'string', 30, array(
             'type' => 'string',
             'notblank' => true,
             'length' => '30',
             ));
        $this->hasColumn('title', 'string', 50, array(
             'type' => 'string',
             'default' => true,
             'length' => '50',
             ));
        $this->hasColumn('description', 'string', null, array(
             'type' => 'string',
             'extra' => 
             array(
              'html' => 'rich',
             ),
             ));
        $this->hasColumn('content', 'string', null, array(
             'type' => 'string',
             'extra' => 
             array(
              'html' => 'rich',
             ),
             ));
        $this->hasColumn('system', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
        $this->hasColumn('avatar_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Image as Avatar', array(
             'local' => 'avatar_id',
             'foreign' => 'id'));

        $balauditable0 = new BalAuditable(array(
             'status' => 
             array(
              'disabled' => true,
             ),
             'enabled' => 
             array(
              'disabled' => true,
             ),
             'author' => 
             array(
              'disabled' => false,
             ),
             'created_at' => 
             array(
              'disabled' => false,
             ),
             'updated_at' => 
             array(
              'disabled' => false,
             ),
             'published_at' => 
             array(
              'disabled' => true,
             ),
             ));
        $taggable0 = new Doctrine_Template_Taggable();
        $this->actAs($balauditable0);
        $this->actAs($taggable0);
    }
}