<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\actions;

use simialbi\yii2\formbuilder\models\Form;
use simialbi\yii2\sms\MessageInterface;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\mail\MailerInterface;
use yii\web\ServerErrorHttpException;

/**
 * Send mail action gets the data from a [[Form]] build by form builder and sends it via mailer component to configured
 * recipients.
 */
class SendMailAction extends BaseAction implements ActionInterface
{
    /**
     * @var array|string The recipient(s) of the mail.
     *
     * @see MessageInterface::setTo() to see how to use this parameter.
     */
    public $recipients;

    /**
     * @var array The sender of the mail.
     *
     * @see MessageInterface::setFrom() to see how to use this parameter.
     */
    public $sender;

    /**
     * @var string|MailerInterface|array Mailer component to use for sending mails.
     */
    public $mailer = 'mailer';

    /**
     * @var string|array The template(s) to use to compose the mail. The parameter `$model` will be passed to the
     * templates which represents an instance of [[\yii\base\DynamicModel|DynamicModel]].
     *
     * @see MailerInterface::compose() to see how to use this parameter.
     */
    public $template;

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function init()
    {
        try {
            $this->mailer = Instance::ensure($this->mailer);
        } catch (InvalidConfigException $e) {
            throw new ServerErrorHttpException('Mailer component not found', 0, $e);
        }
        if (!($this->mailer instanceof MailerInterface)) {
            throw new InvalidConfigException("Mailer component must implement '\yii\mail\MailerInterface'.");
        }
        if (empty($this->recipients)) {
            throw new InvalidConfigException('No recipients configured.');
        }
        if (empty($this->sender)) {
            throw new InvalidConfigException('No sender configured.');
        }
        if (empty($this->template)) {
            $this->template = [
                'html' => '@simialbi/yii2/formbuilder/mail/send-mail-html',
                'text' => '@simialbi/yii2/formbuilder/mail/send-mail-text'
            ];
        }
        parent::init();
    }

    /**
     * {@inheritDoc}
     */
    public function run(Form $form, DynamicModel $model): bool
    {
        $this->sender = (empty($this->sender['name'])) ? $this->sender['email'] : [$this->sender['email'] => $this->sender['name']];
        $this->recipients = ArrayHelper::map($this->recipients, 'email', 'name');

        $mail = $this->mailer
            ->compose($this->template, ['model' => $model])
            ->setTo($this->recipients)
            ->setFrom($this->sender);

        return $mail->send();
    }
}
