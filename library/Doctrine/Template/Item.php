<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Doctrine_Template_Item
 *
 * Easily create a balFramework item
 *
 * @package     Doctrine
 * @subpackage  Template
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 * @author      Benjamin "balupton" Lupton <contact@balupton.com>
 */
class Doctrine_Template_Item extends Doctrine_Template {
	
	// Versionable
	// Sluggable
	// Sortable
	// NestedSet
	// i18n
	
    /**
     * Array of Sluggable options
     *
     * @var string
     */
    protected $_options = array(
		'versionable'   =>  'slug',
        'alias'         =>  null,
        'type'          =>  'string',
        'length'        =>  255,
        'unique'        =>  true,
        'options'       =>  array(),
        'fields'        =>  array(),
        'uniqueBy'      =>  array(),
        'uniqueIndex'   =>  true,
        'canUpdate'     =>  false,
        'builder'       =>  array('Doctrine_Inflector', 'urlize'),
        'indexName'     =>  'sluggable'
    );

    /**
     * Set table definition for Sluggable behavior
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $name = $this->_options['name'];
        if ($this->_options['alias']) {
            $name .= ' as ' . $this->_options['alias'];
        }
        $this->hasColumn($name, $this->_options['type'], $this->_options['length'], $this->_options['options']);
        
        if ($this->_options['unique'] == true && $this->_options['uniqueIndex'] == true && ! empty($this->_options['fields'])) {
            $indexFields = array($this->_options['name']);
            $indexFields = array_merge($indexFields, $this->_options['uniqueBy']);
            $this->index($this->_options['indexName'], array('fields' => $indexFields,
                                                             'type' => 'unique'));
        }

        $this->addListener(new Doctrine_Template_Listener_Sluggable($this->_options));
    }
}
