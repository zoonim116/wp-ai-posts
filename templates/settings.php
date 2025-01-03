<?php

use Wpapi\Core\KeyStatus;
use Wpapi\Helper;

if (! defined('ABSPATH')) {
    exit;
}
$key_status = Helper::getKeyStatus(get_option('wpai_key_valid'));
?>

<div class="nav-tab-content">
    <div class="nav-tab-inside">
        <h3><?php esc_html_e('General Settings', 'wp-ai') ?></h3>
        <form action="options.php" method="POST" enctype="multipart/form-data">
            <?php settings_fields('wpai_settings') ?>
            <?php do_settings_sections('wpai_settings') ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="wpai_key"><?php esc_html_e('OpenAi Key', 'wpai') ?><span class="required">*</span></label>
                        </th>
                        <td>
                            <input id="wpai_key" name="wpai_key" type="text" value="<?php esc_attr_e(get_option('wpai_key')) ?>" required>
                            <span>Key status: <span class="status <?php echo strtolower($key_status)?>"><?php echo $key_status; ?></span> </span>
                            <p class="description"><?php esc_html_e('Save changes before validate API key', 'wp-ai') ?></p>
                        </td>
                    </tr>
                    <tr class="prompt-wrapper hidden">
                        <th scope="row">
                            <label for="wpai_prompt"><?php esc_html_e('Prompt', 'wpai') ?></label>
                        </th>
                        <td>
                            <input id="wpai_prompt" name="wpai_prompt" type="text" value="<?php esc_attr_e(get_option('wpai_prompt')) ?>">
                            <button class="button button-secondary generate"><?php esc_html_e('Generate', 'wpai'); ?></button><span class="loader"></span>
                        </td>
                    </tr>
                    <?php if (get_option('wpai_key_valid') == KeyStatus::VALID->value): ?>
                        <tr>
                            <th></th>
                            <td><button class="button generate-post"><?php esc_html_e('Generate post', 'wpai'); ?></button></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <input type="hidden" name="wpai_key_valid" value="<?php echo get_option('wpai_key_valid'); ?>" />
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e('Save Changes', 'wpai') ?>">
                <button class="button button-link validate-api-button"><?php esc_html_e('Validate API Key', 'wpai'); ?></button>
                <span class="loader"></span>
            </p>
        </form>
    </div>
</div>
