<?php 
/**
 * This helper retrieves stats from a /mail/send API call
 * 
 * PHP Version - 5.6, 7.0, 7.1, 7.2
 *
 * @package   SendGrid\Mail
 * @author    Elmer Thomas <dx@sendgrid.com>
 * @copyright 2018-19 Twilio SendGrid
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @version   GIT: <git_id>
 * @link      http://packagist.org/packages/sendgrid/sendgrid 
 */
namespace SendGrid\Stats;

/**
 * This class is used to retrieve stats from a /mail/send API call
 * 
 * @package SendGrid\Mail
 */
class Stats
{
    const DATE_FORMAT = 'Y-m-d';

    // @var string
    private $startDate;

    // @var string
    private $endDate;

    // @var string
    private $aggregatedBy;

    /**
     * Stats constructor
     * 
     * @param string $startDate    YYYYMMDD
     * @param string $endDate      YYYYMMDD
     * @param string $aggregatedBy day|week|month
     */
    public function __construct($startDate, $endDate = null, $aggregatedBy = null)
    {
        $this->validateDateFormat($startDate);
        if (null !== $endDate) {
            $this->validateDateFormat($endDate);
        }
        if (null !== $aggregatedBy) {
            $this->validateOptions(
                'aggregatedBy',
                $aggregatedBy,
                ['day', 'week', 'month']
            );
        }
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->aggregatedBy = $aggregatedBy;
    }

    /**
     * Retrieve global stats parameters, start date, end date and
     * aggregated by
     * 
     * @return array
     */
    public function getGlobal()
    {
        return [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'aggregated_by' => $this->aggregatedBy
        ];
    }

    /**
     * Retrieve an array of categories
     * 
     * @param array $categories 
     * 
     * @return array
     * @throws \Exception
     */
    public function getCategory($categories)
    {
        $this->validateNumericArray('categories', $categories);
        $stats = $this->getGlobal();
        $stats['categories'] = $categories;
        return $stats;
    }

    /**
     * Retrieve global stats parameters, start date, end date and
     * aggregated for the given set of subusers 
     * 
     * @param array $subusers Subuser accounts
     * 
     * @return array
     * @throws \Exception
     */
    public function getSubuser($subusers)
    {
        $this->validateNumericArray('subusers', $subusers);
        $stats = $this->getGlobal();
        $stats['subusers'] = $subusers;
        return $stats;
    }

    /**
     * Retrieve global stats parameters, start date, end date, 
     * aggregated by, sort by metric, sort by direction, limit
     * and offset
     *
     * @param string  $sortByMetric    blocks|bounce_drops|bounces|
     *                                 clicks|deferred|delivered|
     *                                 invalid_emails|opens|processed|
     *                                 requests|spam_report_drops|
     *                                 spam_reports|unique_clicks|
     *                                 unique_opens|unsubscribe_drops|
     *                                 unsubsribes
     * @param string  $sortByDirection asc|desc
     * @param integer $limit           The number of results to return
     * @param integer $offset          The point in the list to begin 
     *                                 retrieving results
     * 
     * @return array
     * @throws \Exception
     */
    public function getSum(
        $sortByMetric = 'delivered',
        $sortByDirection = 'desc',
        $limit = 5, $offset = 0
    ) {
        $this->validateOptions(
            'sortByDirection',
            $sortByDirection,
            ['asc', 'desc']
        );
        $this->validateInteger('limit', $limit);
        $this->validateInteger('offset', $offset);
        $stats = $this->getGlobal();
        $stats['sort_by_metric'] = $sortByMetric;
        $stats['sort_by_direction'] = $sortByDirection;
        $stats['limit'] = $limit;
        $stats['offset'] = $offset;
        return $stats;
    }

    /**
     * Retrieve monthly stats by subuser
     * 
     * @param string  $subuser         Subuser account
     * @param string  $sortByMetric    blocks|bounce_drops|bounces|
     *                                 clicks|deferred|delivered|
     *                                 invalid_emails|opens|processed|
     *                                 requests|spam_report_drops|
     *                                 spam_reports|unique_clicks|
     *                                 unique_opens|unsubscribe_drops|
     *                                 unsubsribes
     * @param string  $sortByDirection asc|desc
     * @param integer $limit           The number of results to return
     * @param integer $offset          The point in the list to begin 
     *                                 retrieving results
     * 
     * @return array
     * @throws \Exception
     */
    public function getSubuserMonthly(
        $subuser = null,
        $sortByMetric = 'delivered',
        $sortByDirection = 'desc',
        $limit = 5,
        $offset = 0
    ) {
        $this->validateOptions(
            'sortByDirection',
            $sortByDirection,
            ['asc', 'desc']
        );
        $this->validateInteger('limit', $limit);
        $this->validateInteger('offset', $offset);
        return [
            'date' => $this->startDate,
            'subuser' => $subuser,
            'sort_by_metric' => $sortByMetric,
            'sort_by_direction' => $sortByDirection,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    /**
     * Validate the date format
     * 
     * @param string $date YYYY-MM-DD
     * 
     * @return null
     * @throws \Exception
     */
    protected function validateDateFormat($date)
    {
        if (false === \DateTime::createFromFormat(Stats::DATE_FORMAT, $date)) {
            throw new \Exception('Date must be in the YYYY-MM-DD format.');
        }
    }

    /**
     * Validate options
     * 
     * @param string $name    Name of option
     * @param string $value   Value of option
     * @param array  $options Array of options
     * 
     * @return null
     * @throws \Exception
     */
    protected function validateOptions($name, $value, $options)
    {
        if (!in_array($value, $options)) {
            throw new \Exception(
                $name . ' must be one of: ' . implode(', ', $options)
            );
        }
    }

    /**
     * Validate integer
     *
     * @param string  $name  Name as a string
     * @param integer $value Value as an integer
     * 
     * @return null
     * @throws \Exception
     */
    protected function validateInteger($name, $value)
    {
        if (!is_integer($value)) {
            throw new \Exception($name . ' must be an integer.');
        }
    }

    /**
     * Validate a numeric array
     * 
     * @param string $name  Name as a string
     * @param array  $value Value as an array of integers
     * 
     * @return null
     * @throws \Exception
     */
    protected function validateNumericArray($name, $value)
    {
        if (!is_array($value) || empty($value) || !$this->isNumeric($value)) {
            throw new \Exception($name . ' must be a non-empty numeric array.');
        }
    }

    /**
     * Determine if the array is numeric
     * 
     * @param array $array Array of values
     * 
     * @return bool
     */
    protected function isNumeric(array $array)
    {
        return array_keys($array) == range(0, oldCount($array) - 1);
    }
}
