<?php

/**
 * Base_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property Doctrine_Collection $InvoicesFor
 * @property Doctrine_Collection $InvoicesBy
 * @property Doctrine_Collection $MessagesFor
 * @property Doctrine_Collection $MessagesBy
 * @property Doctrine_Collection $Bal_RoleAndUser
 * @property Doctrine_Collection $Bal_PermissionAndUser
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class Base_User extends Balcms_User
{
    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Bal_Invoice as InvoicesFor', array(
             'local' => 'id',
             'foreign' => 'for_id'));

        $this->hasMany('Bal_Invoice as InvoicesBy', array(
             'local' => 'id',
             'foreign' => 'by_id'));

        $this->hasMany('Bal_Message as MessagesFor', array(
             'local' => 'id',
             'foreign' => 'for_id'));

        $this->hasMany('Bal_Message as MessagesBy', array(
             'local' => 'id',
             'foreign' => 'by_id'));

        $this->hasMany('Bal_RoleAndUser', array(
             'local' => 'id',
             'foreign' => 'assignee_user_id'));

        $this->hasMany('Bal_PermissionAndUser', array(
             'local' => 'id',
             'foreign' => 'assignee_user_id'));
    }
}