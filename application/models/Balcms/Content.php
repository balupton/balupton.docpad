<?php

/**
 * Balcms_Content
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Balcms_Content extends Base_Balcms_Content
{

	/**
	 * Apply modifiers
	 * @return
	 */
	public function setUp ( ) {
		$this->hasMutator('Avatar', 'setAvatar');
		$this->hasMutator('position', 'setPosition');
		$this->hasMutator('path', 'setPath');
		$this->hasMutator('code', 'setCode');
		parent::setUp();
	}
	
	/**
	 * Set a File Attachment
	 * @return string
	 */
	protected function setFileAttachment ( $what, $file ) {
		$value = Bal_Doctrine_Core::presetFileAttachment($this,$what,$file);
		return $value === false ? null : $this->_set($what,$value,false);
	}
	
	/**
	 * Set the User's Avatar
	 * @return string
	 */
	public function setAvatar ( $value ) {
		return $this->setFileAttachment('Avatar',$value);
	}
	
	/**
	 * Convert the content to a navigation item
	 * @param mixed $Content
	 * @return array
	 */
	public static function toNavItem ( $Content ) {
		# Prepare
		$Content = $Content;
		$Content_Route = delve($Content,'Route');
		if ( is_object($Content_Route) ) $Content_Route = $Content_Route->toArray();
		
		# Convert
		$content = array(
			'id' => 'content-'.delve($Content,'code'),
			'route' => 'map',
			'label' => delve($Content,'title'),
			'title' => delve($Content,'tagline',delve($Content,'title')),
			'order' => delve($Content,'position'),
			'params' => array(
				'Map' => $Content_Route
			),
			'route' => 'map'
		);
		
		# Return content
		return $content;
	}
	
	/**
	 * Fetched the crumbs as navigation items
	 * @param bool $includeSelf [optional] defaults to true
	 * @return array
	 */
	public static function toNavItems ( $Items ) {
		# Prepare
		$NavItems = array();
		
		# To Navigation
		foreach ( $Items as $Item ) {
			$NavItems[] = Content::toNavItem($Item);
		}
		
		# Return NavItems
		return $NavItems;
	}
	
	
	/**
	 * Get's the content's crumbs
	 * @param const $hydrateMode [optional]
	 * @param bool $includeSelf [optional]
	 * @return mixed
	 */
	public function getAncestors ( $includeSelf = true, $hydrateMode = null ) {
		# Prepare
		$Ancestors = array();
		$Ancestor = $this;
		while ( $Ancestor->Parent_id ) {
			$Ancestor = $Ancestor->Parent;
			$Ancestors[] = $hydrateMode === Doctrine::HYDRATE_ARRAY ? $Ancestor->toArray() : $Ancestor;
		}
		
		# Include?
		if ( $includeSelf ) {
			$Ancestors[] = $hydrateMode === Doctrine::HYDRATE_ARRAY ? $this->toArray() : $this;
		}
		
		# Return Ancestors
		return $Ancestors;
	}
	
	/**
	 * Get's the content's children
	 * @param const $hydrateMode [optional]
	 * @param bool $includeSelf [optional]
	 * @return mixed
	 */
	public function getChildren ( $includeSelf = true, $hydrateMode = null ) {
		# Prepare
		$Children = $this->Children;
		if ( $hydrateMode === Doctrine::HYDRATE_ARRAY && !is_array($Children) ) {
			$Children = $Children->toArray();
		}
		if ( !count($Children) ) {
			$Children = array();
		}
		
		# Include?
		if ( $includeSelf ) {
			$Children[] = $hydrateMode === Doctrine::HYDRATE_ARRAY ? $this->toArray() : $this;
		}
		
		# Return Children
		return $Children;
	}
	
	public function getUrl ( ) {
		# Prepare
		$View = Bal_App::getView();
		
		# Url
		$url = $View->url()->content($this)->toString();
		
		# Return url
		return $url;
	}
	
	/**
	 * Get's the section links
	 */
	public function getSectionLinks ( ) {
		# Prepare
		$Content = $this;
		$Links = array();
		$url = $Content->getUrl();
		$content = $Content->content_rendered;//content_rendered;
		$content = preg_replace('/(<\\/?)(article|aside|audio|canvas|command|datalist|details|embed|figcaption|figure|footer|header|hgroup|keygen|mark|meter|nav|output|progress|rp|rt|ruby|source|summary|time|video)(>?)/i', '$1div$3', $content);
		// ^ convert to HTML4 from HTML5
		$content = str_replace(array('<section','section>'),array('<i','i>'),$content);
		$Document = new DOMDocument();
		$Document->loadHTML('<html><head></head><body>'.$content.'</body></html>');
		
		# Generate
		$Sections = $Document->getElementsByTagName('i');
		for ( $i = 0; $i < $Sections->length; ++$i ) {
			$Section = $Sections->item($i);
			
			# Id
			$id = $Section->getAttribute('id');
			if ( !$id ) continue;
			
			# Class
			$class = $Section->getAttribute('class');
			if ( in_array('noindex', explode(' ', $class)) ) continue;
		
			# Title
			$label = $title = ucfirst($id);
			$H1s = $Section->getElementsByTagName('h1');
			foreach ( $H1s as $H1 ) {
				$label = $H1->getAttribute('title');
				$title = strip_tags($Document->saveXML($H1));
				if ( !$label ) $label = $title;
				break;
			}
		
			# Apply
			$Links[] = array(
				'id' => $this->code.'-'.$id,
				'route' => null,
				'uri' => $url.'#'.$id,
				'label' => $label,
				'title' => $this->title.' > '.$title,
				'order' => $i
			);
		}
		
		# Add Us
		//$us = Content::toNavItem($Content);
		//$us['label'] = 'Open';
		//$us['order'] = -1;
		//array_unshift($Links, $us);
		
		# Return Links
		return $Links;
	}
	
	/**
	 * Sets the code field
	 * @param int $code
	 * @return bool
	 */
	public function setCode ( $code, $load = true ) {
		$code = strtolower($code);
		$code = preg_replace('/[\s_]/', '-', $code);
		$code = preg_replace('/[^-a-z0-9]/', '', $code);
		$code = preg_replace('/--+/', '-', $code);
		$this->_set('code', $code, $load);
		return true;
	}
	
	/**
	 * Sets the position
	 * @param int $position [optional] defaults to id
	 * @return bool
	 */
	public function setPosition ( $position, $load = true ) {
		# Has Changed?
		if ( $this->position != $position && $position > 0 ) {
			$this->_set('position', $position, $load);
			return true;
		}
		
		# No Change
		return false;
	}

	/**
	 * Sets the Route's path field
	 * @param int $path [optional]
	 * @return bool
	 */
	public function setPath ( $path, $load = true ) {
		# Prepare
		$save = false;
		# Prepare
		$path = trim($path, '/');
		if ( empty($path) ) {
			return false;
		}
		# Update
		if ( $this->Route_id ) {
			$Route = $this->Route;
		} else {
			$Route = new Route();
			$Route->type = 'content';
			$Route->data = array('id' => $this->id);
			$this->Route = $Route;
			$save = true;
		}
		# Apply
		if ( $Route->path != $path ) {
			$Route->path = $path;
			$Route->save();
			# Update Children
			$Children = $this->Children;
			foreach ( $Children as $Child ) {
				$Child->setPath($path.'/'.$Child->code);
			}
		}
		# Done
		return $save;
	}
	
	
	/**
	 * Get Subscribers Query
	 * @param constant $hydrateMode
	 * @param Doctrine_Query $SubscriberQuery
	 */
	public function getSubscribersQuery ( $hydrateMode = null ) {
		$tags = $this->ContentTagsNames;
		$SubscribersQuery = Doctrine_Query::create()->select('u.*')->from('User u, u.SubscriptionTags uSubscription')->where('u.status = ?', 'published')->andWhereIn('uSubscription.name', $tags)->orderBy('u.id ASC');
		if ( empty($tags) ) {
			$SubscribersQuery->andWhere('true = false');
		}
		if ( !is_null($hydrateMode) ) {
			$SubscribersQuery->setHydrationMode($hydrateMode);
		}
		return $SubscribersQuery;
	}
	
	/**
	 * Get Unsent Subscribers Query
	 * @param constant $hydrateMode
	 * @param Doctrine_Query $SubscriberQuery
	 */
	public function getUnsentSubscribersQuery ( $hydrateMode = null ) {
		$SubscribersQuery = $this->getSubscribersQuery($hydrateMode);
		$SubscribersQuery->andWhere('NOT EXISTS (SELECT m.id FROM Message m WHERE m.UserFor.id = u.id AND m.Content.id = ?)', $this->id);
		return $SubscribersQuery;
	}
	
	/**
	 * Get Subscribers
	 * @param constant $hydrateMode
	 * @param Doctrine_Query $SubscriberQuery
	 */
	public function getSubscribers ( $hydrateMode = null ) {
		$SubscribersArray = array();
		if ( $this->id ) {
			$SubscribersQuery = $this->getSubscribersQuery($hydrateMode);
			$SubscribersArray = $SubscribersQuery->execute();
		}
		return $SubscribersArray;
	}
	
	/**
	 * Get Unsent Subscribers
	 * @param constant $hydrateMode
	 * @param Doctrine_Query $SubscriberQuery
	 */
	public function getUnsentSubscribers ( $hydrateMode = null ) {
		$SubscribersArray = array();
		if ( $this->id ) {
			$SubscribersQuery = $this->getUnsentSubscribersQuery($hydrateMode);
			$SubscribersArray = $SubscribersQuery->execute();
		}
		return $SubscribersArray;
	}
	
	/**
	 * Ensure Properties
	 * @param Doctrine_Event $Event
	 * @return boolean	wheter or not to save
	 */
	public function ensureProperties ( $Event, $Event_type ) {
		# Check
		if ( !in_array($Event_type,array('preSave')) ) {
			# Not designed for these events
			return null;
		}
		
		# Prepare
		$save = false;
		
		# Fetch
		$Content = $Event->getInvoker();
		$modified = $Content->getModified();
		
		# Ensure Position
		if ( !$this->position && $this->id ) {
			$Content->set('position',$this->id,false);
			$save = true;
		}
		
		# Ensure Path
		if ( array_key_exists('code', $modified) && $this->code ) {
			$path = $this->code;
			if ( $this->Parent_id )
				$path = trim($this->Parent->Route->path,'/') . '/' . trim($path,'/');
			$Content->set('path',$path,false);
			$save = true;
		}
		
		# Return
		return $save;
	}
	
	/**
	 * Ensure the Render of the Content and Description
	 * @param Doctrine_Event $Event
	 * @return bool
	 */
	public function ensureRender ( $Event, $Event_type ) {
		# Check
		if ( !in_array($Event_type,array('preSave')) ) {
			# Not designed for these events
			return null;
		}
		
		# Prepare
		$save = false;
		$View = Bal_App::getView();
		
		# Fetch
		$Content = $Event->getInvoker();
		$modified = $Content->getModified();
		
		# Content
		if ( array_key_exists('content', $modified) ) {
			# Render Content
			$content_rendered = $View->content()->renderContentContent($Content);
			$Content->set('content_rendered', $content_rendered, false);
			# Save
			$save = true;
		}
		
		# Description
		if ( array_key_exists('description', $modified) ) {
			# Auto
			$Content->set('description_auto',false,false);
			# Render Description
			$description_rendered = $View->content()->renderContentDescription($Content);
			$Content->set('description_rendered', $description_rendered, false);
			# Save
			$save = true;
		}
		elseif ( $Content->description_auto || !$Content->description ) {
			# Auto
			$Content->set('description_auto',true,false);
			# Render Description
			$description_rendered = substr(preg_replace('/\s\s+/',' ',strip_tags($Content->content_rendered)), 0, 1000);
			if ( reallyempty($description_rendered) ) $description_rendered = '<!--[empty/]-->';
			$Content->set('description', $description_rendered, false);
			$Content->set('description_rendered', $description_rendered, false);
			# Save
			$save = true;
		}
		
		# Return save
		return $save;
	}
	
	/**
	 * Ensure Tags
	 * @param Doctrine_Event $Event
	 * @return bool
	 */
	public function ensureContentTags ( $Event, $Event_type ) {
		# Check
		if ( !in_array($Event_type,array('preSave','postSave')) ) {
			# Not designed for these events
			return null;
		}
		
		# Handle
		return Bal_Doctrine_Core::ensureTags($Event,'ContentTags','tags');
	}
	
	/**
	 * Ensure Send out to Subscribers
	 * @param Doctrine_Event $Event
	 * @return boolean	wheter or not to save
	 */
	public function ensureSend ( $Event, $Event_type ) {
		# Check
		if ( !in_array($Event_type,array('postSave')) ) {
			# Not designed for these events
			return null;
		}
		
		# Prepare
		$save = false;
		
		# Fetch
		$Content = $Event->getInvoker();
		$modified = $Content->getLastModified();
		
		# Subscription Message
		if ( $Content->status === 'published' && array_key_exists('content_rendered', $modified) ) {
			# Update Message
			$Receivers = $this->getUnsentSubscribers();
			foreach ( $Receivers as $Receiver ) {
				$Message = new Message();
				$Message->UserFor = $Receiver;
				$Message->Content = $Content;
				$Message->useTemplate('content-subscription');
				$Message->save();
			}
		}
		
		# Return
		return $save;
	}
	
	/**
	 * Ensure Consistency
	 * @param Doctrine_Event $Event
	 * @return boolean	wheter or not to save
	 */
	public function ensure ( $Event, $Event_type ){
		return Bal_Doctrine_Core::ensure($Event,$Event_type,array(
			'ensureProperties',
			'ensureContentTags',
			'ensureRender',
			'ensureSend'
		));
	}
	
	/**
	 * Backup old values
	 * @param Doctrine_Event $Event
	 */
	public function preSave ( $Event ) {
		# Prepare
		$Invoker = $Event->getInvoker();
		$result = true;
		
		# Ensure
		if ( self::ensure($Event, __FUNCTION__) ) {
			// no need
		}
		
		# Done
		return method_exists(get_parent_class($this),$parent_method = __FUNCTION__) ? parent::$parent_method($Event) : $result;
	}
	
	/**
	 * Ensure
	 * @param Doctrine_Event $Event
	 * @return string
	 */
	public function postSave ( $Event ) {
		# Prepare
		$Invoker = $Event->getInvoker();
		$result = true;
	
		# Ensure
		if ( self::ensure($Event, __FUNCTION__) ) {
			$Invoker->save();
		}
		
		# Done
		return method_exists(get_parent_class($this),$parent_method = __FUNCTION__) ? parent::$parent_method($Event) : $result;
	}

	/**
	 * Ensure Route id exists
	 * @param Doctrine_Event $Event
	 * @return string
	 */
	public function postInsert ( $Event ) {
		# Prepare
		$Invoker = $Event->getInvoker();
		$Route = $Invoker->Route;
		$result = true;
		
		# Ensure
		if ( !$Route->data['id'] ) {
			$data = $Route->data;
			$data['id'] = $Invoker->id;
			$Route->data = $data;
			$Route->save();
		}
		
		# Done
		return method_exists(get_parent_class($this),$parent_method = __FUNCTION__) ? parent::$parent_method($Event) : $result;
	}
	
	# ========================
	# CRUD HELPERS
	
	
	/**
	 * Fetch all the records for public access
	 * @version 1.0, April 12, 2010
	 * @return mixed
	 */
	public static function fetch ( array $params = array() ) {
		# Prepare
		Bal_Doctrine_Core::prepareFetchParams($params,array('fetch','Root','Parent','User','Author','ContentTags','codes','featured','recent'));
		extract($params);
		
		# Query
		$Query = Doctrine_Query::create();
		
		# Handle
		switch ( $fetch ) {
			case 'list':
				$Query
					->select('Content.id, Content.code, Content.title, Content.tagline, Content.position, Content.status, Content.updated_at, Route.*, Parent.id, Parent.code, Parent.title, ContentTag.name, Author.id, Author.code, Author.displayname, Avatar.id, Avatar.url')
					->from('Content, Content.Route Route, Content.Parent Parent, Content.ContentTags ContentTag, Content.Author Author, Content.Avatar Avatar')
					->orderBy('Content.position ASC, Content.id ASC')
					;
				break;
				
			case 'simplelist':
				$Query
					->select('Content.id, Content.code, Content.title, Content.tagline, Content.position, Content.status, Parent.id, Parent.code, Route.*')
					->from('Content, Content.Route Route, Content.Parent Parent, Content.ContentTags ContentTag')
					->orderBy('Content.position ASC, Content.id ASC')
					;
				break;
				
			default:
				$Query
					->select('Content.*, Route.*, Parent.id, Parent.code, Parent.title, ContentTag.name, Author.id, Author.code, Author.displayname, Avatar.id, Avatar.url')
					->from('Content, Content.Route Route, Content.Parent Parent, Content.ContentTags ContentTag, Content.Author Author, Content.Avatar Avatar')
					->orderBy('Content.position ASC, Content.id ASC')
					;
				break;
		}
		
		# Criteria
		if ( $User ) {
			$identifier = Bal_Doctrine_Core::resolveIdentifier('Content',$User);
			$Query->andWhere(
				'Content.Author.'.$identifier['column'].' = ?',
				$identifier['value']
			);
		}
		if ( $Author ) {
			$identifier = Bal_Doctrine_Core::resolveIdentifier('Content',$Author);
			$Query->andWhere(
				'Content.Author.'.$identifier['column'].' = ?',
				$identifier['value']
			);
		}
		if ( $Parent ) {
			$identifier = Bal_Doctrine_Core::resolveIdentifier('Content',$Parent);
			$Query->andWhere(
				'Content.Parent.'.$identifier['column'].' = ?',
				$identifier['value']
			);
		}
		if ( $codes ) {
			$Query->andWhereIn('Content.code', $codes);
		}
		if ( $featured ) {
			$tags = array('featured');
			$Query->andWhere('Content.tags LIKE "%featured%"');
			//$Query->andWhere('EXISTS (SELECT Content.ContentTags.name FROM Content.ContentTags WHERE Content.ContentTags.name IN ("'.implode($tags,'","').'"))');
		}
		if ( $recent ) {
			$Query->orderBy('Content.published_at DESC, Content.position ASC, Content.id ASC');
		}
		if ( $ContentTags ) {
			$tags = $ContentTags;
			//$Query->andWhere('EXISTS (SELECT c.id FROM Content c, c.ContentTags ct WHERE c.id = Content.id AND cc.name IN ("'.implode($tags,'","').'"))');
			//$Query->andWhereIn('ContentTag.name', $ContentTags);
		}
		if ( $Root ) {
			$Query->andWhere('Content.Parent_id IS ?', null);
			//NOT EXISTS (SELECT ContentOrphan.id FROM Content ContentOrphan WHERE ContentOrphan.id = Content.Parent_id)');
		}
		
		# Fetch
		$result = Bal_Doctrine_Core::prepareFetchResult($params,$Query,'Content');
		
		# Done
		return $result;
	}
	
}