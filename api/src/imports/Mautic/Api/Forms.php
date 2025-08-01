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
 * Forms Context
 */
class Forms extends Api
{

    /**
     * {@inheritdoc}
     */
    protected $endpoint = 'forms';

    /**
     * {@inheritdoc}
     */
    protected $listName = 'forms';

    /**
     * {@inheritdoc}
     */
    protected $itemName = 'form';

    /**
     * {@inheritdoc}
     */
    protected $searchCommands = array(
        'ids',
        'is:published',
        'is:unpublished',
        'is:mine',
        'is:uncategorized',
        'category',
        'name',
        'has:results',
    );

    /**
     * Remove fields from a form
     *
     * @param integer $formId
     * @param array   $fieldIds
     *
     * @return array|mixed
     */
    public function deleteFields($formId, array $fieldIds)
    {
        return $this->makeRequest($this->endpoint.'/'.$formId.'/fields/delete', array('fields' => $fieldIds), 'DELETE');
    }

    /**
     * Remove actions from a form
     *
     * @param integer $formId
     * @param array   $actionIds
     *
     * @return array|mixed
     */
    public function deleteActions($formId, array $actionIds)
    {
        return $this->makeRequest($this->endpoint.'/'.$formId.'/actions/delete', array('actions' => $actionIds), 'DELETE');
    }

    /**
     * Get a single submission
     *
     * @param int $formId
     * @param int $submissionId
     *
     * @return array|mixed
     */
    public function getSubmission($formId, $submissionId)
    {
        return $this->makeRequest("{$this->endpoint}/$formId/submissions/$submissionId");
    }

    /**
     * Get a list of form submissions
     *
     * @param int    $formId
     * @param string $search
     * @param int    $start
     * @param int    $limit
     * @param string $orderBy
     * @param string $orderByDir
     * @param bool   $publishedOnly
     * @param bool   $minimal
     *
     * @return array|mixed
     */
    public function getSubmissions($formId, $search = '', $start = 0, $limit = 0, $orderBy = '', $orderByDir = 'ASC', $publishedOnly = false, $minimal = false)
    {
        $parameters = array(
            'search'        => $search,
            'start'         => $start,
            'limit'         => $limit,
            'orderBy'       => $orderBy,
            'orderByDir'    => $orderByDir,
            'publishedOnly' => $publishedOnly,
            'minimal'       => $minimal
        );

        $parameters = array_filter($parameters);

        return $this->makeRequest("{$this->endpoint}/$formId/submissions", $parameters);
    }

    /**
     * Get a list of form submissions for specific form and contact
     *
     * @param int    $formId
     * @param int    $contactId
     * @param string $search
     * @param int    $start
     * @param int    $limit
     * @param string $orderBy
     * @param string $orderByDir
     * @param bool   $publishedOnly
     * @param bool   $minimal
     *
     * @return array|mixed
     */
    public function getSubmissionsForContact($formId, $contactId, $search = '', $start = 0, $limit = 0, $orderBy = '', $orderByDir = 'ASC', $publishedOnly = false, $minimal = false)
    {
        $parameters = array(
            'search'        => $search,
            'start'         => $start,
            'limit'         => $limit,
            'orderBy'       => $orderBy,
            'orderByDir'    => $orderByDir,
            'publishedOnly' => $publishedOnly,
            'minimal'       => $minimal
        );

        $parameters = array_filter($parameters);

        return $this->makeRequest("{$this->endpoint}/$formId/submissions/contact/$contactId", $parameters);
    }
}
