<?php

namespace Drupal\emaillog_exceptions\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure exception strings for emaillog notifications.
 */
class EmailLogExceptionsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'emaillog_exceptions_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['emaillog_exceptions.settings'];
  }

  /**
   * Builds the configuration form.
   *
   * @param array $form
   *   The form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('emaillog_exceptions.settings');
    $form['exceptions'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Exception strings'),
      '#description' => $this->t('Enter one string per line. If any of these strings are found in an emaillog notification, the email will not be sent.'),
      '#default_value' => $config->get('exceptions') ? implode("\n", $config->get('exceptions')) : '',
      '#rows' => 10,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   The form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $exceptions = array_filter(array_map('trim', explode("\n", $form_state->getValue('exceptions'))));
    $this->config('emaillog_exceptions.settings')
      ->set('exceptions', $exceptions)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
