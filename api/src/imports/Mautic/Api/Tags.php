<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

namespace Backend\imports\Mautic\Api;

/**
 * Tags Context
 */
class Tags extends Api
{

    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'tags';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'tags';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'tag';
}
