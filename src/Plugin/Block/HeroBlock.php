<?php

namespace Drupal\webspark_module_hero\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Drupal\image\Entity\ImageStyle;

/**
 * Provides a 'Hero' Block.
 *
 * @Block(
 *   id = "hero_block",
 *   admin_label = @Translation("Hero block"),
 *   category = @Translation("WebSpark"),
 * )
 */
class HeroBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];

    $config = $this->getConfiguration();

    // Load image and get url if one exists.
    $fid =$config['hero_image'];
    /*if ($fid > 0) {
      $file = File::load($fid);
      if ($file == NULL) {
        $url = '';
      }
      $uri = $file->getFileUri();
      $style = ImageStyle::load('hero');
      $url = $style->buildUrl($uri);
    }
    else{
      $url = '';
    }*/

    $url = 'https://source.unsplash.com/random/1920x1200';

    // Build array of hero components.
    $build['hero']['size'] = $config['hero_size'];
    $build['hero']['hero_image'] = $url;
    $build['hero']['hero_body'] = $config['hero_body'];
    $build['hero']['html_tag'] = 'h1';
    $build['hero']['heading'] = 'The heading';
    $build['hero']['url'] = $config['hero_link_url'];
    $build['hero']['label'] = 'The button label';

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['hero_size'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this
        ->t('Select size'),
      '#options' => [
        'sm' => $this->t('Small'),
        'md' => $this->t('Medium'),
        'lg' => $this->t('Large'),
      ],
      '#default_value' => isset($config['hero_size']) ? $config['hero_size'] : '',
    ];

    $form['hero_image'] = array(
      '#type' => 'managed_file',
      '#name' => 'custom_content_block_image',
      '#title' => t('Hero Image'),
      '#size' => 40,
      '#description' => t(""),
      '#upload_location' => 'public://',
      '#default_value' => isset($config['hero_image']) ? [$config['hero_image']] : '',
    );

    $form['hero_body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Hero Text'),
      '#rows' => 4,
      '#cols' => 5,
      '#description' => $this->t('Text that will be shown below the header in large mode'),
      '#default_value' => isset($config['hero_body']) ? $config['hero_body'] : '',
    ];

    $form['hero_link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CTA Text'),
      '#default_value' => isset($config['hero_link_text']) ? $config['hero_link_text'] : '',
    ];

    $form['hero_link_url'] = [
      '#type' => 'url',
      '#title' => $this->t('CTA Url'),
      '#url' => '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $hero_size      = $form_state->getValue('hero_size');
    $hero_body      = $form_state->getValue('hero_body');
    $hero_image     = $form_state->getValue('hero_image');
    $hero_link_text = $form_state->getValue('hero_link_text');
    $hero_link_url  = $form_state->getValue('hero_link_url');

    $hero_image_fid = array_shift($hero_image);

    $this->configuration['hero_body']         = $hero_body;
    $this->configuration['hero_image']        = $hero_image_fid;
    $this->configuration['hero_size']         = $hero_size;
    $this->configuration['hero_link_text']    = $hero_link_text;
    $this->configuration['hero_link_url']     = $hero_link_url;
  }

}
