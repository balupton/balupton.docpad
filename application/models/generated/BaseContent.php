<?php

/**
 * BaseContent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $route_id
 * @property timestamp $sent_at
 * @property integer $sent_all
 * @property integer $sent_remaining
 * @property enum $sent_status
 * @property Route $Route
 * @property Doctrine_Collection $Subscribers
 * @property Doctrine_Collection $ContentAndSubscriber
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6508 2009-10-14 06:28:49Z jwage $
 */
abstract class BaseContent extends Template
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('content');
        $this->hasColumn('route_id', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'unique' => true,
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('sent_at', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('sent_all', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('sent_remaining', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('sent_status', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'none',
              1 => 'pending',
              2 => 'completed',
             ),
             'default' => 'unsent',
             'notblank' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Route', array(
             'local' => 'route_id',
             'foreign' => 'id'));

        $this->hasMany('Subscriber as Subscribers', array(
             'refClass' => 'ContentAndSubscriber',
             'local' => 'content_id',
             'foreign' => 'subscriber_id'));

        $this->hasMany('ContentAndSubscriber', array(
             'local' => 'id',
             'foreign' => 'content_id'));

        $balauditable0 = new BalAuditable(array(
             'status' => 
             array(
              'disabled' => false,
             ),
             'enabled' => 
             array(
              'disabled' => false,
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
              'disabled' => false,
             ),
             ));
        $nestedset0 = new Doctrine_Template_NestedSet(array(
             'hasManyRoots' => true,
             'rootColumnName' => 'root_id',
             ));
        $searchable0 = new Doctrine_Template_Searchable(array(
             'fields' => 
             array(
              0 => 'code',
              1 => 'status',
              2 => 'enabled',
              3 => 'system',
             ),
             ));
        $this->actAs($balauditable0);
        $this->actAs($nestedset0);
        $this->actAs($searchable0);
    }
}