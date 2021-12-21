<?php

namespace Drupal\dummy_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class Payment extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'payment_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $server = \Drupal::request()->server;
    $protocol = ((!empty($server->get('HTTPS')) && $server->get('HTTPS') != 'off') || $server->get('SERVER_PORT') == 443) ? "https://" : "http://";
    $surl = $protocol . $server->get('HTTP_HOST') . $server->get('REQUEST_URI') . '/success';

    $form['amount'] = [
      '#type' => 'number',
      '#title' => 'Amount',
      '#required' => true,
      '#attributes' => [
        'class' => ['amount'],
      ],
    ];
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => 'First Name',
      '#attributes' => [
        'class' => ['fname'],
      ],
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => 'Last Name',
      '#attributes' => [
        'class' => ['lname'],
      ],
    ];
    $form['mobile'] = [
      '#type' => 'textfield',
      '#title' => 'Mobile/Cell Number',
      '#attributes' => [
        'class' => ['mobile'],
      ],
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => 'Email',
      '#required' => true,
      '#attributes' => [
        'class' => ['email'],
      ],
    ];
    $form['udf5'] = [
      '#type' => 'hidden',
      '#value' => 'BOLT_KIT_PHP7',
      '#attributes' => [
        'class' => ['udf5'],
      ],
    ];
    $form['surl'] = [
      '#type' => 'hidden',
      '#value' => $surl,
      '#attributes' => [
        'class' => ['surl'],
      ],
    ];
    $form['key'] = [
      '#type' => 'hidden',
      '#value' => 'JPM7Fg',
      '#attributes' => [
        'class' => ['key'],
      ],
    ];
    $form['hash'] = [
      '#type' => 'hidden',
      '#value' => '',
      '#attributes' => [
        'class' => ['hash'],
      ],

    ];
    $form['salt'] = [
      '#type' => 'hidden',
      '#value' => 'HuRDUZl1QMs2Qp1FABfcMZI3EDwZxqdz',
      '#attributes' => [
        'class' => ['salt'],
      ],
    ];
    $form['txnid'] = [
      '#type' => 'hidden',
      '#value' => "Txn" . rand(10000,99999999),
      '#attributes' => [
        'class' => ['txnid'],
      ],
    ];
    $form['pinfo'] = [
      '#type' => 'textfield',
      '#value' => 'P01,P02',
      '#title' => 'Product info',
      '#attributes' => [
        'class' => ['pinfo'],
      ],
    ];
    $form['button'] = [
      '#type' => 'markup',
      '#markup' => '<a class="button">Pay</a>',
    ];
    $form['#attached']['library'][] = 'dummy_api/payment-js';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t("Payment is successfull of amount %amount Rs.", ['%amount' => $form_state->getValue('amount')]));
  }
}