<?php

namespace PharmIT\Push;

interface PushService
{
    /**
     * Load the configuration for this push service
     *
     * @return bool
     *   If configuration loading was successful
     */
    public function loadConfiguration();

    /**
     * Set the message text for this push message.
     * This will clear any previous message
     *
     * @param string $message
     *   The message text to set
     * @return self
     *   This instance, to allow chaining
     */
    public function setMessageText($message);

    /**
     * Set the message data for this push message.
     * This will clear any previous message data
     *
     * @param array $data
     *   The message data to set
     * @return self
     *   This instance, to allow chaining
     */
    public function setMessageData(array $data);

    /**
     * Add a recipient for the current message
     *
     * @param string $recipient
     *   The ID of the recipient to add
     * @return self
     *   This instance, to allow chaining
     */
    public function addRecipient($recipient);

    /**
     * Add recipients for the current message
     *
     * @param array $recipients
     *   The ID's of the recipients to add
     * @return self
     *   This instance, to allow chaining
     */
    public function addRecipients(array $recipients);

    /**
     * Send the message
     *
     * @return array
     *   An array of all recipient ID's to which the message was successfully sent
     */
    public function send();

    /**
     * Get the ID's of the recipients that did not receive the message successfully
     *
     * @return array
     */
    public function getFailedRecipients();
}