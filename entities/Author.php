<?php
/**
 * File Author.php
 *
 * PHP version 5.4+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.1.0
 * @link      http://www.sweelix.net
 * @category  entities
 * @package   sweelix.yii1.ext.entities
 */

namespace sweelix\yii1\ext\entities;
use sweelix\yii1\ext\db\ar\Author as ActiveRecordAuthor;

/**
 * Class Author
 *
 * This is the model class for table "authors".
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.1.0
 * @link      http://www.sweelix.net
 * @category  entities
 * @package   sweelix.yii1.ext.entities
 * @since     1.0.0
 *
 * @property Language  $language
 * @property Content[] $contents
 * @property Node[]    $nodes
 *
 */
class Author extends ActiveRecordAuthor {
	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className entity classname automatically set
	 *
	 * @return Author the static model class
	 * @since  1.0.0
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * The followings are the available model relations:
	 *
	 * @return array relational rules.
	 * @since  1.0.0
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'language' => array(self::BELONGS_TO, 'sweelix\yii1\ext\entities\Language', 'languageId'),
			'contents' => array(self::HAS_MANY, 'sweelix\yii1\ext\entities\Content', 'authorId'),
			'nodes' => array(self::HAS_MANY, 'sweelix\yii1\ext\entities\Node', 'authorId'),
		);
	}
}