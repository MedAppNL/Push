<?php
namespace PharmIT\Push\Services;

abstract class AbstractPushService implements PushService
{
    /**
     * @var string|null
     *   The text of the message
     */
    protected $messageText = null;

    /**
     * @var string|null
     *   The text of the message
     */
    protected $messageTitle = null;

    /**
     * @var array|null
     *   The data of the message
     */
    protected $messageData = null;

    /**
     * @var array|null
     *   Additional fields to add to the message payload
     */
    protected $additionalFields = null;

    /**
     * @var array
     *   The recipient ID's
     */
    protected $recipients = [];

    /**
     * @var array
     *   The failed recipient ID's
     */
    protected $failedRecipients = [];

    /**
     * @inheritdoc
     */
    public function setMessageText($message)
    {
        $this->messageText = $message;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setMessageData(array $data)
    {
        $this->messageData = $data;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setAdditionalFields(array $data)
    {
        $this->additionalFields = $data;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addRecipient($recipient)
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addRecipients(array $recipients)
    {
        $this->recipients = array_merge($this->recipients, $recipients);
        return $this;
    }

    public function getFailedRecipients()
    {
        return $this->failedRecipients;
    }
}
