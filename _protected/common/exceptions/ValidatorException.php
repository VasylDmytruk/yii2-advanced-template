<?php

namespace common\exceptions;

use Yii;
use yii\base\Event;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class ValidatorException Uses to send response error array ("details").
 * Example:
 * ```
 * {
 *      "name": "Model validation error",
 *      "message": "There is no user with such email.",
 *      "status": 400,
 *      "type": "frontend\\modules\\api\\v1\\exceptions\\ApiException",
 *      "details": null|array|object
 * }
 * ```
 */
class ValidatorException extends HttpException
{
    /**
     * Name of response data details index.
     */
    const DATA_DETAILS = 'errors';

    /**
     * @var array|null|object|string
     */
    protected $_details = null;

    /**
     * ApiException constructor.
     *
     * @param string $message
     * @param null|string|array|object $details Could be list of errors.
     * @param int $status
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $details = null, $status = 400, $code = 0, \Exception $previous = null)
    {
        $this->_details = $details;

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, [$this, 'beforeSendResponse']);

        parent::__construct($status, $message, $code, $previous);
    }

    /**
     * Adds details field to response before send
     * @param Event $event
     */
    public function beforeSendResponse($event)
    {
        $response = $event->sender;
        if ($response->data !== null && is_array($response->data)) {
            $response->data[self::DATA_DETAILS] = $this->_details;
        }
    }

    /**
     * @return array|null|object|string
     */
    public function getDetails()
    {
        return $this->_details;
    }

    /**
     * Gets details in json. If details empty, returns empty string.
     *
     * @return string Returns details in json. If details empty, returns empty string.
     */
    public function getDetailsAsJson(): string
    {
        $jsonDetails = '';

        if (!empty($this->_details)) {
            $jsonDetails = Json::encode($this->_details);
        }

        return $jsonDetails;
    }

    /**
     * Returns details if not empty, otherwise returns message.
     *
     * @return array|string Details if not empty, otherwise returns message.
     */
    public function getDetailsOrMessage()
    {
       $result = !empty($this->_details) ? $this->_details : $this->getMessage();

       return $result;
    }
}
